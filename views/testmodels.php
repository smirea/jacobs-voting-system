<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'classes/Model.php';
  require_once 'classes/VoteModel.php';

  $poll = new PollModel('poll', 'options');
  $poll->create_poll("Binary","Titlu","Subtitlu","1","2","3","5",array(1,2,3,4,5));
?>