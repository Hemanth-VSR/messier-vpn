<?php

require "api/_libs/autoload.php";

$wg = new Wireguard();
print_r($wg->getPeers());

?>