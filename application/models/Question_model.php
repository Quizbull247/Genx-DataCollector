<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Booking_model (Booking Model)
 * Booking model class to get to handle booking related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Question_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function emojiListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function emojiListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        // $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    
    function selectallque()
    {
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
            $this->db->where('BaseTbl.id <=', 33); 
        $this->db->order_by('BaseTbl.id', 'ASC');
         $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    
    
    function selectallquenextquestions()
    {
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
            $this->db->where('BaseTbl.id >', 18); 
        $this->db->order_by('BaseTbl.id', 'ASC');
         $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    
    
    function electionListing()
    {
        $this->db->select('*');
        $this->db->from('election as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    
     
    function getZoneByElectionId($zId)
    {
        $this->db->select('*');
        $this->db->from('zone as BaseTbl');
        $this->db->where('BaseTbl.election',$zId); 
        $this->db->order_by('BaseTbl.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
      function getQcListBYzcId($zId)
    {
        $this->db->select('*');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->where('BaseTbl.userId',$zId); 
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }
    
    
    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewemoji($bookingInfo)
    {
        $this->db->trans_start();
        $this->db->insert('question', $bookingInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getemojiInfo($bookingId)
    {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where('id', $bookingId);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
    function editemojij($bookingInfo, $bookingId)
    {
        $this->db->where('id', $bookingId);
        $this->db->update('question', $bookingInfo);
        
        return TRUE;
    }
    public function deleteEmoji($id)
{
    $this->db->where('id', $id);
    return $this->db->delete('question'); // Adjust 'emojis' to your actual table name
}



  public function get_all_candidates($zone) {
       $this->db->select('candidate.id, candidate.name, party.name as party_name');
    $this->db->from('candidate');
    $this->db->join('party', 'party.id = candidate.party');
    $this->db->where('candidate.zone', $zone);
    $query = $this->db->get();
    return $query->result();
    
}


  public function get_bjp_candidates($zone) {
   $this->db->select('candidate.id, candidate.name, party.name as party_name');
    $this->db->from('candidate');
    $this->db->join('party', 'party.id = candidate.party');
    $this->db->where('candidate.zone', $zone);
    $this->db->where_in('candidate.party', [1, 2, 6]);
    $query = $this->db->get();
    return $query->result();
}

  public function get_cong_candidates($zone) {
 
      $this->db->select('candidate.id, candidate.name, party.name as party_name');
    $this->db->from('candidate');
    $this->db->join('party', 'party.id = candidate.party');
    $this->db->where('candidate.zone', $zone);
    $this->db->where_in('candidate.party', [3,4,18]);
    $query = $this->db->get();
    return $query->result();
    
}


public function get_other_candidates($zone) {
    $this->db->select('candidate.id, candidate.name, party.name as party_name');
    $this->db->from('candidate');
    $this->db->join('party', 'party.id = candidate.party');
    $this->db->where('candidate.zone', $zone);
    $this->db->where_not_in('candidate.party', [1, 2,6,3,4,18]); // Exclude party values 1 and 3
    $query = $this->db->get();
    return $query->result();
}



    public function get_questions_with_options() {
    // Select questions and their options
    $this->db->select('q.id as question_id, q.name as question_name, o.id as option_id, o.option as option_text, o.option_order');
    $this->db->from('question as q');
    $this->db->where('q.id <=', 33); // Ensure proper operator usage
    $this->db->join('options as o', 'o.question = q.id', 'left');
    $this->db->order_by('q.id, o.option_order');
    $query = $this->db->get();
    $result = $query->result();

    // Initialize an empty array to hold questions with options
    $questions = array();

    // Process query results
    foreach ($result as $row) {
        // Initialize question if not already set
        if (!isset($questions[$row->question_id])) {
            $questions[$row->question_id] = array(
                'id' => $row->question_id,
                'name' => $row->question_name,
                'options' => array()
            );
        }
        // Add option if it exists
        if ($row->option_id) {
            $questions[$row->question_id]['options'][] = array(
                'id' => $row->option_id,
                'text' => $row->option_text,
                'order' => $row->option_order
            );
        }
    }

    // Return the questions as a numerically indexed array
    return array_values($questions);
}

    
        
 public function get_questions_with_optionslast() {
        $this->db->select('q.id as question_id, q.name as question_name, o.id as option_id, o.option as option_text, o.option_order');
        $this->db->from('question as q');
          $this->db->where('q.id >', 33); 

        $this->db->join('options as o', 'o.question = q.id', 'left');
        $this->db->order_by('q.id, o.option_order');
        $query = $this->db->get();
        $result = $query->result();

        $questions = array();
        foreach ($result as $row) {
            if (!isset($questions[$row->question_id])) {
                $questions[$row->question_id] = array(
                    'id' => $row->question_id,
                    'name' => $row->question_name,
                    'options' => array()
                );
            }
            if ($row->option_id) {
                $questions[$row->question_id]['options'][] = array(
                    'id' => $row->option_id,
                    'text' => $row->option_text,
                    'order' => $row->option_order
                );
            }
        }

        return array_values($questions);
    }
   
    

}