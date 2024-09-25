<?php

class Wireguard{

    public static function getPeers($public_key){
        $result_ = shell_exec("sudo wg | grep -A4 $public_key | sed '/^$/,/^$/d'");
        if (isset($result_) and !is_null($result_)){
            return $result_;
        } else {
            $this->response_(array("Message" => "Public key invalid!"), 200);
            exit();
        }
    }
}