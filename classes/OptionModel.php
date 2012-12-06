<?
  require_once 'config.php';
  require_once 'utils.php';

  require_once 'classes/Model.php';
  
  class OptionModel extends Model {

    public $table_name;

     function __construct($table_name) {
      parent::__construct($table_name);
    }

    /**
     * Add option to the database
     * @param {Int} $poll_id is the id of the poll to which option should belong
     * @param {String} $option_name is the name of option 
     * @return {Bool} 
     */    
    public function add_option($poll_id, $option_name) {
      return mysql_query("INSERT INTO `".$this->table_name."` (`poll_id`, `option_name`, `value`) VALUES ('$poll_id', '$option_name', '0'); ");
    }

   /**
    * Returns vote result for desired option
    * @param {Int} $poll_id is the id of the poll to which option belongs
    * @param {String} $option_name is the name of the option
    * @return {Int}
    * */
    public function option_value($poll_id, $option_name) {

      $value = mysql_fetch_assoc(mysql_query("SELECT value FROM options WHERE poll_id='$poll_id' AND option_name='$option_name';"));

      return $value['value'];
    } 

    /**
     * Adds one to the option value (aka. VOTE)
    * @param {Int} $poll_id is the id of the poll to which option belongs
    * @param {String} $option_name is the name of the option
    * @return {Bool}
    * */
    public function increment_value($poll_id, $option_name) {
      
      $newValue = $this->option_value($poll_id, $option_name) + 1;

      return mysql_query("UPDATE options SET value='$newValue' WHERE poll_id='$poll_id' AND option_name = '$option_name';");
    }

  }
?>