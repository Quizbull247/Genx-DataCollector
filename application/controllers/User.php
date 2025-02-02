<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->output->delete_cache();

        $this->load->model('user_model');
        $this->load->model('Question_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->global['todaydata'] = $this->user_model->countTodayFeedback();
        $this->global['totaldata'] = $this->user_model->countTotalFeedback();
        $this->global['totalqc'] = $this->user_model->countTotalOT(2);
        $this->global['totalzc'] = $this->user_model->countTotalOT(3);
        $this->global['totalot'] = $this->user_model->countTotalOT(4);

        $questionCount = 41; // Number of questions

        $columns = [];
        for ($i = 1; $i <= $questionCount; $i++) {
            $columns[] = "ans_string_$i";
        }

        // Create the query
        $query = $this->db->select(implode(', ', $columns))
            ->get('feedback');
        $feedbackData = $query->result_array();

        $graphs = [];

        foreach (range(1, $questionCount) as $i) {
            $questionKey = "ans_string_$i";

            // Collect all answers for the question
            $answers = array_column($feedbackData, $questionKey);

            // Filter out any invalid values (NULL, arrays, objects, etc.)
            $answers = array_filter($answers, function ($value) {
                return is_string($value) || is_int($value);
            });

            // Count occurrences of each answer
            $answerCounts = array_count_values($answers);

            // Calculate the total responses for the question
            $totalResponses = array_sum($answerCounts);

            // Calculate percentages
            $percentages = array_map(function ($count) use ($totalResponses) {
                return round(($count / $totalResponses) * 100, 2);
            }, $answerCounts);

            // Prepare data for the graph
            $graphs[$questionKey] = [
                'labels' => array_keys($answerCounts),
                'data' => array_values($percentages), // Pass percentages instead of counts
            ];
        }



        $this->global['graphs'] = $graphs;


        $this->loadViews("general/dashboard", $this->global, NULL, NULL);
    }

    /**
     * This function is used to load the user list
     */
    function userListing()
    {
       
        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {        
        $searchText = '';
        if (!empty($this->input->post('searchText'))) {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
        }
        $data['searchText'] = $searchText;

        $this->load->library('pagination');

        $count = $this->user_model->userListingCount($searchText);

        $returns = $this->paginationCompress("userListing/", $count, 10);

        $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'CodeInsect : User Listing';

        $this->loadViews("users/users", $this->global, $data, NULL);
        //}
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {

        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        $this->load->model('user_model');
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'CodeInsect : Add New User';
        $this->global['election'] = $this->Question_model->electionListing();

        $this->loadViews("users/addNew", $this->global, $data, NULL);
        // }
    }


    function addNewQC()
    {
        $this->load->model('user_model');
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'CodeInsect : Add New User';
        $this->global['election'] = $this->Question_model->electionListing();

        $this->loadViews("users/addNewqc", $this->global, $data, NULL);

    }

    function addNewUseradmin()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewQC();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $qc_id = 0;
            $zc_id = 0;
            if ($this->session->userdata('role') == 1) {
                $roleId = 2;
                $qc_id = 0;
                $zc_id = 0;
            } else if ($this->session->userdata('role') == 2) {
                $roleId = 3;
                $qc_id = $this->vendorId;
                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$qc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $zc_id = $row->zc_id;
                }

            } else {
                $roleId = 4;




                $zc_id = $this->vendorId;

                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$zc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $qc_id = $row->qc_id;
                }

            }






            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $isAdmin = $this->input->post('isAdmin');
            $electionId = $this->input->post('election');

            $userInfo = array(
                'email' => $email,
                'password' => getHashedPassword($password),
                'roleId' => 2,
                'name' => $name,
                'mobile' => $mobile,
                'isAdmin' => $isAdmin,
                'qc_id' => 0,
                'zc_id' => 0,
                'zoneId' => 0,
                'electionId' => $electionId,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $this->load->model('user_model');

            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }

            redirect('userListing');
        }
        // }
    }


    function addNewZC()
    {
        $this->load->model('user_model');
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'CodeInsect : Add New User';
        $this->global['election'] = $this->Question_model->electionListing();

        $this->loadViews("users/addNewzc", $this->global, $data, NULL);

    }

    function addNewUseradminzc()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewZC();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $qc_id = 0;
            $zc_id = 0;
            if ($this->session->userdata('role') == 1) {
                $roleId = 2;
                $qc_id = 0;
                $zc_id = 0;
            } else if ($this->session->userdata('role') == 2) {
                $roleId = 3;
                $qc_id = $this->vendorId;
                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$qc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $zc_id = $row->zc_id;
                }

            } else {
                $roleId = 4;




                $zc_id = $this->vendorId;

                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$zc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $qc_id = $row->qc_id;
                }

            }






            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $isAdmin = $this->input->post('isAdmin');
            $electionId = $this->input->post('election');
            $qc_id = $this->input->post('qc_id');
            $zone = $this->input->post('zone');

            $userInfo = array(
                'email' => $email,
                'password' => getHashedPassword($password),
                'roleId' => 3,
                'name' => $name,
                'mobile' => $mobile,
                'isAdmin' => $isAdmin,
                'qc_id' => $qc_id,
                'zc_id' => 0,
                'zoneId' => $zone,
                'electionId' => $electionId,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $this->load->model('user_model');

            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }

            redirect('userListing');
        }
        // }
    }


    function addNewOT()
    {
        $this->load->model('user_model');
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'CodeInsect : Add New User';
        $this->global['election'] = $this->Question_model->electionListing();

        $this->loadViews("users/addNewot", $this->global, $data, NULL);

    }

    function addNewUseradminot()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewOT();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $qc_id = 0;
            $zc_id = 0;
            if ($this->session->userdata('role') == 1) {
                $roleId = 2;
                $qc_id = 0;
                $zc_id = 0;
            } else if ($this->session->userdata('role') == 2) {
                $roleId = 3;
                $qc_id = $this->vendorId;
                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$qc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $zc_id = $row->zc_id;
                }

            } else {
                $roleId = 4;




                $zc_id = $this->vendorId;

                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$zc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $qc_id = $row->qc_id;
                }

            }






            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $isAdmin = $this->input->post('isAdmin');
            $electionId = $this->input->post('election');
            $qc_id = $this->input->post('qc_id');
            $zc_id = $this->input->post('zc_id');
            $zone = $this->input->post('zone');

            $userInfo = array(
                'email' => $email,
                'password' => getHashedPassword($password),
                'roleId' => 4,
                'name' => $name,
                'mobile' => $mobile,
                'isAdmin' => $isAdmin,
                'qc_id' => $qc_id,
                'zc_id' => $zc_id,
                'zoneId' => $zone,
                'electionId' => $electionId,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $this->load->model('user_model');

            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }

            redirect('userListing');
        }
        // }
    }


    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if (empty($result)) {
            echo ("true");
        } else {
            echo ("false");
        }
    }

    /**
     * This function is used to add new user to the system
     */


    function addNewUser()
    {


        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNew();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $qc_id = 0;
            $zc_id = 0;
            if ($this->session->userdata('role') == 1) {
                $roleId = 2;
                $qc_id = 0;
                $zc_id = 0;
            } else if ($this->session->userdata('role') == 2) {
                $roleId = 3;
                $qc_id = $this->vendorId;
                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$qc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $zc_id = $row->zc_id;
                }

            } else {
                $roleId = 4;




                $zc_id = $this->vendorId;

                $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$zc_id' ");
                $row = $q->row();
                if (!empty($row)) {
                    $qc_id = $row->qc_id;
                }

            }






            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $electionId = $this->input->post('election');
            $zoneId = $this->input->post('zone');
            $isAdmin = $this->input->post('isAdmin');

            $userInfo = array(
                'email' => $email,
                'password' => getHashedPassword($password),
                'roleId' => $roleId,
                'name' => $name,
                'mobile' => $mobile,
                'isAdmin' => $isAdmin,
                'qc_id' => $qc_id,
                'zoneId' => $zoneId,
                'electionId' => $electionId,
                'zc_id' => $zc_id,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $this->load->model('user_model');

            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }

            redirect('userListing');
        }
        // }
    }


    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        if ($userId == null) {
            redirect('userListing');
        }

        $data['roles'] = $this->user_model->getUserRoles();
        $data['userInfo'] = $this->user_model->getUserInfo($userId);
        $this->global['election'] = $this->Question_model->electionListing();
        $eId = $data['userInfo']->electionId;
        $qc_id = $data['userInfo']->qc_id;
        $this->global['zones'] = $this->Question_model->getZoneByElectionId($eId);
        $this->global['qcdlist'] = $this->Question_model->getQcListBYzcId($qc_id);

        $this->global['pageTitle'] = 'CodeInsect : Edit User';

        $this->loadViews("users/editOld", $this->global, $data, NULL);
        //}
    }


    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        $this->load->library('form_validation');

        $userId = $this->input->post('userId');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->editOld($userId);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $isAdmin = $this->input->post('isAdmin');
            $zoneId = $this->input->post('zone');
            $electionId = $this->input->post('election');
            $qc_id = $this->input->post('qc_id');

            $userInfo = array();

            if (empty($password)) {
                $userInfo = array(
                    'email' => $email,
                    'name' => $name,
                    'mobile' => $mobile,
                    'isAdmin' => $isAdmin,
                    'updatedBy' => $this->vendorId,
                    'zoneId' => $zoneId,
                    'qc_id' => $qc_id,
                    'electionId' => $electionId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
            } else {
                $userInfo = array(
                    'email' => $email,
                    'password' => getHashedPassword($password),

                    'name' => ucwords($name),
                    'mobile' => $mobile,
                    'isAdmin' => $isAdmin,

                    'qc_id' => $qc_id,
                    'zoneId' => $zoneId,
                    'electionId' => $electionId,



                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
            }
            // print_r($userInfo);die;
            $result = $this->user_model->editUser($userInfo, $userId);

            if ($result == true) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'User updation failed');
            }

            redirect('userListing');
        }
        //}
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        // if(!$this->isAdmin())
        // {
        //     echo(json_encode(array('status'=>'access')));
        // }
        // else
        // {
        $userId = $this->input->post('userId');
        $userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

        $result = $this->user_model->deleteUser($userId, $userInfo);

        if ($result > 0) {
            echo (json_encode(array('status' => TRUE)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
        //}
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("general/404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        // if(!$this->isAdmin())
        // {
        //     $this->loadThis();
        // }
        // else
        // {
        $userId = ($userId == NULL ? 0 : $userId);

        $searchText = $this->input->post('searchText');
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');

        $data["userInfo"] = $this->user_model->getUserInfoById($userId);

        $data['searchText'] = $searchText;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;

        $this->load->library('pagination');

        $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

        $returns = $this->paginationCompress("login-history/" . $userId . "/", $count, 10, 3);

        $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'CodeInsect : User Login History';

        $this->loadViews("users/loginHistory", $this->global, $data, NULL);
        // }        
    }

    /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;

        $this->global['pageTitle'] = $active == "details" ? 'CodeInsect : My Profile' : 'CodeInsect : Change Password';
        $this->loadViews("users/profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]|callback_emailExists');

        if ($this->form_validation->run() == FALSE) {
            $this->profile($active);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));

            $userInfo = array('name' => $name, 'email' => $email, 'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->user_model->editUser($userInfo, $this->vendorId);

            if ($result == true) {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }

            redirect('profile/' . $active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword', 'Old password', 'required|max_length[20]');
        $this->form_validation->set_rules('newPassword', 'New password', 'required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword', 'Confirm new password', 'required|matches[newPassword]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->profile($active);
        } else {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');

            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/' . $active);
            } else {
                $usersData = array(
                    'password' => getHashedPassword($newPassword),
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );

                $result = $this->user_model->changePassword($this->vendorId, $usersData);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Password updation successful');
                } else {
                    $this->session->set_flashdata('error', 'Password updation failed');
                }

                redirect('profile/' . $active);
            }
        }
    } 
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if (empty($result)) {
            $return = true;
        } else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }


    public function zccount()
    {
        $this->global['pageTitle'] = 'Datacollector : Dashboard';
        $q = $this->db->query("SELECT t.zc_id, u.name,
            COUNT(*) AS total_data, SUM(CASE WHEN DATE(t.created_at) = CURDATE() THEN 1 ELSE 0 END) AS today_data_count
            FROM feedback t JOIN tbl_users u ON u.userId = t.zc_id GROUP BY t.zc_id, u.name");
        $this->global['userRecords'] = $q->result();
        $this->loadViews("users/zcdata", $this->global, NULL, NULL);
    }



    public function otcount($id)
    {
        $this->global['pageTitle'] = 'Datacollector : Dashboard';

        // Sanitize the $id to ensure it is an integer
        $id = intval($id);

        $where = '';
        if ($id != 0) {
            $where = 'WHERE   u1.zc_id  = ' . $this->db->escape($id); // Escape the $id
        }

        // Prepare the SQL query with placeholders
        $sql = "
        SELECT 
    u1.userId AS qt_id, 
    u1.name, 
    u1.zc_id, 
    u2.name AS zcname,
    COUNT(t.id) AS total_data,
    SUM(CASE WHEN DATE(t.created_at) = CURDATE() THEN 1 ELSE 0 END) AS today_data_count
FROM 
    tbl_users u1
LEFT JOIN 
    feedback t ON u1.userId = t.qt_id AND u1.zc_id = t.zc_id
LEFT JOIN 
    tbl_users u2 ON u2.userId = u1.zc_id
 
    
    $where
    
    
GROUP BY 
    u1.userId, u1.name, u1.zc_id, u2.name;

    ";

        $q = $this->db->query($sql);
        $this->global['userRecords'] = $q->result();
        log_message('debug', 'Number of user records: ' . count($this->global['userRecords']));

        $this->loadViews("users/otdata", $this->global, NULL, NULL);
    }  
    public function otcountforzc()
    {
        $this->global['pageTitle'] = 'Datacollector : Dashboard';
        // Get user ID from session
        $userId = $this->session->userdata('userId');

        // Prepare the query with bindings
        $sql = "
SELECT 
    u1.userId AS qt_id, 
    u1.name, 
    u1.zc_id, 
    COALESCE(u2.name, '') AS zcname, 
    COUNT(t.qt_id) AS total_data,
    SUM(CASE WHEN DATE(t.created_at) = CURDATE() THEN 1 ELSE 0 END) AS today_data_count
FROM 
    tbl_users u1
LEFT JOIN 
    feedback t ON u1.userId = t.qt_id 
LEFT JOIN 
    tbl_users u2 ON u2.userId = u1.zc_id 
WHERE 
    u1.zc_id = ?
GROUP BY 
    u1.userId, u1.name, u1.zc_id, u2.name;
";

        // Execute the query with the user ID as a parameter
        $q = $this->db->query($sql, array($userId));

        // Store the result in the global userRecords
        $this->global['userRecords'] = $q->result(); 

        $this->loadViews("users/otdataforzc", $this->global, NULL, NULL);
    } 
}

?>