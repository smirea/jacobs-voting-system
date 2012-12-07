<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'classes/Model.php';
  require_once 'classes/VoteModel.php';

  $model = new Model('Elections');

  v_export(sql_to_array($model->select('*', "where label='A'")));

?>