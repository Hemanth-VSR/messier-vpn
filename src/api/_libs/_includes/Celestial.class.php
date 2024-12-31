<?php

class Celestial{
    public static function checkIF(){
        if (php_sapi_name() == 'cli'){
            return php_sapi_name();
        } else {
            return false;
        }
    }
}
?>