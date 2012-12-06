<?php

  class Model {
    protected $table_name;

    function __construct ($table_name) {
      $this->table_name = $table_name;
    }

    public sql ($query) {
      // bla logic
    }
  }

?>