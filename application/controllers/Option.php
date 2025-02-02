<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Booking (BookingController)
 * Booking Class to control booking related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Option extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Option_model', 'bm');
        $this->isLoggedIn();
        $this->module = 'option';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('option/list');
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
            
            $this->loadViews("option/list", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'CodeInsect : Add New option';
            
             $this->global['election']=$this->bm->electionListing();
             $this->global['party']=$this->bm->partyListing();

             

            $this->loadViews("option/add", $this->global, NULL, NULL);
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
            $this->form_validation->set_rules('option', 'option', 'trim|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                // Handle file upload
    
                    $election=$this->input->post('election');
                    $option=$this->input->post('option');
                    $question=$this->input->post('question');
                    $option_order=$this->input->post('option_order');
                    $bookingInfo = array(
                        'election' => $election,
                        'option_order' => $option_order,
                         'option' => $option,
                        'question' => $question,
                        'createdBy' => $this->vendorId,
                        'createdDtm' => date('Y-m-d H:i:s')
                    );
    
                    $result = $this->bm->addNewemoji($bookingInfo);
    
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'New option created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'option creation failed');
                    }
                    redirect('option/list');
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


            $this->global['pageTitle'] = 'CodeInsect : Edit option';
             $this->global['election']=$this->bm->electionListing();
             $this->global['question']=$this->bm->questionListingnew();

            $this->loadViews("option/edit", $this->global, $data, NULL);
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
                redirect('option/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);
            $data['election']=$this->bm->electionListing();


            $this->global['pageTitle'] = 'CodeInsect : Edit option';
             $this->global['election']=$this->bm->electionListing();
             $this->global['question']=$this->bm->questionListingnew();

            $this->loadViews("option/view", $this->global, $data, NULL);
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
    
            $this->form_validation->set_rules('option', 'option', 'trim|required');
    
            if ($this->form_validation->run() == FALSE) {
                $this->edit($bookingId);
            } else {
                $election=$this->input->post('election');
                    $option=$this->input->post('option');
                    $question=$this->input->post('question');
                    $option_order=$this->input->post('option_order');
                    $bookingInfo = array(
                        'election' => $election,
                        'option_order' => $option_order,
                         'option' => $option,
                        'question' => $question,
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
    
                redirect('option/list');
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

    $this->load->model('Option_model'); // Load your model
    $result = $this->Option_model->deleteEmoji($id); // Assume deleteEmoji is a method in your model

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete emoji']);
    }
}
   public function zone(){
        $election_id= $this->input->post('electionId');
        $parties =$this->bm->zoneListing($election_id);
        echo json_encode(['parties' => $parties]);

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
    
    
    
    
     public function getQuestions(){
        $election_id= $this->input->post('electionId');
        $parties =$this->bm->zoneListing($election_id);
        echo json_encode(['parties' => $parties]);

    }
    
    
}

?>