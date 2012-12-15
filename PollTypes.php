<?php
    require_once 'config.php';
    require_once 'utils.php';
    require_once 'classes/VoteModel.php';
    require_once 'classes/OptionModel.php';
    require_once 'classes/PollModel.php';
    
    class PollTypes {
        
      function vote_is_valid($user_id, $poll_id,$vote_values){
        
         $voteModel = new VoteModel('votes');
         $optionModel = new OptionModel('options');

         if($voteModel->has_voted($user_id,$poll_id) && $vodeModel->vote_in_time($poll_id)){
          
            $poll_type = get_poll_type($poll_id);
            $vote_options = get_options($poll_id);
            
            switch($pole_type){
               case 'binary' :
                    if($vote_options.size != 1) // if it's not yes/no poll then the size will not be 1
                        return false;

                    if($vote_values[$vote_options[0]]!= 1 || $vote_values[$vote_options[0]] != 0)
                        return false;

                    return true;
                    
               case 'one_out_of_many':
                   if($vote_options.size != $vote_values.size)
                       return false;
                   return true;
               default:
                   return false;

          }
        }
      }    
        
    }

?>
