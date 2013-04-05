<?php

class Song extends ActiveRecord\Model {
    static $belongs_to = array(array('playlist'));

    static $validates_presence_of = array(array('title'));
}

?>