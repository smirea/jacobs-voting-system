<?php
function authenticate($user, $password) {
	$ldap_host = "jacobs.jacobs-university.de";

	$ldap = ldap_connect($ldap_host);

	if($bind = @ldap_bind($ldap, $user."@jacobs.jacobs-university.de", $password)) {
			$_SESSION['user'] = $user;
			$_SESSION['access'] = true;
			return true; 
	} 
	else { 
		return false;
	}
	
}
?>