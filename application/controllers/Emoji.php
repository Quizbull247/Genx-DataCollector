<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Booking (BookingController)
 * Booking Class to control booking related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Emoji extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Emoji_model', 'bm');
        $this->isLoggedIn();
        $this->module = 'Emoji';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('emoji/list');
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
            
            $this->global['pageTitle'] = 'CodeInsect : Emoji';
            
            $this->loadViews("emoji/list", $this->global, $data, NULL);
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
            $this->global['pageTitle'] = 'CodeInsect : Add New Emoji';

            $this->loadViews("emoji/add", $this->global, NULL, NULL);
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
                $config['upload_path'] = 'uploads/emoji/'; // Ensure this path is correct and writable
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048; // 2MB
                $config['max_width'] = 1024;
                $config['max_height'] = 768;
    
                $this->load->library('upload', $config);
    
                if (!$this->upload->do_upload('emoji_image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'File upload error: ' . $error);
                    $this->add();
                } else {
                    $fileData = $this->upload->data();
                    $imagePath = $fileData['file_name'];
    
                    $roomName = $this->security->xss_clean($this->input->post('name'));
                    $bookingInfo = array(
                        'name' => $roomName,
                        'image' => $imagePath, // Save image path
                        'createdBy' => $this->vendorId,
                        'createdDtm' => date('Y-m-d H:i:s')
                    );
    
                    $result = $this->bm->addNewemoji($bookingInfo);
    
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'New Emoji created successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Emoji creation failed');
                    }
    
                    redirect('emoji/list');
                }
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

            $this->global['pageTitle'] = 'CodeInsect : Edit Emoji';
            
            $this->loadViews("emoji/edit", $this->global, $data, NULL);
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
                redirect('emoji/list');
            }
            
            $data['bookingInfo'] = $this->bm->getemojiInfo($bookingId);

            $this->global['pageTitle'] = 'CodeInsect : Edit Emoji';
            
            $this->loadViews("emoji/view", $this->global, $data, NULL);
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
                $bookingInfo = array(
                    'name' => $roomName,
                    'createdBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
    
                // Check if a new image file is uploaded
                if (!empty($_FILES['emoji_image']['name'])) {
                    $config['upload_path'] = 'uploads/emoji/'; // Ensure this path is correct and writable
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = 2048; // 2MB
                    $config['max_width'] = 1024;
                    $config['max_height'] = 768;
    
                    $this->load->library('upload', $config);
    
                    if (!$this->upload->do_upload('emoji_image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', 'File upload error: ' . $error);
                        $this->edit($bookingId);
                        return; // Stop execution if file upload fails
                    } else {
                        $fileData = $this->upload->data();
                        $bookingInfo['image'] = $fileData['file_name']; // Save new image path
                    }
                }
    
                $result = $this->bm->editemojij($bookingInfo, $bookingId);
    
                if ($result) {
                    $this->session->set_flashdata('success', 'Emoji updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Emoji update failed');
                }
    
                redirect('emoji/list');
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

    $this->load->model('emoji_model'); // Load your model
    $result = $this->emoji_model->deleteEmoji($id); // Assume deleteEmoji is a method in your model

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

}

?>