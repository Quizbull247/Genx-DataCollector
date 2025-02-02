<?php
class Feedback_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert_feedback($data) {
        // Insert data into the feedback table
        $this->db->insert('feedback', $data);

        // Return the ID of the inserted record
        return $this->db->insert_id();
    }
    
      public function get_all_feedback() {
        $query = $this->db->get('feedback');
        return $query->result(); // or $query->result() for objects
    }
       public function get_one_feedback($id) {
           $this->db->where('id',$id);
        $query = $this->db->get('feedback');
        return $query->row(); 
    }
        public function countAllRecords($where)
    {
        $this->db->select("COUNT(id) as count");
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get("feedback");
        $result = $query->row();
        return $result->count;
    }

    public function countFilteredRecords($where)
    {
        $this->db->select("COUNT(id) as count");
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get("feedback");
        $result = $query->row();
        return $result->count;
    }

    public function getData($limit, $start, $where, $order, $dir)
    {
        $this->db->select("*");
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->limit($limit, $start);
        $this->db->order_by($order, $dir);
            //   $this->db->order_by($order, 'ASC');
        $query = $this->db->get("feedback");
        return $query->result();
    }

    public function getUserName($userId)
    {
        $this->db->select('name');
        $this->db->where('userId', $userId);
        $query = $this->db->get('tbl_users');
        $row = $query->row();
        return !empty($row) ? $row->name : '';
    }
    
    public function getAllData($where = '')
{
    $this->db->select("*");
    if (!empty($where)) {
        $this->db->where($where);
    }
    $this->db->order_by("id", "DESC"); // Order as needed
    $query = $this->db->get("feedback");
    return $query->result();
}
public function getUserIdsByName($name)
{
    $this->db->select('userId');
    $this->db->from('tbl_users');
    $this->db->like('name', $name);
    $this->db->or_like('mobile', $name);  
    $query = $this->db->get();

    $result = $query->result_array();
    return array_column($result, 'userId');
}


}
