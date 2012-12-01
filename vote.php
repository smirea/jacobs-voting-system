<?php

  require_once 'config.php';
  require_once 'utils.php';
  
  $pid =  $_GET["id"];
  $options = $_GET["options"];
  $uid = rand();

$query = "INSERT INTO votes (user_id, poll_id) VALUES('$uid','$pid')";
  if (!mysql_query($query)) {
    echo mysql_error();
  }

foreach ($options as $op) {

  $query = "SELECT value FROM options WHERE poll_id='$pid'"
  ." AND option_name='$op'";

  $value = mysql_query($query);

  if (!$value) {
    echo mysql_error();
  }

$value = mysql_fetch_assoc($value);

$result = $value['value'] + 1;

echo "Setting vote for " . $op . " as ". $result;

  $query = "UPDATE options SET value='$result' WHERE poll_id='$pid'"
  ." AND option_name = '$op'";

  if (!mysql_query($query)) {
    echo mysql_error();
  }

}

?>
