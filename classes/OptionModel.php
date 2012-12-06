<?
  require_once 'config.php';
  require_once 'utils.php';

  require_once 'classes/Model.php';
  
  class OptionModel extends Model {

    public $table_name;

     function __construct($table_name) {
      parent::__construct($table_name);
    }

    
    public function add_option($poll_id, $option_name, $value) {
      return mysql_query("INSERT INTO `".$this->table_name."` (`poll_id`, `option_name`, `value`) VALUES ('".$poll_id."', '".$option_name."', '".$value."'); ");
    }

    // To get results of the vote -.D
    public function option_value($poll_id, $option_name) {

      $value = mysql_fetch_assoc(mysql_query("SELECT value FROM options WHERE poll_id='$poll_id' AND option_name='$option_name';"));

      return $value['value'];
    } 

    // To increment the vote by one -.D
    public function increment_value($poll_id, $option_name) {
      
      $newValue = $this->option_value($poll_id, $option_name) + 1;

      return mysql_query("UPDATE options SET value='$newValue' WHERE poll_id='$poll_id' AND option_name = '$option_name';");
    }

  }
?>