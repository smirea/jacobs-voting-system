<?php

  require_once 'classes/PollModel.php';

  $p = new PollModel('poll', 'options');

  Output::message($p->get_polls());


?>