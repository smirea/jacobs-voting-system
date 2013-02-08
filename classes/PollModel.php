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

    /**
     * Returns the full description of any number of polls
     * @param  {Int Array} $poll_ids the poll_ids to retrieve. If not set, all the polls will be returned
     * @return {Array} the arrays of a map [poll_id] => poll_description
     */
    public function get_polls (array $poll_ids = null) {
      $polls = array();
      if ($poll_ids === null) {
        $query = $this->select('*');
      } else {
        $query = $this->select('*', "WHERE id IN ('".implode("', '", $poll_ids)."')");
      }
      if (!$query) {
        Output::error(new DatabaseError($this));
        return false;
      }
      while ($row = mysql_fetch_assoc($query)) {
        $options_query = $this->option_model->select('*', "WHERE poll_id='".$row['id']."'");
        if (!$options_query) {
          Output::error(new DatabaseError($query));
          return false;
        }
        $row['options'] = array();
        while ($option = mysql_fetch_assoc($options_query)) {
          $row['options'][$option['id']] = $option;
        }
        $polls[$row['id']] = $row;
      }
      return $polls;
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
