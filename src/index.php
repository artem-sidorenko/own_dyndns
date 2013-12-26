<?php
# Custom DDNS updates without any dyndns providers on the own infrastructure
# PHP 5.5 required due to password_verify() function
# Licensed under BSD License: http://www.opensource.org/licenses/BSD-2-Clause
# Author: Artem Sidorenko http://2realities.com

#include settings
include 'conf/config.inc.php';

#get our input vars
@$ipv4=$_GET["ipaddr"];
@$ipv6=$_GET["ip6addr"];
@$username=$_GET["username"];
@$domain=$_GET["domain"];
@$password=$_GET["pass"];

#check auth
if( (!isset($users[$username])) || !password_verify($password,$users[$username]["password"]) || (!in_array($domain,$users[$username]["zones"])) ){
	die("authorisation failed");
}else{
	#auth if ok, proceed with update
	$zone=substr($domain,strpos($domain,"."));
	#some basics
	$commands="server $dns_server\\nupdate delete $domain\\n";
	#do we have ipv4?
	if(!empty($ipv4))
		$commands.="update add $domain $dns_ttl A $ipv4\\n";
	#do we have ipv6?
	if(!empty($ipv6))
		$commands.="update add $domain $dns_ttl AAAA $ipv6\\n";
	#ready to run the update
	$commands.="send\\n";
	$output=array(); $returnvar=0;
	exec('echo -e "'.$commands.'" | nsupdate -k '.$dns_key_file,$output,$returnvar);
	if($returnvar==0){
		if(!empty($ipv4))
			echo "good $ipv4\n";
		if(!empty($ipv6))
			echo "good $ipv6\n";
	}
	else
		die('update failed');
}

?>
