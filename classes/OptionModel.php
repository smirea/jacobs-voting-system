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
      $result = $this->insert("(poll_id, option_name, value) VALUES ('$poll_id', '$option_name', '0'); ");
      if($result === false) {
        Output::error(new DatabaseError($this));
        return null;
      }
      return true;
    }

   /**
    * Returns vote result for desired option
    * @param {Int} $poll_id is the id of the poll to which option belongs
    * @param {String} $option_name is the name of the option
    * @return {Int}
    * */
    public function option_value($poll_id, $option_name) {
      $result = $this->select("value","WHERE poll_id='$poll_id' AND option_name='$option_name';");
      if($result === false) {
        Output::error(new DatabaseError($this));
        return null;
      }
      $value = mysql_fetch_assoc($result);
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

      $result = $this->update("SET value='$newValue' WHERE poll_id='$poll_id' AND option_name = '$option_name';");
      if($result === false) {
        Output::error(new DatabaseError($this));
        return null;
      }
      return true;
    }

    public function return_all_options($poll_id) {

      $result = array();
      $response = $this->select("option_name", " WHERE poll_id='$poll_id';");
      if($response === false) {
        Output::error(new DatabaseError($this));
        return null;
      }
      $array = sql_to_array($response);

      foreach ($array as $a) {
        $result[]=$a['option_name'];  
      }
      return $result;
    }

  }
?>