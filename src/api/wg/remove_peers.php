<?php

include "../_libs/autoload.php";

class remove_peers extends RestAPI{

    public function __construct($public_key){
        $this->public_key = $public_key;
    }

    public function remove_(){
        $end___ = Wireguard::removePeers($this->public_key);
        if ($end___ === true){
            RestAPI::response_([
                "Public Key" => $this->public_key,
            ], 200);
        } else {
            RestAPI::response_(array("Message" => $end___), 400);
        }
    }

}

$re_ = new remove_peers($_POST['public_key']);
$re_->remove_();