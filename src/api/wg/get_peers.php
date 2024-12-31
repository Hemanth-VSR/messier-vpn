<?php

include "../_libs/autoload.php";

class get_peers extends RestAPI{

    public function __construct($public_key){
        $this->public_key = $public_key;
    }

    public function ValiField(){
        try{
            $peer = Wireguard::getPeers($this->public_key);
            $this->filter_bf($peer);
            
            if (isset($this->peer , $this->AllowedIPs , $this->Endpoint , $this->LatestHandshake , $this->TransferReceived , $this->TransferSent)){
                RestAPI::response_([
                    "Peer" => $this->peer,
                    "Allowed IPs" => $this->AllowedIPs,
                    "Latest Handshake" => $this->LatestHandshake,
                    "Transfer received" => $this->TransferReceived,
                    "Transfter send" => $this->TransferSent,
                    "is_connected" => $this->VPN
                ], 200);
            }
        } catch (Exception $e){
            RestAPI::response_(array("Message" => $e->getMessage()), 400);
        }
    }

    private function filter_bf($peer){
        preg_match('/peer:\s*([A-Za-z0-9\/+=]+)\s*(?:endpoint:\s*([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+:[0-9]+))?\s*(?:allowed ips:\s*([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+\/[0-9]+))?\s*(?:latest handshake:\s*([\d]+\s\w+(?:,\s[\d]+\s\w+)*\sago))?\s*(?:transfer:\s*([0-9]+(?:\.[0-9]+)?\s\w+)\s*received,\s*([0-9]+(?:\.[0-9]+)?\s\w+)\s*sent)?/', $peer, $matches);
        $this->peer = $matches[1];
        $this->Endpoint = ($matches[2] ?? 'false');
        $this->AllowedIPs = $matches[3];
        $this->LatestHandshake = ($matches[4] ?? 'false');
        $this->TransferReceived = ($matches[5] ?? 'false');
        $this->TransferSent = ($matches[6] ?? 'false'); 
        if (strlen($this->Endpoint) == 0){
            $this->Endpoint = 'false';
            $this->VPN = 'false';
        } else {
            $this->VPN = 'true';
        }
        
    }
}

$wg = new get_peers($_POST['public_key']);
$wg->ValiField();