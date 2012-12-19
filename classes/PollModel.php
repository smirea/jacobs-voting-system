<?php
  require_once 'config.php';
  require_once 'utils.php';
  
  require_once 'classes/Model.php';

  class PollModel extends Model {

	  function __construct($table_name) {
	    parent::__construct($table_name);
	  }

	  public function get_poll_type($poll_id) {
	  	
	  	 $array = sql_to_array($this->select("type","WHERE id=".$poll_id.";"));
	     //$array = sql_to_array(mysql_query("SELECT type FROM ".$this->table_name." WHERE id=".$poll_id.";"));
	     return $array;
	  }

	  public function get_votes($poll_id) {//get formatted bigAss json with pollType and votes
	  	
	  	$votes = sql_to_array($this->select("*","WHERE poll_id=".$poll_id.";"));
	  	//$votes = sql_to_array(mysql_query("SELECT * FROM votes WHERE poll_id=".$poll_id.";"));
	  	$pollType = $this->get_poll_type();
      	return $pollType + $votes;
	  }

	  public function create_poll($type, $title, $subtitle, $num_values, $max_file, $open_time, $close_time) {

	  	return $this->insert("(`user_id`, `type`, `num_values`, `title`, `subtitle`, `timestamp`, `opening_time`, `closing_time`) VALUES ('".$_SESSION['user']."', '".$type."', '".$num_values."', '".$title."', '".$subtitle."', '".time()."', '".$opening_time."', '".$closing_time."');");
	  }
  }
?>
