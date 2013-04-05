<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Parties extends Crowdrock_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['message'] = "";
    }

    //redirect if needed, otherwise display the user list
    function index() {
        if (!parent::authenticate())
            return;

        $this->data['parties'] = Party::all(array('conditions' => 'organizer_id = ' . User::find($this->ion_auth->user()->row()->id)->organizer->id));

        $this->load->view('shared/header', $this->data);
        $this->load->view('parties/index', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function search() {
        if(isset ($_GET['lat']) && isset ($_GET['lon'])){
            $this->data['parties'] = Party::nearby((double)$_GET['lat'], (double)$_GET['lon'], 1);
        }else if(isset($_GET['query'])){
            $this->data['parties'] = Party::all(array('joins' => 'LEFT JOIN organizers o ON(o.id = parties.organizer_id)',
            'conditions' => array('title like ? or o.name like ?', '%' . $_GET['query'] . '%', '%' . $_GET['query'] . '%')));
        }else{
            $this->data['parties'] = array();
        }

        if($this->is_mobile()){
            $this->load->view('parties/index_mobile', $this->data);
        }
        else{
            $this->load->view('parties/search_result', $this->data);
        }
    }

    function upcoming(){
//        if (!parent::authenticate())
//            return;

        $this->data['parties'] = Party::all();

        $this->load->view('parties/index_mobile', $this->data);
    }

    function show($id){
        if (!parent::authenticate())
            return;

        $this->data['party'] = Party::find($id);

        $this->load->view('shared/header', $this->data);
        $this->load->view('parties/show', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function new_party() {
        if (!parent::authenticate())
            return;
        
        $this->data['party'] = new Party();

        $this->load->view('shared/header', $this->data);
        $this->load->view('parties/new', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function create() {
        if (!parent::authenticate())
            return;

        $party = new Party(array('title' => $this->input->post('title'), 'organizer_id' => User::find($this->ion_auth->user()->row()->id)->organizer->id, 'address' => $this->input->post('address'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')));

        if ($party->is_valid() && $party->save()) {
            $this->session->set_flashdata('message', "Party created successfully.");
            redirect("parties", 'refresh');
        } else {
            $this->session->set_flashdata('message', "Failed to create party.");
            $this->data['party'] = $party;
            $this->load->view('shared/header', $this->data);
            $this->load->view('parties/new', $this->data);
            $this->load->view('shared/footer', $this->data);
        }
    }

    function edit($id) {
        if (!parent::authenticate())
            return;

        log_message('error', 'test message party edit');
        $this->data['party'] = Party::find($id);

        $this->load->view('shared/header', $this->data);
        $this->load->view('parties/edit', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function update($id) {
        if (!parent::authenticate())
            return;

        $party = Party::find($id); //new Party(array('title' => $this->input->post('title'), 'organizer_id' => $this->ion_auth->user()->row()->id));

        if ($party->is_valid() && $party->update_attributes(array("title" => $this->input->post('title'), 'address' => $this->input->post('address'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude')))) {
            redirect("parties", 'refresh');
        } else {
            $this->data['party'] = $party;
            $this->data['message'] = "Failed to update party.";
            $this->data['error_messages'] = $party->errors->full_messages();
            $this->load->view('shared/header', $this->data);
            $this->load->view('parties/edit', $this->data);
            $this->load->view('shared/footer', $this->data);
        }
    }

    function delete($id) {
        if (!parent::authenticate())
            return;

        $party = Party::find($id);
        $party->delete();
        redirect("parties", 'refresh');
    }

    function edit_songs($id) {
        if (!parent::authenticate())
            return;

        $party = Party::find($id);
        $songs = Song::find('all', array('conditions' => 'organizer_id = ' . parent::current_user()->organizer->id));
        $party_songs = PartySong::find('all', array('conditions' => 'party_id = ' . $id, 'include' => array('song')));
        $party_song_ids = array();
        foreach ($party_songs as $party_song) {
            array_push($party_song_ids, $party_song->song_id);
        }

        $this->data['party'] = $party;
        $this->data['songs'] = $songs;
        $this->data['party_songs'] = $party_songs;
        $this->data['party_song_ids'] = $party_song_ids;

        $this->load->view('shared/header', $this->data);
        $this->load->view('parties/edit_songs', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function update_songs($id) {
        if (!parent::authenticate())
            return;

        $party = Party::find($id);
        $songs = Song::find('all', array('conditions' => 'organizer_id = ' . parent::current_user()->organizer->id));
        $party_songs = PartySong::find('all', array('conditions' => 'party_id = ' . $id, 'include' => array('song')));
        $party_song_ids = array();
        $song_ids = array();
        $submitted_song_ids = $this->input->post('song_ids');

        foreach ($party_songs as $party_song) {
            array_push($party_song_ids, $party_song->song_id);
        }
        foreach ($songs as $song) {
            array_push($song_ids, $song->id);
        }

        foreach ($songs as $song) {
            if (in_array($song->id, $submitted_song_ids) && !in_array($song->id, $party_song_ids)) {
                PartySong::create(array('party_id' => $party->id, 'song_id' => $song->id, 'title' => $song->title,
                    'artist' => $song->artist, 'album' => $song->album, 'genre' => $song->genre, 'time_str' => $song->time));
            } elseif(!in_array($song->id, $submitted_song_ids) && in_array($song->id, $party_song_ids)) {
                PartySong::find('first', array('conditions' => "party_id = " . $party->id . " and song_id = " . $song->id))->delete();
            }
        }

        redirect("partysongs/song_list/" . $party->id, 'refresh');
    }

    function email_party_link($id){
        if (!parent::authenticate())
            return;

        $party = Party::find($id);
        $email_addresses = explode(",", $this->input->post('email_addresses'));
        //print_r($email_addresses);
        
        $party->email_party_link($email_addresses);
        $this->session->set_flashdata('message', "Mail sent");

        $this->data['party'] = $party;
        redirect("parties/show/" . $id, 'refresh');
    }
}
