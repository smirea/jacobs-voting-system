<?php

  require_once 'utils.php';

  class Output {
    private static $printed = false;
    public static $errors = array();
    public static $messages = array();

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

    public static function get_errors () {
      $result = array();
      foreach (Output::$errors as $error) {
        $result[] = $error->to_object();
      }
      return $result;
    }

    /**
     * Terminates the script and outputs all the queued messages.
     * @return function [description]
     */
    public static function done () {
      if (Output::$printed) {
        return;
      }
      Output::$printed = true;

      if (count(Output::$errors) > 0) {
        output_error(Output::get_errors());
      } else {
        json_output(Output::$messages);
      }
      exit();
    }

    /**
     * Takes care of special final_* functions
     * @param  {String} $name
     * @param  {Array} $arguments
     */
    public static function __callStatic ($name, $arguments) {
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