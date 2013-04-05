<?php

class Point extends ActiveRecord\Model {

    static $belongs_to = array(array('user'), array('participant_request', 'class_name' => 'ParticipantRequest', 'foreign_key' => 'participant_request_id'));
    static $TYPE_UP_VOTE = 'up_vote';
    static $TYPE_DOWN_VOTE = 'down_vote';
    static $TYPE_PLAY_REQUEST = 'play_request';
    static $TYPE_PLAY_CREDIT = 'play_credit';
    static $after_create = array('update_user_point_cache');

    public function update_user_point_cache() {
        $user = $this->user;
        if ($user != null)
            $user->update_attribute('total_points', $user->total_points + $this->point);
    }

}

?>
