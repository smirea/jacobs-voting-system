<?php
  header("content-type: text/plain");

  require_once 'config.php';
  require_once 'utils.php';

  function has_voted($user_id, $poll_id){
  	$is_in_there = sql_to_array(mysql_query("SELECT * FROM votes 
  		WHERE user_id = $user_id AND poll_id = $poll_id"));
  	if(sizeof($is_in_there)){
		return false;
	}
	return true;
  }

  function vote_in_time($poll_id){
  	$opening_time = mysql_query("SELECT opening_time FROM poll 
  		WHERE id = $poll_id");
  	if(!$opening_time){
  		die('Invalid query: '.mysql_error());
  	}

  	$opening_time=sql_to_array($opening_time);

  	$closing_time = mysql_query("SELECT closing_time FROM poll 
  		WHERE id = $poll_id");

  	if(!$closing_time){
  		die('Invalid query: '.mysql_error());
  	}

  	$closing_time = sql_to_array($closing_time);

  	if($poll_id<$opening_time[0]||$poll_id>$closing_time[0]){
  		return false;
  	}
  	return true;
  }

  function voted_according_to_poll($votes, $poll_id){
  	
  }

  function vote_is_valid($user_id, $poll_id, $votes){
  	if(has_voted($user_id, $poll_id)){
  		return false;
    }
	if(!voted_according_to_poll($votes, $poll_id)){
		return false;
	}
	if(!vote_in_time($poll_id)){
		return false;
	}
	return true;
  }
?>