<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once DIR_CLASSES . DS . 'Output.php';
  require_once DIR_CLASSES . DS . 'Error.php';
  require_once DIR_CLASSES . DS . 'Model.php';

  Output::error(new Error('trololooo'));

  Output::error(new Error('foo'));
  Output::message('wtf');

  $model = new Model('votes');
  $model->describe();

  Output::error(new DatabaseError($model));
  Output::final_error(new Error('i am final and bar does not appear'));
  Output::error(new Error('bar'));

  Output::done();

?>