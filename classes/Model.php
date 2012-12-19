<?php

  class Model {
    protected $table_name;
    public $queries = array();
    public $errors = array();

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
      return $this->query("SELECT $columns FROM ".$this->table_name." $query");
    }

    /**
     * Perform an insert query
     * @param {String} $query the insert query string to run
     * @return {Bool}
     */
    protected function insert ($query) {
      return $this->query("INSERT INTO ".$this->table_name." $query");
    }

    /**
     * Perform an update query
     * @param {String} $query the update query string to run
     * @return {Bool}
     */
    protected function update ($query) {
      return $this->query("UPDATE ".$this->table_name." $query");
    }

    /**
     * If you don't know what you are doing, then don't use this as well
     * @param {String} $query the delete query string to run
     * @return {Bool}
     */
    protected function delete ($query) {
      return $this->query("DELETE FROM ".$this->table_name." $query");
    }

    /**
     * If you ever use this function, I'll strangle you with your balls
     * love Stefan ^.^
     * @return {Bool}
     */
    protected function drop () {
      return $this->query("DROP ".$this->table_name);
    }

    /**
     * Describe the structure of the
     * @return {MySQL}
     */
    public function describe () {
      return $this->query("DESCRIBE ".$this->table_name);
    }

    /**
     * Generic wrapper for the mysql_query
     * @param {String} $query any generic query
     */
    private function query ($query) {
      $this->queries[] = $query;
      $this->errors[] = mysql_error();
      return mysql_query($query);
    }

    /**
     * @return {String}
     */
    public function get_table_name () {
      return $this->table_name;
    }

  }

?>