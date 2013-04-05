<?php

class Organizer extends ActiveRecord\Model {
    static $belongs_to = array(array('user'));
    static $has_many = array(array('playlists'), array('parties', 'class_name' => 'Party'));
    static $after_destroy = array('delete_all_related_data');

    function delete_all_related_data(){
        $parties = Party::find('all', array('conditions' => array('organizer_id' => $this->id)));
        foreach ($parties as $party) {
            $party->delete();
        }

        $songs = Song::find('all', array('conditions' => array('organizer_id' => $this->id)));
        foreach ($songs as $song) {
            $song->delete();
        }
    }
}

?>
