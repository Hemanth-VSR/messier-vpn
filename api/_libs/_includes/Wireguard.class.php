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

    public static function addPeers($public_key, $allowed_ips){
        try{
            if (Wireguard::public_key_not_alloc($public_key) === false){
                $result_ = shell_exec("sudo wg set wg0 peer $public_key allowed-ips $allowed_ips/32 2>&1 && echo $?");
                if ($result_ == 0){
                    // $re = Database::DbConnection()->query("UPDATE `ip_pool` set ");
                    return true;
                } else {
                    return $result_;
                }
            } else {
                return "Public key is already exist!";
            }
            
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public static function public_key_not_alloc($public_key){
        $re = Database::DbConnection()->query("SELECT COUNT(*) as count FROM `ip_pool` WHERE `public_key` = '$public_key';");
        if ($re->fetch_assoc()['count'] == 1){
            return true;
        } else {
            return false;
        }
    }

    public static function removePeers($public_key){
        $result_ = shell_exec("sudo wg set wg0 peer $public_key remove");
    }
}