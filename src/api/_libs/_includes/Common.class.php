<?php

trait gettersetter{
    public static function __callStatic($function, $arguments){
        $getter = lcfirst(substr($function, 3));
        $args1 = $arguments[0];
        try{
            $result = Database::DbConnection()->query("SELECT `$getter` FROM `ip_pool`");
            return $result->fetch_assoc();
        } catch (Exception $theLine){
            return $theLine->getMessage();
        }

    }
}