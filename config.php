<?php
  define( 'DB_USER', 'jPerson' );
  define( 'DB_PASS', 'jacobsRulz' );
  define( 'DB_NAME', 'VotingSystem' );
  
  dbConnect( DB_USER, DB_PASS, DB_NAME );
  
  session_start();
  
  /**
   * @brief Perform a database connection
   * @warning Dies if it is unable to make a connection
   * @param {string} $user
   * @param {string} $pass
   * @param {string} $name
   * @param {string} $host
   */
  function dbConnect($user, $pass, $name = null, $host = 'localhost'){
    $connexion = mysql_connect( $host, $user, $pass ) or die ("Could not connect to Data Base!");
    if( $name ) mysql_select_db( $name, $connexion ) or die ("Failed to select Data Base");
  }
  
  /**
   * @brief Wraps var_export into a <pre></pre> tag for nice formatting
   * @param {mixed} [$arg_n]
   */
  function v_export(){
    $args = func_get_args();
    echo '<pre>';
    foreach( $args as $arg ){
      var_export( $arg );
      echo "\n";
    }
    echo '</pre>';
  }
?>
