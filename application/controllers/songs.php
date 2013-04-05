<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends Crowdrock_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['message'] = "";
        parent::authenticate();
    }

    //redirect if needed, otherwise display the user list
    function index() {
        $this->data['songs'] = Song::all(array('conditions' => 'organizer_id = ' . User::find($this->ion_auth->user()->row()->id)->organizer->id));

        $this->load->view('shared/header', $this->data);
        $this->load->view('songs/index', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function new_song() {
        $this->data['song'] = new Song();

        $this->load->view('shared/header', $this->data);
        $this->load->view('songs/new', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function create() {
        $song = new Song(array('title' => $this->input->post('title'), 'artist' => $this->input->post('artist'),
            'album' => $this->input->post('album'), 'genre' => $this->input->post('genre'), 'time' => $this->input->post('time'),
                    'organizer_id' => User::find($this->ion_auth->user()->row()->id)->organizer->id));

        if ($song->is_valid() && $song->save()) {
            redirect("songs", 'refresh');
        } else {
            $this->data['song'] = $song;
            $this->load->view('shared/header', $this->data);
            $this->load->view('songs/new', $this->data);
            $this->load->view('shared/footer', $this->data);
        }
    }

    function edit($id) {
        $this->data['song'] = Song::find($id);

        $this->load->view('shared/header', $this->data);
        $this->load->view('songs/edit', $this->data);
        $this->load->view('shared/footer', $this->data);
    }

    function update($id) {
        $song = Song::find($id); //new Song(array('title' => $this->input->post('title'), 'organizer_id' => $this->ion_auth->user()->row()->id));
        $song_attributes = array("title" => $this->input->post('title'), 'artist' => $this->input->post('artist'),
            'album' => $this->input->post('album'), 'genre' => $this->input->post('genre'), 'time' => $this->input->post('time'));

        if ($song->is_valid() && $song->update_attributes($song_attributes)) {
            redirect("songs", 'refresh');
        } else {
            $this->data['song'] = $song;
            $this->data['message'] = "Failed to update song.";
            $this->data['error_messages'] = $song->errors->full_messages();
            $this->load->view('shared/header', $this->data);
            $this->load->view('songs/edit', $this->data);
            $this->load->view('shared/footer', $this->data);
        }
    }

    function delete($id) {
        $song = Song::find($id);
        $song->delete();
        redirect("songs", 'refresh');
    }

}
