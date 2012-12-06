<?php

  require_once 'config.php';
  require_once 'utils.php';

  require_once 'classes/Model.php';
  require_once 'classes/PollModel.php';


  class VoteModel {

    public $table_name;
    private $poll_model;

    /**
     * Constructor for the Model
     * @param {String} $table_name the name of the table
     */
    function __construct($table_name) {
      $this->table_name = $table_name;
      $this->poll_model = new PollModel('$poll');
    }

    /**
     * Inserts a vote into the table
     * @param {Int} $poll_id the id of the poll
     * @param {Int} $user_id the id of the user
     * @return {Bool}
     */
    public function add_vote($poll_id, $user_id) {
      return mysql_query("INSERT INTO `".$this->table_name."` (user_id, poll_id) VALUES ('$user_id','$poll_id');");
    }

    protected function has_voted($user_id, $poll_id){
      $is_in_there = $this->poll_model->has_user_voted($user_id,$poll_id);
      /*sql_to_array(mysql_query("SELECT * FROM votes 
        WHERE user_id = '$user_id' AND poll_id = '$poll_id'"));*/

      if(sizeof($is_in_there)>0){
        return true;
      }
      return false;
    }

    protected function vote_in_time($poll_id){

      $start_time = $this->poll_model->opening_time($poll_id);
      
      if (!$start_time) {
        die(mysql_error());
      }

      $start_time = mysql_fetch_assoc($start_time);

      $end_time = $this->poll_model->closing_time($poll_id);
      
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

    protected function vote_is_valid($user_id, $poll_id, $votes){
      if(has_voted($user_id, $poll_id)){
        return false;
      }
      if(!vote_in_time($poll_id)){
        return false;
      }
      return true;
    }
  }
?>