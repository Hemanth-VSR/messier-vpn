<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/api/_libs/_includes/REST.class.php';

class Wireguard extends RestAPI{

    public static function getPeers($public_key){
        $result_ = shell_exec("sudo wg | grep -A4 $public_key | sed '/^$/,/^$/d'");
        if (isset($result_) and !is_null($result_)){
            return $result_;
        } else {
            RestAPI::response_(array("Message" => "Public key invalid!"), 400);
            exit();
        }
    }

    public static function addPeers(){
        $result_ = shell_exec("wg set wg0 peer $public_key allowed-ips $allowed_ips");
    }
}