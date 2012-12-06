<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'can_vote.php';
  require_once 'classes/votes.php';
  require_once 'classes/options.php';

  $votes = new votesModel("votes");
  $option = new optionsModel("options");


  $pid =  $_GET["id"];
  $options = $_GET["options"];
  $uid = rand(); //$session['user_id'];

  if (!vote_is_valid($uid,$pid,$options)) {
    die("Vote invalid.");
  }

  $votes->add_vote($uid,$pid);

  foreach ($options as $op) {
    echo "Voted for " . $op . "\n";
    $option->incrementValue($pid,$op);
  }


?>
