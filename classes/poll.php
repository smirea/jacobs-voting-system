<?
  require_once 'config.php';
  require_once 'utils.php';
  
  class pollModel {
  
	  public $poll_id;
    public $table_name;

	  function __construct($id,$table_name) {
	    $this->poll_id = $id;
      $this->table_name = $table_name;
	  }

	  public function getPollType() {
	     $array = sql_to_array(mysql_query("SELECT type FROM ".$this->table_name." WHERE id=".$this->poll_id.";"));
	     return $array;
	  }

	  public function getVotes() {//get formatted bigAss json with pollType and votes
	  	$votes = sql_to_array(mysql_query("SELECT * FROM votes WHERE poll_id=".$this->poll_id.";"));
	  	$pollType = $this->getPollType();
	  	json_output($pollType+$votes);
	  }
  }

  $poll = new pollModel($_GET["id"],"poll");
  $poll->getVotes();

?>
