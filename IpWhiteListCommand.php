<?php
class IpWhiteListCommand
{
    public function run($args){
        $result = $this->inWhiteList($args[0]);
        var_dump($result);die;
    }
    public function inWhiteList($mid){
        $ips= ['172.20.2.1/30'];//验证ip的地址及子网掩码
        $isIn = false;
        $realIp = '172.20.2.254';//需要验证的ip
        
        if ($ips && in_array($realIp, $ips)){echo 2;
            $isIn = true;
        }
        if(!$isIn){
            $rangeIps = array_filter($ips, function($v){return strpos($v, '/') !== false;});
            foreach ($rangeIps as $rangeIp) {
                if($this->ipInRange($realIp, $rangeIp)){
                    $isIn = true;
                    break;
                }
            }
        }
    
        if (!$isIn) {
            $trueIp = '192.168.0.254';
        
            if ($ips && $trueIp && in_array($trueIp, $ips)) {
                $isIn = true;
            }
        }
        return $isIn;
    }
    public function ipInRange( $ip, $range ) {
        if ( strpos( $range, '/' ) === false ) {
            $range .= '/32';
        }
        // $range is in IP/CIDR format eg 127.0.0.1/24
        list( $range, $netmask ) = explode( '/', $range, 2 );
        $range_decimal = ip2long( $range );
        $ip_decimal = ip2long( $ip );
        $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
    }
}
