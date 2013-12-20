<?php
# Custom DDNS updates without any dyndns providers on the own infrastructure
# Licensed under BSD License: http://www.opensource.org/licenses/BSD-2-Clause
# Author: Artem Sidorenko http://2realities.com

#include settings
include 'conf/config.inc.php';

#get our input vars
$ipv4=$_GET=["ipaddr"];
$ipv6=$_GET=["ip6addr"];
$userame=$_GET["username"];
$domain=$_GET["domain"];
$password=$_GET["pass"];

#check auth
if( (!isset($users[$username])) || ($password!=$users[$username]["password"]) || (!in_array($users[$username]["zones"])) ){
	die("authorisation failed");
}else{
	#auth if ok, proceed with update
	$zone=substr($domain,strpos($domain,"."));
	$commands="server $dns_server\\nzone $zone\\nupdate delete $domain\\nupdate add $domain $dns_ttl $ip\\nsend\\n";
	$output=array(); $returnvar=0;
	exec('echo -e "'.$commands.'" | nsupdate -k '.$dns_key_file,$output,$returnvar);
	if($returnvar==0)
		echo "good $ip";
}

?>
