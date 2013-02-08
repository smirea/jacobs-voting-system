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
    /**
     * Returns the type of the poll
     * @param {Int} $poll_id is the id of the poll which type is needed
     * @return {Array} the array of the poll type
     */
    public function get_poll_type($poll_id) {
       $result = $this->select("type","WHERE id=".$poll_id.";");
       if($result === false) {
        Output::error(new DatabaseError($this));
        return null;
       }
       $array = sql_to_array($result);
       return $array;
    }

    /**
     * Returns the type of the poll
     * @param {Int} $poll_id is the id of the poll which type is needed
     * @return {Array} the array of the votes
     */
    public function get_votes($poll_id) {//get formatted bigAss json with pollType and votes      
      $result = $this->select("*","WHERE poll_id=".$poll_id.";");
      if($result === false) {
        Output::error(new DatabaseError($this));
        return null;
      }
      $votes = sql_to_array($result);
      return $votes;
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
        return null;
      }
      while ($row = mysql_fetch_assoc($query)) {
        $options_query = $this->option_model->select('*', "WHERE poll_id='".$row['id']."'");
        if (!$options_query) {
          Output::error(new DatabaseError($query));
          return null;
        }
        $row['options'] = array();
        while ($option = mysql_fetch_assoc($options_query)) {
          $row['options'][$option['id']] = $option;
        }
        $polls[$row['id']] = $row;
      }
      return $polls;
    }

    /**
     * Creates a poll with the specified data
     * @param  {String, String, String, Int, Int, Timestamp, Timestamp, Array} $poll_ids the poll_ids to retrieve. If not set, all the polls will be returned
     * @return {Bool} the arrays of a map [poll_id] => poll_description
     */
    public function create_poll($type, $title, $subtitle, $num_values, $max_value, $open_time, $close_time, array $options) {
      $result = $this->insert("(`user_id`, `type`, `num_options`, `max_value`, `title`, `subtitle`, `timestamp`, `opening_time`, `closing_time`) VALUES ('".$_SESSION['user']."', '".$type."', '".$num_values."','".$max_value."' ,'".$title."', '".$subtitle."', '".time()."', '".$open_time."', '".$close_time."');");
      if($result === true) {
        foreach($options as $key => $option) {
          $option_result = $this->option_model->add_option(mysql_insert_id(),$option);
          // var_export($this->option_model->queries);
          if($option_result === false) {
            Output::error(new DatabaseError($option_model));
            return null;
          }
        }
      }
      else return false;
      return true;
    }

  }
?>
