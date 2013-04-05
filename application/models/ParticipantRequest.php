<?php

class ParticipantRequest extends ActiveRecord\Model {

    static $table_name = 'participant_requests';
    #   static $belongs_to = array(array('party_song', 'class_name' => 'PartySong', 'foreign_key' => 'party_song_id'));
    static $belongs_to = array(array('party_song', 'class_name' => 'PartySong', 'foreign_key' => 'party_song_id'), array('user'), array('party'));
    static $has_many = array(array('points'));
    static $TYPE_UP_VOTE = 'up_vote';
    static $TYPE_DOWN_VOTE = 'down_vote';
    static $TYPE_PLAY_REQUEST = 'play_request';
    static $before_create = array('validate_user_uniqueness');
    static $after_create = array('update_party_song_cache', 'add_user_point', 'update_ranking');

    public function validate_user_uniqueness() {
        if (User::find($this->user_id)->is_anonymous()) {
            return true;
        }

        if (ParticipantRequest::count(array('conditions' => array('party_song_id = ? and user_id = ?', $this->party_song_id, $this->user_id))) > 0) {
            $this->errors->add('user_id', "You can vote only once in a song");
            return false;
        }
        return true;
    }

    public function update_party_song_cache() {
        if ($this->request_type == ParticipantRequest::$TYPE_UP_VOTE)
            $this->party_song->update_attribute('up_vote_count', $this->party_song->up_vote_count + 1);
        if ($this->request_type == ParticipantRequest::$TYPE_DOWN_VOTE)
            $this->party_song->update_attribute('down_vote_count', $this->party_song->down_vote_count + 1);
        if ($this->request_type == ParticipantRequest::$TYPE_PLAY_REQUEST)
            $this->party_song->update_attribute('play_request_count', $this->party_song->play_request_count + 1);
    }

    public function add_user_point() {
        if ($this->request_type == ParticipantRequest::$TYPE_UP_VOTE)
            Point::create(array('user_id' => $this->user_id, 'participant_request_id' => $this->id,
                        'party_id' => $this->party_id, 'point' => 1, 'reason' => Point::$TYPE_UP_VOTE));
        if ($this->request_type == ParticipantRequest::$TYPE_DOWN_VOTE)
            Point::create(array('user_id' => $this->user_id, 'participant_request_id' => $this->id,
                        'party_id' => $this->party_id, 'point' => 1, 'reason' => Point::$TYPE_DOWN_VOTE));
    }

    public function friendly_vote_title() {
        if ($this->request_type == ParticipantRequest::$TYPE_UP_VOTE)
            return "+1 Vote";
        elseif ($this->request_type == ParticipantRequest::$TYPE_UP_VOTE)
            return "-1 Vote";
        else
            return $this->request_type;
    }

    public function update_ranking(){
        if ($this->request_type == ParticipantRequest::$TYPE_UP_VOTE)
            $this->party->update_song_ranking();
        if ($this->request_type == ParticipantRequest::$TYPE_DOWN_VOTE)
            $this->party->update_song_ranking();
    }

}

?>