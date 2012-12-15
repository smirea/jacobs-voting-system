<?php

require_once 'classes/Model.php';

class Error {

  protected $fatal;
  protected $message;

  function __construct ($message, $fatal) {
    $this->message = $message;
    $this->fatal = $fatal;
  }

}

class DatabaseError extends Error {

    protected $query;
    protected $mysql;
    protected $model;

    function __construct ($model, $message) {
      parent::__construct($message, 1);
      $this->model = $model;
    }

    function output() {
      //return JSON
      //$model->query_queue[], mysql_error(),$message, 1
    }

  }

function database_error($message) {
  $error = new DatabaseError($message);
  $error->output();
}

// if (!result) database_error(bsdgbdfsh, query, result); 

?>