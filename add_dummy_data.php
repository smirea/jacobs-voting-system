<?php

  require_once 'config.php';
  require_once 'utils.php';

  $users = array(1000, 2000, 6969, 3000, 4000, 5069, 6000);
  $labels = explode(' ', "A B C D E F G H I J");

  foreach ($users as $id) {
    $r = rand(0, count($labels));
    for ($i=0; $i<$r; ++$i) {
      $query = "INSERT INTO Elections(user_id,label,vote)" .
                "VALUES ('$id', '${labels[$i]}', '".rand(0,5)."')";
      echo "$query <br />";
      mysql_query($query);
    }
  }

  sql_to_json_output(mysql_query(
    "SELECT * FROM Elections"
  ));

?>