<?
  require_once 'config.php';
  require_once 'utils.php';

  class optionsModel {

  	public $table_name;

  	function __construct($table_name) {
  		$this->table_name = $table_name;
  	}

  	public function add_option($poll_id, $option_name, $value) {
  		return mysql_query("INSERT INTO `".$this->table_name."` (`poll_id`, `option_name`, `value`) VALUES ('".$poll_id."', '".$option_name."', '".$value."'); ");
  	}
  }

  $option = new optionsModel("options");
?>