<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Booking_model (Booking Model)
 * Booking model class to get to handle booking related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Option_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function emojiListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('options as BaseTbl');
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
        $this->db->from('options as BaseTbl');
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
    
    function electionListing()
    {
        $this->db->select('*');
        $this->db->from('election as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'DESC');
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
        $this->db->insert('options', $bookingInfo);
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
        $this->db->from('options');
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
        $this->db->update('options', $bookingInfo);
        
        return TRUE;
    }
    public function deleteEmoji($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('options'); // Adjust 'emojis' to your actual table name
    }
    public function zoneListing($election_id) 
    {
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
        $this->db->where('election', $election_id);
        $this->db->order_by('BaseTbl.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function partyListing(){
        $this->db->select('*');
        $this->db->from('party as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function questionListingnew(){
        $this->db->select('*');
        $this->db->from('question as BaseTbl');
        $this->db->order_by('BaseTbl.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    

    

}