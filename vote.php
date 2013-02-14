<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'classes/VoteModel.php';
  require_once 'classes/OptionModel.php';

  $voteModel = new VoteModel('votes');
  $optionModel = new OptionModel('options');

  e_assert_isset($_GET, 'id,options');

  $pid =  $_GET["id"];
  $options = $_GET["options"];
  $uid = rand(); //$session['user_id'];

  if (!$voteModel->vote_is_valid($uid,$pid,$options)) {
    output_error("Vote invalid");
  }

  $voteModel->add_vote($pid,$uid);

  foreach ($options as $op) {
    echo "Voted for " . $op . "\n";
    $optionModel->increment_value($pid,$op);
  }


?>
