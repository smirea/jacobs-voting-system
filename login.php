<?php
require_once("ldapCheck.php");
require_once("../config.php");
require_once("../utils.php");

var_export($_SESSION);

if(isset($_GET['out'])) {
	session_unset();
	$_SESSION = array();
	unset($_SESSION['user'],$_SESSION['access']);
	session_destroy();
} else if(isset($_POST['userLogin'])) {
 if(authenticate($_POST['userLogin'],$_POST['userPassword'])) {
		die("Location: ".$_SERVER["HTTP_REFERRER"]);
	} else {
		output_error("Login failed!");
	}
}

else if(isset($_SESSION['access']) && $_SESSION['access'] === true) {//stub
  }
  else {

?>

<form method="post" action="login.php">
	User: <input type="text" name="userLogin" /><br />
	Password: <input type="password" name="userPassword" /><br />
	<input type="submit" name="submit" value="Submit" />
</form>
 
 <?php 
}
?>