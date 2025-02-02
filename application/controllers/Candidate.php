<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
 
class Candidate extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Candidate_model', 'bm');
        $this->isLoggedIn();
        $this->module = 'candidate';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('candidate/list');
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
            
            $this->loadViews("candidate/list", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'CodeInsect : Add New candidate';
            
             $this->global['election']=$this->bm->electionListing();
             $this->global['party']=$this->bm->partyListing();

             

            $this->loadViews("candidate/add", $this->global, NULL, NULL);
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
            $this->form_validation->set_rules('name', 'Name', 'trim|callback_html_clean|required|max_length[50]');
    
            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                // Handle file upload
    
                    $roomName = $this->security->xss_clean($this->input->post('name'));
                    $election=$this->input->post('election');
                    $party=$this->input->post('party');
                    $zone=$this->input->post('zone');
                    $bookingInfo = array(
                        'name' => $roomName,
                        'election' => $election,
                        'zone' => $zone,
                        'party' => $party,
                        'createdBy' => $this->vendorId,
                        'createdDtm' => date('Y-m-d H:i:s')
                    );
    
                    $result = $this->bm->addNewemoji($bookingInfo);
    
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'New candidate created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'candidate creation failed');
                    }
                    redirect('candidate/list');
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
                redirect('emoji/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);
            $data['election']=$this->bm->electionListing();


            $this->global['pageTitle'] = 'CodeInsect : Edit candidate';
             $this->global['election']=$this->bm->electionListing();
             $this->global['party']=$this->bm->partyListing();
             $this->global['zone']=$this->bm->zoneListingnew();
            
            $this->loadViews("candidate/edit", $this->global, $data, NULL);
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
                redirect('candidate/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);
            $data['election']=$this->bm->electionListing();


            $this->global['pageTitle'] = 'GNX MEDIA : Edit candidate';
             $this->global['election']=$this->bm->electionListing();
             $this->global['party']=$this->bm->partyListing();
             $this->global['zone']=$this->bm->zoneListingnew();
            
            $this->loadViews("candidate/view", $this->global, $data, NULL);
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
    
            $this->form_validation->set_rules('name', 'Name', 'trim|callback_html_clean|required|max_length[50]');
    
            if ($this->form_validation->run() == FALSE) {
                $this->edit($bookingId);
            } else {
                $roomName = $this->security->xss_clean($this->input->post('name'));
                                    $election=$this->input->post('election');
                                    $party=$this->input->post('party');
                                    $zone=$this->input->post('zone');

                $bookingInfo = array(
                    'name' => $roomName,
                    'election'=>$election,
                    'party'=>$party,
                    'zone'=>$zone,
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
    
                redirect('candidate/list');
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

    $this->load->model('Candidate_model'); // Load your model
    $result = $this->Candidate_model->deleteEmoji($id); // Assume deleteEmoji is a method in your model

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete emoji']);
    }
}
   public function zone(){
        $election_id= $this->input->post('electionId');
        $parties =$this->bm->zoneListing($election_id);
        $candidateListing =$this->bm->candidateListing($election_id);
        echo json_encode(['parties' => $parties,'candidateListing'=>$candidateListing]);

    }
    
     public function getzc(){
        $qc_id= $this->input->post('qc_id');
        $election_id= $this->input->post('electionId');
        $parties =$this->bm->getZCListing($qc_id,$election_id);
        echo json_encode(['candidateListing' => $parties]);

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
}

?>