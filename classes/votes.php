<?
  require_once 'config.php';
  require_once 'utils.php';

  class votesModel {

  	public $table_name;

  	function __construct($table_name) {
  		$this->table_name = $table_name;
  	}

  	public function add_vote($poll_id, $user_id) {
  		return mysql_query("INSERT INTO `".$this->table_name."` (user_id, poll_id) VALUES ('$user_id','$poll_id');");
  	}
  }
  
?>