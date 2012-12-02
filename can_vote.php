<?php

  header("content-type: text/plain");

  require_once 'config.php';
  require_once 'utils.php';

  function has_voted($user_id, $poll_id){
  	$is_in_there = sql_to_array(mysql_query("SELECT * FROM votes 
  		WHERE user_id = '$user_id' AND poll_id = '$poll_id'"));

  	if(sizeof($is_in_there)>0){
		return true;
	}
	return false;
  }

  function vote_in_time($poll_id){

  $ot = mysql_query("SELECT opening_time FROM poll WHERE id='$poll_id'");
  
  if (!$ot) {
    die(mysql_error());
  }

  $ot = mysql_fetch_assoc($ot);

  $ct = mysql_query("SELECT closing_time FROM poll WHERE id='$poll_id'");
  
  if (!$ct) {
    die(mysql_error());
  }

  $ct = mysql_fetch_assoc($ct);
  $now=time();

  if ($now<$ot['opening_time']||$now>$ct['closing_time']){
    return false;
  }
  return true;

  }

  function voted_according_to_poll($votes, $poll_id){
  	return true;
  }

  function vote_is_valid($user_id, $poll_id, $votes){
  	if(has_voted($user_id, $poll_id)){
  		return false;
    }
	/*if(!voted_according_to_poll($votes, $poll_id)){
		return false;
	}*/
	if(!vote_in_time($poll_id)){
		return false;
	}
	return true;
  }
?>