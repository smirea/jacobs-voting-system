<?php
  require_once 'config.php';
  require_once 'utils.php';
  
  require_once 'classes/Model.php';
  require_once 'classes/OptionModel.php';

  class PollModel extends Model {

    private $option_model;

	  function __construct($table_name,$optionTable_name) {
	    parent::__construct($table_name,$optionTable_name);
      $this->option_model = new OptionModel($optionTable_name);
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

	  public function create_poll($type, $title, $subtitle, $num_values, $max_value, $open_time, $close_time, array $options) {
      $result = $this->insert("(`user_id`, `type`, `num_options`, `max_value`, `title`, `subtitle`, `timestamp`, `opening_time`, `closing_time`) VALUES ('".$_SESSION['user']."', '".$type."', '".$num_values."','".$max_value."' ,'".$title."', '".$subtitle."', '".time()."', '".$open_time."', '".$close_time."');");
      if($result === true) {
        foreach($options as $key => $option) {
          $option_result = $this->option_model->add_option(mysql_insert_id(),$option);
          // var_export($this->option_model->queries);
          if($option_result === false) {
            Output::error(new DatabaseError($option_model));
            return false;
          }
        }
      }
      else return false;
      return true;
	  }
    
  }
?>
