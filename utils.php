<?php

  /**
   * @brief check if an array is associative or not
   * @param {array} $array
   * @return {bool} the result as a boolean 
   */
  function is_assoc(array $array){
    if( !is_numeric( array_shift( array_keys( $array ) ) ) ){
        return true;
    }
    return false;
  }
  
  /**
   * @brief Outputs a JSON with the proper headers from the given array
   * @warning This function terminates the execution (runs exit())
   * @param {array} $arr the given array
   */
  function json_output( array $arr ){
    if( !headers_sent() ){
      header('Cache-Control: no-cache, must-revalidate');
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
      header('Content-type:application/json');
      header('Content-attributes: application/json; charset=ISO-8859-15');
    }
    exit( json_encode( $arr ) );
  }
  
  /**
   * @brief Outputs an error JSON with the format {"error" => $message}
   * @warning This function terminates the execution (runs exit())
   * @param {string} $message the message to output
   */
  function output_error( $message ){
    json_output( array( 'error' => $message ) );
  }
  
  /**
   * @brief Check if condition is false, in which case run output_error( of_the_message )
   * @warning This function the execution (runs exit())
   * @param {bool} $bool
   * @param {string} $message what to output if the first param is false
   */
  function e_assert( $bool, $message = "Assertion failed" ){
    if( !$bool ){
      output_error( $message );
    }
  }
  
  /**
   * @brief For each key in keys, apply e_assert( isset($arr[$key]) );
   * @param {array} $arr the array to check into
   * @param {string|array} $keys either a associative array with 'keys_to_check'=>'message_to_output'
   *                              or a comma-separated string of keys (in which case a default message will be used )
   */
  function e_assert_isset( array $arr, $keys ){
    if( is_string( $keys ) ){
      $keys = array_map( 'trim', explode(',',$keys) );
    }
    if( !is_assoc( $keys ) ){
      $keys = array_flip( $keys );
      array_walk( $keys, function(&$item, $key){ $item = "$key not set!"; });
    }
    foreach( $keys as $k => $v ){
      e_assert( isset( $arr[$k] ), $v );
    }
  }
  
  function sql_to_json_output( $q ){
    if( $q ){
      json_output( sql_to_array( $q ) );
    } else {
      output_error( mysql_error() );
    }
  }
  
  /**
   * @brief Takes a mysql resource and returns a list of associative arrays 
   *          with the results (one for each row)
   * @param {MySQL} $sql the resource to use
   * @param {} $key
   * @return a list of associative arrays with the result
   */
  function sql_to_array ( $sql, $key = null ){
    if( $sql ){
      $a = array();
      while( $r = mysql_fetch_assoc( $sql ) ){
        if( $key ){
          $a[ $r[ $key ] ] = $r;
        } else {
          $a[] = $r;
        }
      }
      return $a;
    } else {
      return array();
    }
  }

?>