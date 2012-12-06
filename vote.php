<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'can_vote.php';
  require_once 'classes/votes.php';
  require_once 'classes/options.php';

  $voteModel = new VoteModel('votes');
  $optionModel = new OptionModel('options');

  $pid =  $_GET["id"];
  $options = $_GET["options"];
  $uid = rand(); //$session['user_id'];

  if (!vote_is_valid($uid,$pid,$options)) {
    die("Vote invalid.");
  }

  $voteModel->add_vote($uid,$pid);

  foreach ($options as $op) {
    echo "Voted for " . $op . "\n";
    $optionModel->increment_value($pid,$op);
  }


?>
