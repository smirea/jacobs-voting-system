<?php
  require_once 'config.php';
  require_once 'utils.php';
  
  require_once 'classes/Model.php';

  class PollModel extends Model {

	  function __construct($table_name) {
	    parent::__construct($table_name);
	  }

	  public function get_poll_type($poll_id) {
	     $array = sql_to_array(mysql_query("SELECT type FROM ".$this->table_name." WHERE id=".$poll_id.";"));
	     return $array;
	  }

	  public function get_votes($poll_id) {//get formatted bigAss json with pollType and votes
	  	$votes = sql_to_array(mysql_query("SELECT * FROM votes WHERE poll_id=".$poll_id.";"));
	  	$pollType = $this->get_poll_type();
      return $pollType + $votes;
	  }
  }

?>
