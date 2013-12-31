own_dyndns
==========
php script to allow dyndns updates via http.

This script can be used with AVM FritzBox (and probably some other SOHO routers).

Features
========
 - dns updates of dynamic zones via nsupdate
 - support of different users and zones
 - support of IPv4 and IPv6
 - accepts following GET input vars
     - ipaddr - IPv4 address
     - ip6addr - IPv6 address
     - username - username for authentification
     - pass - password for authentification
     - domain - dns name to update to

Requirements
============
 - Webserver with PHP 5.5 due to password_verify() function
 - Own DNS server like Bind
 - nsupdate and dnssec-keygen commands from bind package(s)
 - mkpasswd from whois package

TODOs, known limitations
========================
 - missing validation of IP addresses and dns names, it might result into broken updates and error exit codes of nsupdate

How to use and install this script
==================================
 - Upload the content of src folder to your web server directory
 - create the config.inc.php in the conf subdirectory, see config_default.inc.php for avaliable options
 - use "mkpasswd -m sha-512 -S $(pwgen 10 1)" to create the password hashes

How to configure bind
=====================
First of all we need to configure dns server to allow dynamic updates to the zone, here we use bind for this.

    #create the keys in the conf folder
    cd conf
    dnssec-keygen -a HMAC-MD5 -b 512 -n USER dns.example.com
    #rename just to get better names
    mv *.private dyndns.private
    mv *.key dyndns.key
    #set the according permissions in the conf folder
    chown root:apache *
    chmod 640 *
    chown root:apache .
    chmod 750 .
    
    #put the key from dyndns.key to the /etc/bind/keys.conf
    echo << EOF > /etc/bind/keys.conf
    key yourhostkey {
    algorithm HMAC-MD5;
    secret "<<your public key here>>";
    };
    EOF
    
    #set the correct permissions
    chown root:named /etc/bind/keys.conf
    chmod 640 /etc/bind/keys.conf
    
    #include it in named.conf
    echo 'include "/etc/bind/keys.conf"' >> /etc/bind/named.conf
    
Update the zone configuration in the named.conf with following options

    #allow all zone updates
    allow-update {
        key yourhostkey;
    };
    
    #allow update only of one specific entry
    #update-policy {
    #   grant   yourhostkey name yourhost. A;
    #};

    #if you use allow-query statements to block some queries, don't forget to add allow-query here. Otherwise named will block update queries.
    #allow-query {
    #    key yourhostkey;
    #};
    
How to use it with AVM FritzBox
===============================
  - Use the custom DDNS provider and use URL like this (remove the spaces, they are due to MD formatting there)

    dns.example.com/?ipaddr=< ipaddr >&ip6addr=< ip6addr >&username=< username >&pass=< pass >&domain=< domain >

  - it looks like https dns update isn't supported by FritzBoxes