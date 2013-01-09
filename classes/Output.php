<?php

  require_once 'utils.php';

  class Output {
    public static $print_messages_if_errors = true;

    public static $errors = array();
    public static $messages = array();

    private static $printed = false;

    /**
     * Queue an error for output
     * @param  {Error} $error An instance of Error
     */
    public static function error ($error) {
      Output::$errors[] = $error;
    }

    /**
     * Queue a result message
     * @param  [type] $message
     */
    public static function message ($message) {
      Output::$messages[] = $message;
    }

    /**
     * Get all the errors in array format
     * @return {Array}
     */
    public static function get_errors () {
      $result = array();
      foreach (Output::$errors as $error) {
        $result[] = $error->to_object();
      }
      return $result;
    }

    public static function get_output () {
      if (Output::$printed) {
        return;
      }
      Output::$printed = true;
      $result = array();
      if (Output::$print_messages_if_errors || count(Output::$errors) == 0) {
        $result['result'] = Output::$messages;
      }
      $result['errors'] = Output::get_errors();

      return $result;
    }

    /**
     * Terminates the script and outputs all the queued messages.
     */
    public static function done () {
      json_output(Output::get_output());
    }

    /**
     * Takes care of special final_* functions
     * @param  {String} $name
     * @param  {Array} $arguments
     */
    public static function __callStatic ($name, array $arguments) {
      $final = 'final_';
      if (strpos($name, $final) === 0) {
        $method = substr($name, strlen($final));
        if (!method_exists('Output', $method)) {
          Output::final_error("Method `$method` does not exist");
        }
        call_user_func_array(array('Output', $method), $arguments);
        Output::done();
      }
    }

  }

?>