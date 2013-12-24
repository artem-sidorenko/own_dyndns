<?php

#location of dns keyfile generated by dnssec-keygen
#the *.key file should be in the same dir.
$dns_key_file="conf/dyndns.private";

#DNS TTL in seconds
$dns_ttl=60;

#DNS server to update to
$dns_server="localhost";

#hash with users, passwords and allowed domains
#structure like this
/*$users=[ 
	#username
	"test" => [
			#password hash
			"password"=> '$6$ghshakldwqddr$1K3nY6KTZ6CU2a63YSI7ZQ2lkLhOTB407YkJBEFjajvhtGrq14QavlUTzUWjlzv9VpnLbzMx1h3e2d/gxjv17.',
			#array with allowed domains
			"zones"=> ["test.example.com"] 
	],
];*/
$users=[];

@include "conf/config.inc.php";
?>