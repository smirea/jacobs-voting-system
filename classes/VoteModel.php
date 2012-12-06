<?
  require_once 'config.php';
  require_once 'utils.php';

  require_once 'classes/Model.php';


  class VoteModel {

  	public $table_name;

    /**
     * Constructor for the Model
     * @param {String} $table_name the name of the table
     */
  	function __construct($table_name) {
  		$this->table_name = $table_name;
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
  }
?>