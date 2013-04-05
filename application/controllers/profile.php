<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Crowdrock_Controller {

    public function __construct() {
        parent::__construct();
        log_message('error', 'test message');
    }

    function edit() {
        $this->data['title'] = "Edit Profile";
        $user = $this->ion_auth->user()->row();
        $phone = explode('-', $user->phone);

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['first_name'] = array('name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $user->first_name,
        );
        $this->data['last_name'] = array('name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $user->last_name,
        );
        $this->data['email'] = array('name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $user->email,
        );
        $this->data['company'] = array('name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $user->company,
        );
        $this->data['phone1'] = array('name' => 'phone1',
            'id' => 'phone1',
            'type' => 'text',
            'value' => $phone[0],
        );
        $this->data['phone2'] = array('name' => 'phone2',
            'id' => 'phone2',
            'type' => 'text',
            'value' => $phone[1],
        );
        $this->data['phone3'] = array('name' => 'phone3',
            'id' => 'phone3',
            'type' => 'text',
            'value' => $phone[2],
        );

        $this->load->view('profile/edit', $this->data);
    }

    function update() {
        $this->data['title'] = "Edit Profile";
        $user = User::find($this->ion_auth->user()->row()->id);

        //validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array('first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
            );
        }
        if ($this->form_validation->run() == true && $user->update_attributes($additional_data)) {
            $this->session->set_flashdata('message', "Profile Updated");
            redirect("profile/show/" . $user->id, 'refresh');
            return;
        } else { //display the edit profile form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array('name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array('name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array('name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone1'] = array('name' => 'phone1',
                'id' => 'phone1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone1'),
            );
            $this->data['phone2'] = array('name' => 'phone2',
                'id' => 'phone2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone2'),
            );
            $this->data['phone3'] = array('name' => 'phone3',
                'id' => 'phone3',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone3'),
            );

            $this->load->view('profile/edit', $this->data);
        }
    }

    function show($user_id) {
        $user = User::find($user_id);
        $this->data["user"] = $user;

        $this->load->view('profile/show', $this->data);
    }

    function mine() {
        if (!parent::authenticate())
            return;

        $this->viewer_profile($this->ion_auth->user()->row()->id);
    }

    function viewer_profile($user_id) {
        $user = User::find($user_id);
        $this->data["user"] = $user;
        $this->data['total_points'] = $user->total_points;
        $this->data['up_vote_count'] = $user->total_up_votes();
        $this->data['down_vote_count'] = $user->total_down_votes();
        $this->data['vote_count'] = $user->total_votes();
        $this->data['earned_points'] = Point::find('all', array('conditions' => array('user_id = ?', $user->id), 'order' => 'created_at desc'));
        $this->data['top_ranked_votes'] = ParticipantRequest::find('all', array('conditions' => array('participant_requests.user_id = ?', $user->id),
                    'joins' => "inner join party_songs on party_songs.id = participant_requests.party_song_id",
                    'order' => 'party_songs.rank desc', 'limit' => 3, 'offset' => 0));
        $this->data['most_recent_votes'] = ParticipantRequest::find('all', array('conditions' => array('participant_requests.user_id = ?', $user->id),
                    'order' => 'participant_requests.created_at desc', 'limit' => 3, 'offset' => 0));

        $this->load->view('profile/viewer_profile', $this->data);
    }

//    function leaderboard($party_id){
//        $party = Party::find($party_id);
//        $leader_info = $party->leader_info();
//
//        $user = User::find($leader_info['user_id']);
//        $this->data["user"] = $user;
//        $this->data["party"] = $party;
//        $this->data['total_party_points'] = $leader_info['total_party_points'];
//        $this->data['up_vote_count'] = $leader_info['up_vote_count'];
//        $this->data['down_vote_count'] = $leader_info['down_vote_count'];
//        $this->data['vote_count'] = $leader_info['total_votes'];
//        $this->data['total_earned_points'] = $leader_info['total_earned_points'];
//
//        $this->load->view('profile/leader_board', $this->data);
//    }

    function leaderboard($party_id) {
        $party = Party::find($party_id);
        $leader_points = $party->leader_points();
        $this->data["leader_points"] = $leader_points;
        $this->data["party"] = $party;
        print_r($leader_points);

        $this->load->view('profile/leader_board', $this->data);
    }

    function destroy($user_id) {
        $user = User::find_by_id($user_id);

        if (!$this->ion_auth->is_admin()) {
            redirect('/parties/upcoming');
            return;
        }

        if ($this->session->userdata('user_id') == $user_id) {
            redirect('/auth/index');
            return;
        }

        $user->delete();

        redirect('/auth/index');
    }

}
