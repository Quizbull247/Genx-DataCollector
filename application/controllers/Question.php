<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Booking (BookingController)
 * Booking Class to control booking related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Question extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        $this->load->model('Question_model', 'bm');
        $this->isLoggedIn();
        $this->module = 'question';
        
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('question/list');
    }
    
    /**
     * This function is used to load the booking list
     */
    function list()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->bm->emojiListingCount($searchText);

			$returns = $this->paginationCompress ( "list/", $count, 10 );
            
            $data['records'] = $this->bm->emojiListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : Party';
            
            $this->loadViews("question/list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'CodeInsect : Add New question';
            
             $this->global['election']=$this->bm->electionListing();

             

            $this->loadViews("question/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addemoji()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                // Handle file upload
    
                    $roomName = $this->security->xss_clean($this->input->post('name'));
                    $election=$this->input->post('election');
                    $bookingInfo = array(
                        'name' => $roomName,
                        'election' => $election,
                        'createdBy' => $this->vendorId,
                        'createdDtm' => date('Y-m-d H:i:s')
                    );
    
                    $result = $this->bm->addNewemoji($bookingInfo);
    
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'New question created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'question creation failed');
                    }
                    redirect('question/list');
            }
        }
    }

    
    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($bookingId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($bookingId == null)
            {
                redirect('question/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);
            $data['election']=$this->bm->electionListing();


            $this->global['pageTitle'] = 'CodeInsect : Edit question';
            
            $this->loadViews("question/edit", $this->global, $data, NULL);
        }
    }
    
    function view($bookingId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($bookingId == null)
            {
                redirect('question/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);
            $data['election']=$this->bm->electionListing();


            $this->global['pageTitle'] = 'CodeInsect : Edit question';
            
            $this->loadViews("question/view", $this->global, $data, NULL);
        }
    } 
    /**
     * This function is used to edit the user information
     */
    function editemoji()
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
    
            $bookingId = $this->input->post('bookingId');
    
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->edit($bookingId);
            } else {
                $roomName = $this->security->xss_clean($this->input->post('name'));
                                    $election=$this->input->post('election');

                $bookingInfo = array(
                    'name' => $roomName,
                    'election'=>$election,
                    'createdBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
    
                // Check if a new image file is uploaded

                $result = $this->bm->editemojij($bookingInfo, $bookingId);
    
                if ($result) {
                    $this->session->set_flashdata('success', 'Emoji updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Emoji update failed');
                }
    
                redirect('question/list');
            }
        }
    }

    public function html_clean($s, $v)
    {
        return strip_tags((string) $s);
    }
    
    public function delete()
    {
    $id = $this->input->post('id');

    if (!$this->hasDeleteAccess()) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
        return;
    }

    $this->load->model('Question_model'); // Load your model
    $result = $this->Question_model->deleteEmoji($id); // Assume deleteEmoji is a method in your model

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete emoji']);
    }
}

    public function update(){
        $bookingId=$this->input->post('id');
        $bookingInfo['status']=$this->input->post('status');
        $result = $this->bm->editemojij($bookingInfo, $bookingId);
    
                if ($result) {
                   echo"1";
                } else {
                      echo"0";
                }
    }


   function serveydata()
    {
       
        $this->load->model('feedback_model');
       

        $this->global['pageTitle'] = 'Datacollector : Servey Data';
        $this->loadViews("question/serveylist", $this->global, [], NULL);
    }
    
    function serveydatanew()
{
    $this->load->model('feedback_model');

    $limit = $this->input->get('length');
    $start = $this->input->get('start');
    $order = 'id';
    $dir = 'DESC';

    $search = $this->input->get('search')['value'];

    // Determine user role and apply condition accordingly
      $where = "name != '1'  ";
    
            // $today = Date('Y-m-d');
            // // $twoDaysBefore = date('Y-m-d', strtotime('-2 days', strtotime($today)));

            // $where .= "   AND DATE(created_at) BETWEEN '$today' AND '$today'";

     if (!empty($search)) {
        $user_ids = $this->feedback_model->getUserIdsByName($search);
        if (!empty($user_ids)) {
            $user_ids_str = implode(",", $user_ids);
            $where .= ($where ? " AND" : "") . " qc_id IN ($user_ids_str)";
            $where .= ($where ? " OR" : "") . " zc_id IN ($user_ids_str)";
            $where .= ($where ? " OR" : "") . " qt_id IN ($user_ids_str)";
        } 
    }
    
    
    
        $segmaneid=  $this->input->get('qt_id');


    if ($this->session->userdata('role') == 2) {
        $where .= "  AND   qc_id = " . $this->session->userdata('userId');
    } else if ($this->session->userdata('role') == 3) {
        $where .= "  AND  zc_id = " . $this->session->userdata('userId');
    }

    if (!empty($search)) {
        $where .= " AND (name LIKE '%$search%' OR mobile LIKE '%$search%')";
        $totalFiltered = $this->feedback_model->countFilteredRecords($where);
    }
    
    if ($segmaneid != 0 && $this->session->userdata('role') == 1) {
        $where .= "   AND  qt_id = " . $segmaneid;
    }else if ($segmaneid != 0){
           $where .= "  AND qt_id = " . $segmaneid;
    }
    // Total records
    $totalData = $this->feedback_model->countAllRecords($where);

    // Filtered records
    $totalFiltered = $totalData;
    

    // Fetch records with limit and offset
    
    $posts = $this->feedback_model->getData($limit, $start, $where, $order, $dir);

    $data = array();
    if (!empty($posts)) {
        foreach ($posts as $post) {
            $nestedData['id'] = $post->id;
            $nestedData['name'] = $post->name;
            $nestedData['mobile'] = $post->mobile;
            $nestedData['qc_id'] = $this->feedback_model->getUserName($post->qc_id);
            $nestedData['zc_id'] = $this->feedback_model->getUserName($post->zc_id);
            $nestedData['qt_id'] = $this->feedback_model->getUserName($post->qt_id);
            for ($i = 1; $i <= 41; $i++) {
                $nestedData["ans_string_$i"] = $post->{"ans_string_$i"};
            }
            $nestedData['created_at'] = date("d-m-Y", strtotime($post->created_at));
            $nestedData['action'] = '<a href="' . base_url() . 'question/viewfeedback/' . $post->id . '" class="btn btn-success"><i class="fa fa-eye"></a></i>';

            $data[] = $nestedData;
        }
    }

    $json_data = array(
        "draw" => intval($this->input->get('draw')),
        "recordsTotal" => intval($totalData),
        "recordsFiltered" => intval($totalFiltered),
        "data" => $data
    );

    echo json_encode($json_data);
}

     function viewfeedback($id)
    {
        $this->load->model('feedback_model');
        $data['records'] = $this->feedback_model->get_one_feedback($id);
       // echo "<pre>";
       // print_r(     $data['records']);
        $data['questions'] = $this->bm->selectallque();
         $data['questionslast'] = $this->bm->selectallquenextquestions();
         $zoneid =  $data['records']->zoneid;
    $data['candidates'] = $this->bm->get_all_candidates($zoneid);
 
//print_r(  $data['questions']);
        $this->global['pageTitle'] = 'Datacollector : Servey Data';
        $this->loadViews("question/viewfeedback", $this->global, $data, NULL);
    }
    
    

}

?>