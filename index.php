<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once DIR_CLASSES . DS . 'Error.php';
  require_once DIR_CLASSES . DS . 'Output.php';

  if (!isset($_GET['q'])) {
    output_error('No query specified');
  }

  $file = $_GET['q'];

  if (!file_exists(DIR_VIEWS . DS . $file)) {
    output_error('View does not exist');
  }

  require_once DIR_VIEWS . DS . $file;

  Output::done();

?>