<?php

class ThirdpartyAuthentication extends ActiveRecord\Model {

    static $belongs_to = array(
        array('user')
    );

}

?>