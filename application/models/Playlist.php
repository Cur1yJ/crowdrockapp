<?php

class Playlist extends ActiveRecord\Model {
    static $belongs_to = array(array('organizer'));
    static $has_many = array(array('songs'));
}

?>