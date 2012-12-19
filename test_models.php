<?php

  require_once 'config.php';
  require_once 'utils.php';
  require_once 'classes/Model.php';
  require_once 'classes/VoteModel.php';

  $model = new Model('Elections');
  class test extends PollModel{
    public function __construct($table_name,$type, $title, $subtitle, $num_values, $max_file, $open_time, $close_time) {
      parent::__construct($table_name);
      $t = $this->create_poll($type, $title, $subtitle, $num_values, $max_file, $open_time, $close_time);
      v_export($t);
      if(!$t) {
        echo mysql_error();
        return ;
      }
      //v_export(sql_to_array($t));
    }
  }
  $dude = new test("poll","Binary","Titlu","Subtitlu","1","2","3","5");
  // v_export(sql_to_array($model->select('*', "where label='A'")));
?>