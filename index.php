<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once DIR_CLASSES . DS . 'Error.php';
  require_once DIR_CLASSES . DS . 'Output.php';

  if (!isset($_GET['q'])) {
    Output::error(new Error('No query specified'));
    OUtput::done();
  }

  $file = $_GET['q'];

  if (!file_exists(DIR_VIEWS . DS . $file)) {
    Output::error(new Error('View does not exist'));
    Output::message($_GET);
    Output::done();
  }

  unset($_GET['q']);
  require_once DIR_VIEWS . DS . $file;

  Output::done();

?>