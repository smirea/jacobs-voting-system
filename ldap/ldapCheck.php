<?php
function authenticate($user, $password) {
	$ldap_host = "jacobs.jacobs-university.de";

	//$ldap_dn = "DC=jacobs,DC=jacobs-university,DC=de";

	//$ldap_user_group = "WebUsers";

	//$ldap_manager_group = "WebManagers";

	//$ldap_usr_dom = "DC=jacobs,DC=jacobs-university,DC=de";

	$ldap = ldap_connect($ldap_host);

	if($bind = @ldap_bind($ldap, $user."@jacobs.jacobs-university.de", $password)) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true; } 
	else return false;
	
}
?>