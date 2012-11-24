<?php
$host = "jacobs.jacobs-university.de";
$port = 389;
$base_dn = "DC=jacobs,DC=jacobs-university,DC=de";
$user_dn = "OU=active,OU=Users,OU=CampusNet,DC=jacobs,DC=jacobs-university,DC=de";

$username = mysql_escape_string($_REQUEST["username"]);
$password = mysql_escape_string($_REQUEST["password"]);

if (($username=="") || ($password==""))
    $success = false;
else {
    error_reporting(-1);
    $ldap_conn = ldap_connect($host);
    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
    ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
    $success = ldap_bind($ldap_conn,
                    $username."@jacobs.jacobs-university.de",
                    $password);
    ldap_unbind($ldap_conn);
}

error_reporting(0);

echo "success";

session_destroy();

?>
