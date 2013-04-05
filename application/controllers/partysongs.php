<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partysongs extends Crowdrock_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['page_title'] = "";
    }

    function new_song($party_id) {
        if (!parent::authenticate())
            return;
        $this->data['party_song'] = new PartySong(array('party_id' => $party_id));

        $this->load->view('shared/header', $this->data);
        $this->load->view('partysongs/new', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function create($party_id) {
        if (!parent::authenticate())
            return;
        $party_song = new PartySong(array('title' => $this->input->post('title'), 'artist' => $this->input->post('artist'),
                    'album' => $this->input->post('album'), 'genre' => $this->input->post('genre'), 'time_str' => $this->input->post('time_str'),
                    'party_id' => $party_id));
        $is_saved = $party_song->is_valid() && $party_song->save();
        
        if ($this->is_mobile()) {
            $party = $party_song->party;
            $party_songs = PartySong::find('all', array('conditions' => array("party_id = ? and status is null or status not in (?)", $party->id, PartySong::$STATE_PLAYING), 'order' => 'up_vote_count desc'));
            $currently_playing_song = PartySong::first('all', array('conditions' => array("party_id = ? and status = ?", $party->id, PartySong::$STATE_PLAYING), 'order' => 'updated_at desc'));
            $this->data['access_token'] = $party->access_token;
            $this->data['party'] = $party;
            $this->data['party_songs'] = $party_songs;
            $this->data['currently_playing_song'] = $currently_playing_song;
            $this->data['page_title'] = $party->title;
            $this->data['party_song'] = new PartySong(array('party_id' => $party->id));
        }

        if ($is_saved) {
            if ($this->is_mobile()) {
                $this->load->view('partysongs/front_end_mobile2', $this->data);
                return;
            }

            redirect("partysongs/song_list/" . $party_song->party_id, 'refresh');
            return;
        } else {
            if ($this->is_mobile()) {
                $this->load->view('partysongs/front_end_mobile2', $this->data);
                return;
            }

            $this->data['party_song'] = $party_song;
            $this->load->view('shared/header', $this->data);
            $this->load->view('partysongs/new', $this->data);
            $this->load->view('shared/footer', $this->data);
            return;
        }
    }

    function edit($id) {
        if (!parent::authenticate())
            return;
        $this->data['party_song'] = PartySong::find($id);

        $this->load->view('shared/header', $this->data);
        $this->load->view('partysongs/edit', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function update($id) {
        if (!parent::authenticate())
            return;
        $party_song = PartySong::find($id); //new Song(array('title' => $this->input->post('title'), 'organizer_id' => $this->ion_auth->user()->row()->id));
        $song_attributes = array("title" => $this->input->post('title'), 'artist' => $this->input->post('artist'),
            'album' => $this->input->post('album'), 'genre' => $this->input->post('genre'), 'time_str' => $this->input->post('time_str'));

        if ($party_song->is_valid() && $party_song->update_attributes($song_attributes)) {
            redirect("partysongs/song_list/" . $party_song->party_id, 'refresh');
        } else {
            $this->data['party_song'] = $party_song;
            $this->data['message'] = "Failed to update song.";
            $this->data['error_messages'] = $party_song->errors->full_messages();
            $this->load->view('shared/header', $this->data);
            $this->load->view('partysongs/edit', $this->data);
            $this->load->view('shared/footer', $this->data);
        }
    }

    function song_list($party_id) {
        if (!parent::authenticate())
            return;
        $party = Party::find($party_id);
        $this->data['party_songs'] = $party->party_songs;
        $this->data['party'] = $party;

        $this->load->view('shared/header', $this->data);
        $this->load->view('partysongs/index', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function delete($id) {
        if (!parent::authenticate())
            return;
        $party_song = PartySong::find($id);
        $party_song->delete();
        redirect("partysongs/song_list/" . $party_song->party_id, 'refresh');
    }

    function import($party_id) {
        if (!parent::authenticate())
            return;
        $party = Party::find($party_id);
        $this->data['party_songs'] = $party->party_songs;
        $this->data['party'] = $party;

        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->session->set_flashdata('message', "No file selected. Please select a file.");
            redirect("partysongs/song_list/" . $party_id, 'refresh');
            return;
        }

        $config['upload_path'] = '/home/fattah/Desktop/files';
        $config['allowed_types'] = 'txt';
        $config['max_size'] = 1024 * 100;
        $config['encrypt_name'] = TRUE;
        $csv_array = $this->parse_csv($_FILES['file']['tmp_name'], array('delimiter' => "\t"));

        $imported_songs = array();
        $success_count = 0;
        $failure_count = 0;
        foreach ($csv_array as $key => $song_info) {
            $party_song = new PartySong(array('title' => $song_info[0], 'artist' => $song_info[1],
                        'album' => $song_info[3], 'genre' => $song_info[5], 'time_str' => $song_info[7], 'party_id' => $party_id));

            if ($party_song->save())
                $success_count += 1;
            else
                $failure_count += 1;
            array_push($imported_songs, $party_song);
        }
        $message = "" . $success_count . " Songs imported successfully. ";
        if ($failure_count > 0)
            $message += $failure_count . " Failed.";
        $this->session->set_flashdata('message', $message);

        redirect("partysongs/song_list/" . $party_id, 'refresh');
    }

    function parse_csv($file, $options = null) {
        $delimiter = empty($options['delimiter']) ? "," : $options['delimiter'];
        $to_object = empty($options['to_object']) ? false : true;
        $str = file_get_contents($file);
        $lines = explode("\r", $str);
        $field_names = explode($delimiter, array_shift($lines));
        foreach ($lines as $line) {
            // Skip the empty line
            if (empty($line))
                continue;
            $fields = explode($delimiter, $line);
            $_res = $to_object ? new stdClass : array();
            foreach ($field_names as $key => $f) {
                $value = array_key_exists($key, $fields) ? $fields[$key] : "";
                if ($to_object) {
                    $_res->{utf8_encode($f)} = $value;
                } else {
                    $_res[utf8_encode($f)] = $value;
                    $_res[$key] = $value;
                }
            }
            $res[] = $_res;
        }
        return $res;
    }

    public function show($party_id) {
        //phpinfo();
        if (!parent::authenticate())
            return;
        $party = Party::find($party_id);
        $party_songs = $party->party_songs;

        $this->data['party'] = $party;
        $this->data['party_songs'] = $party_songs;
        $this->data['popular_party_songs'] = $party->popular_songs();
        $this->data['up_vote_summary'] = $party->last_minute_request_summary(ParticipantRequest::$TYPE_UP_VOTE);
        $this->data['down_vote_summary'] = $party->last_minute_request_summary(ParticipantRequest::$TYPE_DOWN_VOTE);
        $this->data['play_request_summary'] = $party->last_minute_request_summary(ParticipantRequest::$TYPE_PLAY_REQUEST);
        $this->load->view('partysongs/show', $this->data);
    }

    public function update_playlist_ranking($party_id) {
        if (!parent::authenticate())
            return;
        $party = Party::find($party_id);
        $party->update_song_ranking();

        redirect('partysongs/show/' . $party_id);
    }

    public function mark_as_playing($party_song_id) {
        if (!parent::authenticate())
            return;
        //PartySong::table()->update(array('status' => NULL), array('status' => array(PartySong::$STATE_PLAYING)));
        //TODO:: Following is a temporary fix of above written method to update all other partysong
        $previous_playing_song = PartySong::find('first', array('status' => array(PartySong::$STATE_PLAYING)));
        if ($previous_playing_song != NULL)
            $previous_playing_song->update_attribute('status', NULL);

        $party_song = PartySong::find($party_song_id);
        $party_song->update_attribute('status', PartySong::$STATE_PLAYING);
        $party_song->update_attribute('play_count', ($party_song->play_count + 1));
        $party_song->add_play_point_to_voters();

        redirect("partysongs/show/" . $party_song->party_id, 'refresh');
    }

    public function reload_list($access_token) {
        $party = Party::find_by_access_token($access_token);
        $party_songs = PartySong::find('all', array('conditions' => array('party_id = ?', $party->id), 'order' => 'up_vote_count desc'));
        $this->data['access_token'] = $access_token;
        $this->data['party'] = $party;
        $this->data['party_songs'] = $party_songs;
        $this->data['page_title'] = $party->title;
        //echo "alert('test');";

        $list_items = str_replace("\r", '', addslashes($this->load->view('partysongs/song_list_mobile', $this->data, true)));
        $list_items = str_replace("\n", '', $list_items);
        echo "$('#listview-1').html(\"" . $list_items . "\");";
        //echo '$(\'#listview-1\').html("' . '' . '");';
        echo "$('#listview-1').listview();";
        echo "$('#listview-1').listview('refresh');";
        echo "$('#listview-1 a').buttonMarkup('refresh')";
    }

    public function front_end($access_token) {
        $party = Party::find_by_access_token($access_token);
        $party_songs = PartySong::find('all', array('conditions' => array("party_id = ? and status is null or status not in (?)", $party->id, PartySong::$STATE_PLAYING), 'order' => 'up_vote_count desc'));
        $currently_playing_song = PartySong::first('all', array('conditions' => array("party_id = ? and status = ?", $party->id, PartySong::$STATE_PLAYING), 'order' => 'updated_at desc'));
        $this->data['access_token'] = $access_token;
        $this->data['party'] = $party;
        $this->data['party_songs'] = $party_songs;
        $this->data['currently_playing_song'] = $currently_playing_song;
        $this->data['page_title'] = $party->title;
        $this->data['party_song'] = new PartySong(array('party_id' => $party->id));

        $this->session->set_userdata('current_party_id', $party->id);

        $this->load->view('partysongs/front_end_mobile2', $this->data);
    }

    public function up_vote($party_song_id, $access_token_param_name, $access_token) {
        if (!parent::authenticate())
            return;

        $party_song = PartySong::find($party_song_id);
        $request = new ParticipantRequest(array('party_song_id' => $party_song->id,
                    'request_type' => ParticipantRequest::$TYPE_UP_VOTE,
                    'party_id' => $party_song->party_id, 'user_id' => $this->ion_auth->user()->row()->id));
        if ($request->save())
            $this->session->set_flashdata('message', "Your vote executed");
        else
            $this->session->set_flashdata('message', $request->errors->on('user_id'));

        redirect('partysongs/front_end/' . $access_token);
    }

    public function down_vote($party_song_id, $access_token_param_name, $access_token) {
        if (!parent::authenticate())
            return;

        $party_song = PartySong::find($party_song_id);
        $request = new ParticipantRequest(array('party_song_id' => $party_song->id,
                    'request_type' => ParticipantRequest::$TYPE_DOWN_VOTE,
                    'party_id' => $party_song->party_id, 'user_id' => $this->ion_auth->user()->row()->id));
        if ($request->save())
            $this->session->set_flashdata('message', "Your vote executed");
        else
            $this->session->set_flashdata('message', $request->errors->on('user_id'));

        redirect('partysongs/front_end/' . $access_token);
    }

    public function play_request($party_song_id, $access_token_param_name, $access_token) {
        if (!parent::authenticate())
            return;

        $party_song = PartySong::find($party_song_id);
        $request = new ParticipantRequest(array('party_song_id' => $party_song->id,
                    'request_type' => ParticipantRequest::$TYPE_PLAY_REQUEST,
                    'party_id' => $party_song->party_id, 'user_id' => $this->ion_auth->user()->row()->id));
        if ($request->save())
            $this->session->set_flashdata('message', "Your vote executed");
        else
            $this->session->set_flashdata('message', $request->errors->on('user_id'));

        redirect('partysongs/front_end/' . $access_token);
    }

}
