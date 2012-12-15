<?php

  class Model {
    protected $table_name;

    /**
     * Model constructor
     * @param {String} $table_name the name of the table on which the model should be applied
     */
    function __construct ($table_name) {
      $this->table_name = $table_name;
    }

    /**
     * Perform a sql query
     * @param {String} $columns the columns to select
     * @param {String} $query the select query string to run
     * @return {MySQL}
     */
    protected function select ($columns, $query) {
      return mysql_query("SELECT $columns FROM ".$this->table_name." $query");
    }

    /**
     * Perform an insert query
     * @param {String} $query the insert query string to run
     * @return {Bool}
     */
    protected function insert ($query) {
      return mysql_query("INSERT INTO ".$this->table_name." $query");
    }

    /**
     * Perform an update query
     * @param {String} $query the update query string to run
     * @return {Bool}
     */
    protected function update ($query) {
      return mysql_query("UPDATE ".$this->table_name." $query");
    }

    /**
     * If you don't know what you are doing, then don't use this as well
     * @param {String} $query the delete query string to run
     * @return {Bool}
     */
    protected function delete ($query) {
      return mysql_query("DELETE FROM ".$this->table_name." $query");
    }

    /**
     * If you ever use this function, I'll strangle you with your balls
     * love Stefan ^.^
     * @return {Bool}
     */
    protected function drop () {
      return mysql_query("DROP ".$this->table_name);
    }

    /**
     * Describe the structure of the 
     * @return {MySQL}
     */
    public function describe () {
      return mysql_query("DESCRIBE ".$this->table_name);
    }

    /**
     * @return {String}
     */
    public function get_table_name () {
      return $this->table_name;
    }
  }

?>