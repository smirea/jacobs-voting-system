<?php

  require_once 'config.php';
  require_once 'utils.php';

  require_once 'classes/Model.php';
  require_once 'classes/PollModel.php';


  class VoteModel extends Model{

    private $poll_model;
    /**
     * Constructor for the Model
     * @param {String} $table_name the name of the table
     */
    function __construct($table_name) {
      parent::__construct($table_name);
      $this->poll_model = new PollModel('poll');
    }

    /**
     * Inserts a vote into the table
     * @param {Int} $poll_id the id of the poll
     * @param {Int} $user_id the id of the user
     * @return {Bool}
     */
    public function add_vote($poll_id, $user_id) {

      return $this->insert("(user_id, poll_id) VALUES ('$user_id','$poll_id')");
    }

    protected function has_voted($user_id, $poll_id){

      $is_in_there = mysql_num_rows($this->select('*',"WHERE user_id = '$user_id' AND poll_id = '$poll_id'"));

      if($is_in_there>0){
        return true;
      }

      return false;
    }

    protected function vote_in_time($poll_id){

      $start_time = $this->poll_model->select('opening_time',"WHERE id='$poll_id'");

      
      if (!$start_time) {
        die(mysql_error());
      }

      $start_time = mysql_fetch_assoc($start_time);

      $end_time = $this->poll_model->select('closing_time',"WHERE id='$poll_id'");
      
      if (!$end_time) {
        die(mysql_error());
      }

      $end_time = mysql_fetch_assoc($end_time);
      $now=time();

      if ($now<$start_time['opening_time']||$now>$end_time['closing_time']){
        return false;
      }
      return true;

    }

    protected function vote_acc_to_poll($options, $poll_id){
      $poll_type=$this->poll_model->select('type', "WHERE id='$poll_id'");
      $max_value=$this->poll_model->select('max_value',"WHERE id='$poll_id'");
      $num_options=$this->poll_model->select('num_options',"WHERE id='$poll_id'");

      switch($poll_type){
        case "Binary":
          if(!check_binary($options,$max_value,$num_options))
            return false;
          return true;
        case "N-ary":
          if(!check_nary($options,$max_value,$num_options))
            return false;
          return true;
        case "Weighted":
          if(!check_weighted($options,$max_value,$num_options))
            return false;
          return true;
        case "Sorted":
          if(!check_sorted($options,$max_value,$num_options))
            return false;
          return true;
      }
      return true;
    }

    public function vote_is_valid($user_id, $poll_id, $votes){
      if($this->has_voted($user_id, $poll_id)){
        return false;
      }
      if(!$this->vote_in_time($poll_id)){
        return false;
      }
      if(!vote_acc_to_poll($options,$poll_id)){
        return false;
      }
      return true;
    }
  }
?>