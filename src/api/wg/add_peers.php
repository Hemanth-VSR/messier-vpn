<?php

include "../_libs/autoload.php";

class add_peers extends RestAPI{

    use gettersetter;

    public function __construct($public_key){
        $this->db = Database::DbConnection();
        $this->public_key = $public_key;
    }

    public function valid_(){
        $nextIP = $this->getNextIP();
        $end__ = Wireguard::addPeers($this->public_key, $nextIP);
        if ($end__ === true){
            RestAPI::response_([
                "Public_key" => $this->public_key,
                "IPAllocated" => $nextIP
            ], 200);
        } else {
            RestAPI::response_(array("Message" => $end__), 400);
        } 
    }

    public function getAllocatedIPAddress(){
        print_r($this->db);
        $ipadd = array();
        $database = $this->db->query("SELECT `ipaddress` FROM `ip_pool`");
        if ($database->num_rows > 0){
            while ($result = $database->fetch_assoc()){
                $ipadd[] = $result['ipaddress'];
            }
            return $ipadd;
        } else {
            return $ipadd;
        }
        
    }

    public function ip2long_array($ipArray) {
        return array_map('ip2long', $ipArray);
    }

    public function long2ip_array($longArray) {
        return array_map('long2ip', $longArray);
    }

    public function getNextIP(){
        $existingIPs = $this->getAllocatedIPAddress();
        $subnet = '172.20.0.1/16'; 
        $ipRange = explode('/', $subnet);   
        $startIP = ip2long($ipRange[0]); 
        $mask = 32 - (int)$ipRange[1];   
        $endIP = ($startIP | ((1 << $mask) - 1)) - 1;
        $existingIPLongs = $this->ip2long_array($existingIPs);

        sort($existingIPLongs);

        $nextIP = null;

        for ($currentIP = $startIP + 1; $currentIP <= $endIP; $currentIP++) {
            if (!in_array($currentIP, $existingIPLongs)) {
                $nextIP = $currentIP;
                break;
            }
        }
        return long2ip($nextIP);
    }

}

if ($a_ = Celestial::checkIF()){
    throw new Exception($a_ ." ". "Cannot handle Celestial API's");
} else {
    $a = new add_peers($_POST['public_key']);
    $a->valid_();
}
