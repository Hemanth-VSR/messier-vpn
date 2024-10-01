<?php
function ipToLongRange($ip, $subnetMask) {
    $ipLong = ip2long($ip);
    $maskLong = ip2long($subnetMask);
    $network = $ipLong & $maskLong;

    $broadcast = $network | (~$maskLong & 0xFFFFFFFF);

    return [
        'network' => $network,
        'broadcast' => $broadcast
    ];
}

function longToIPRange($network, $broadcast) {
    $ips = [];
    for ($ip = $network + 1; $ip < $broadcast; $ip++) {
        $ips[] = long2ip($ip);
    }
    return $ips;
}

function findFreeIPs($subnetIP, $subnetMask, $usedIPs = []) {
    // Convert to long integers for range
    $range = ipToLongRange($subnetIP, $subnetMask);
    
    // Get list of all available IPs
    $allIPs = longToIPRange($range['network'], $range['broadcast']);
    print_r($allIPs);
    // Find free IPs
    $freeIPs = array_diff($allIPs, $usedIPs);
    
    return $freeIPs;
}

// Example usage
$subnetIP = '192.168.1.0';    // Subnet IP
$subnetMask = '255.255.255.0'; // Subnet Mask
$usedIPs = ['192.168.1.1', '192.168.1.2', '192.168.1.5']; // Sample used IPs

$freeIPs = findFreeIPs($subnetIP, $subnetMask, $usedIPs);

echo "Free IPs: \n";
// print_r($freeIPs);
?>
