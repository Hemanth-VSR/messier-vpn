<?php

require_once 'REST.class.php';

class Wireguard extends RestAPI{

    public static $ip = null;

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
                    $re = Database::DbConnection()->query("INSERT INTO `ip_pool` (`c_id`, `public_key`, `ipaddress`) VALUES ('1', '$public_key', '$allowed_ips');");
                    return true;
                } else {
                    return $result_;
                }
            } else {
                $ip = Wireguard::$ip;
                return "Public key is already allocated to $ip!";
            }
            
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public static function public_key_not_alloc($public_key){
        try{
            $re = Database::DbConnection()->query("SELECT COUNT(*) as count, `ipaddress` FROM `ip_pool` WHERE `public_key` = '$public_key' GROUP BY `ipaddress`;");
            $row = $re->fetch_assoc();
            if ($row['count'] >= 1){
                Wireguard::$ip = $row['ipaddress'];
                return true;
            } else {
                return false;
            }
        } catch (Exception $e){
            return RestAPI::response_(array($e->getMessage()), 400);
        }
        
    }

    public static function removePeers($public_key){
        $result_ = shell_exec("sudo wg set wg0 peer $public_key remove 2>&1 && echo $?");
        if ($result_ == 0){
            $re = Database::DbConnection()->query("UPDATE `ip_pool` set `public_key` = '0', `ipaddress` = '0' WHERE `public_key` = '$public_key';");
            $isAffected = mysqli_affected_rows(Database::DbConnection());
            if ($isAffected == 1){
                return true;
            } else {
                return "Public key '$public_key' already affected or removed!";
            }
        }
    }
}