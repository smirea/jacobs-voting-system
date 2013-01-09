<?php

  /**
   * Generic error classs
   */
  class Error {

    protected $message;

    /**
     * Initializes a generic error
     * @param string $message
     */
    public function __construct ($message = 'An error has occured') {
      $this->message = $message;
    }

    /**
     * Converts all the error information into an array to be used in output
     */
    public function to_object () {
      return array(
        'type' => get_class($this),
        'message' => $this->message
      );
    }

    /**
     * Short description of the error
     */
    public function __toString () {
      return $this->message;
    }

  }

  /**
   * Used to abstract Model Errors
   */
  class DatabaseError extends Error {
    protected $model;

    /**
     * Initializes a DatabaseError
     * @param {Model} $model An instance of Model on which the error occured
     * @param {String} $message A custom description of the error
     */
    public function __construct ($model, $message = 'A database error occured') {
      parent::__construct($message);
      $this->model = $model;
    }

    /**
     * @see Error::to_object()
     */
    public function to_object () {
      $object = parent::to_object();
      $object['sql'] = array();

      foreach ($this->model->queries as $key => $query) {
        $object['sql'][] = array(
          'query' => $query,
          'error' => $this->model->errors[$key]
        );
      }
      
      return $object;
    }

    /**
     * @see Error::__toString()
     */
    public function __toString () {
      $last_query = count($this->model->queries)-1;
      return $this->message . "\n" .
              "SQL: " . $this->model->queries[$last_query] . "\n" .
              "Error: " . $this->model->errors[$last_query] . "\n";
    }
  }

?>