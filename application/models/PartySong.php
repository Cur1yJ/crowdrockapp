<?php

class PartySong extends ActiveRecord\Model {

    static $table_name = 'party_songs';
    static $belongs_to = array(array('party'), array('song'));
    static $has_many = array(array('participant_requests'));
    static $validates_presence_of = array(array('title'));
    static $STATE_PLAYING = 'playing';
    
    public function add_play_point_to_voters(){
        $votes = ParticipantRequest::find('all', array('conditions' => array('party_song_id = ? and request_type = ?', $this->id, ParticipantRequest::$TYPE_UP_VOTE)));
        foreach ($votes as $vote) {
            Point::create (array('user_id' => $vote->user_id, 'participant_request_id' => $vote->id,
                'party_id' => $vote->party_id, 'point' => 10, 'reason' => Point::$TYPE_PLAY_CREDIT));
        }
    }

    public function up_vote_requests(){
        return ParticipantRequest::find('all', array('conditions' => array("party_song_id = ? and request_type = ? and user_id != ?", $this->id, ParticipantRequest::$TYPE_UP_VOTE, User::anonymous()->id), 'order' => 'updated_at desc'));
    }

    public function requester_names(){
        $names = array();
        $up_requests = $this->up_vote_requests();
        foreach ($up_requests as $participant_request) {
            array_push($names, $participant_request->user->display_name());
        }
        return implode("<br/>", $names);
    }
}

?>
