<?php

class User extends ActiveRecord\Model {
    static $has_one = array(array('organizer'),array('thirdparty_authentication'));
//    static $has_many = array(array('participant_requests') );
    static $has_many = array(array('participant_requests'), array('points'));
    static $after_destroy = array('delete_all_related_data');

    static $ANONYMOUS_USERNAME = 'anonymous';

    function create_default_associations(){
        log_message('error', 'creating default association');
        $organizer = Organizer::create(array('user_id' => $this->id, 'name' => $this->username));
        $default_playlist = Playlist::create(array('organizer_id' => $organizer->id));
    }

    function total_up_votes(){
        $request_count = ParticipantRequest::find('first', array('select' => 'count(*) as vote_count', 'conditions' => array('request_type = ? and user_id = ?', ParticipantRequest::$TYPE_UP_VOTE, $this->id)));
        return $request_count->vote_count;
    }

    function total_down_votes(){
        $request_count = ParticipantRequest::find('first', array('select' => 'count(*) as vote_count', 'conditions' => array('request_type = ? and user_id = ?', ParticipantRequest::$TYPE_DOWN_VOTE, $this->id)));
        return $request_count->vote_count;
    }

    function total_votes(){
        $request_count = ParticipantRequest::find('first', array('select' => 'count(*) as vote_count', 'conditions' => array('1 = 1')));
        return $request_count->vote_count;
    }

    function is_anonymous(){
        return $this->username == User::$ANONYMOUS_USERNAME;
    }

    function delete_all_related_data(){
        $organizers = Organizer::find('all', array('conditions' => array('user_id' => $this->id)));
        foreach ($organizers as $organizer) {
            $organizer->delete();
        }

        $points = Point::find('all', array('conditions' => array('user_id' => $this->id)));
        foreach ($points as $point) {
            $point->delete();
        }
    }

    public static function anonymous(){
        return $user = User::first(array('conditions' => array("username = '" . User::$ANONYMOUS_USERNAME . "'", )));
    }

    public function display_name(){
        if($this->first_name == "0" || $this->last_name == "0"){
            return $this->email;
        }else{
           return $this->first_name . " " . $this->last_name;
        }
    }
}

?>
