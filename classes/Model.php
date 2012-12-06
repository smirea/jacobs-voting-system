<?php

  class Model {
    protected $table_name;

    function __construct ($table_name) {
      $this->table_name = $table_name;
    }


    public function select ($columns, $query) {
      return mysql_query("SELECT $columns FROM ".$this->table_name." $query");
    }

    public function insert ($query) {
      return mysql_query("INSERT INTO ".$this->table_name." $query");
    }

    public function update ($query) {
      return mysql_query("UPDATE ".$this->table_name." $query");
    }

    public function delete ($query) {
      return mysql_query("DELETE FROM ".$this->table_name." $query");
    }
  }

?>