<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('Question_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Kolkata');

    } 
    
      public function index()
    {
         $response = array(
                'status' => false,
                'message' => 'api'
            );
       $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function login()
    {

        // Set validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails
            $response = array(
                'status' => false,
                'message' => validation_errors()
            );
        } else {
            // Get email and password from request
            $email = $this->input->post('mobile');
            $password = $this->input->post('password');

            // Check the user in the database
            $user = $this->login_model->loginMeByMobile($email, $password);

            if (!empty($user)) {
                // Successful login
                $response = array(
                    'status' => true,
                    'message' => 'Login successful',
                    'data' => array(
                        'userId' => $user->userId,
                        'name' => $user->name,
                        'roleId' => $user->roleId,
                        'isAdmin' => $user->isAdmin,
                        'role' => $user->role,
                        'zoneId' => $user->zoneId,
                        'zoneName' => $user->zoneName
                    )
                );
            } else {
                // Invalid credentials
                $response = array(
                    'status' => false,
                    'message' => 'Invalid email or password'
                );
            }
        }

        // Return the response in JSON format
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function syncAllData()
    {
        // Set validation rules
        $this->form_validation->set_rules('userId', 'User Id', 'required|integer');
        $this->form_validation->set_rules('zoneId', 'Zone Id', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails
            $response = array(
                'status' => false,
                'message' => validation_errors()
            );
        } else {
            // Get email and password from request
            $userid = $this->input->post('userId');
            $zoneid = $this->input->post('zoneId');

            // Check the user in the database
            $candidates = $this->Question_model->get_all_candidates($zoneid);
            $questions = $this->Question_model->get_questions_with_options();

            // Use Query Builder to avoid SQL injection
            $this->db->select('COUNT(*) AS total_feedback');
            $this->db->from('feedback');
            $this->db->where('qt_id', $userid);
            $this->db->group_by('qt_id');
            $query = $this->db->get();
            $countdata = $query->row();

             
            
            
        $candidatesbjp = $this->Question_model->get_bjp_candidates($zoneid);


             // Define options with all candidates
            $candidatesbjpOptions = [];
            foreach ($candidatesbjp as $candidate) {
                $candidatesbjpOptions[] = [
                    'id' => $candidate->id,
                    'order' => '1',
                    'text' => $candidate->name .' ('.$candidate->party_name.')'
                ];
            }
            // Add "भाजपा से अन्य" and "नहीं जानते" options
$candidatesbjpOptions[] = [
    'id' => 'other', // You can choose a unique id here
    'order' => '1',
    'text' => 'जदयू-भाजपा और उनके समर्थक दलों से अन्य'
];

$candidatesbjpOptions[] = [
    'id' => 'unknown', // You can choose a unique id here
    'order' => '1',
    'text' => 'नहीं जानते'
];

            // Construct the final object
            $resultbjp = [
                'id' => '34',
                'name' =>' आपके अनुसार इस क्षेत्र से जदयू-भाजपा और उनके समर्थक दलों का विधायक उम्मीदवार किसे होना चाहिए?',
                'options' => $candidatesbjpOptions
            ];
            
            
            
            $q = $this->db->query("
    SELECT tbl_users.*, zone.name AS zone_name 
    FROM tbl_users 
    JOIN zone ON zone.id = tbl_users.zoneId 
    WHERE tbl_users.userId = '$userid'
");
$row = $q->row();


            
            $candidatesOptionsfirst[] = [
                                'id' => '1', // You can choose a unique id here
                                'order' => '1',
                                'text' => $row->zone_name    
                                ];




             $resultfirst = [
                'id' => '1',
                'name' => 'विधानसभा संख्या',
                'options' => $candidatesOptionsfirst
            ];


            $mergedArrayaaa = array_merge([$resultfirst] ,$questions);




            $mergedArray = array_merge($mergedArrayaaa, [$resultbjp]);


        $candidatescng = $this->Question_model->get_cong_candidates($zoneid);

             // Define options with all candidates
            $candidatescongOptions = [];
            foreach ($candidatescng as $candidate) {
                $candidatescongOptions[] = [
                    'id' => $candidate->id,
                    'order' => '1',
                    'text' => $candidate->name .' ('.$candidate->party_name.')'
                ];
            }


            // Add "भाजपा से अन्य" and "नहीं जानते" options
$candidatescongOptions[] = [
    'id' => 'other', // You can choose a unique id here
    'order' => '1',
    'text' => 'राजद और उनके समर्थक दलों  से अन्य'
];

$candidatescongOptions[] = [
    'id' => 'unknown', // You can choose a unique id here
    'order' => '1',
    'text' => 'नहीं जानते'
];




            // Construct the final object
            $resultcongr = [
                'id' => '35',
                'name' => 'आपके अनुसार इस क्षेत्र से राजद और उनके समर्थक दलों का विधायक उम्मीदवार किसे होना चाहिए?',
                'options' => $candidatescongOptions
            ];


             $mergedArraynew = array_merge($mergedArray, [$resultcongr]);


             $candidatesothers = $this->Question_model->get_other_candidates($zoneid);


             // Define options with all candidates
            $candidatesothersOptions = [];
            foreach ($candidatesothers as $candidate) {
                $candidatesothersOptions[] = [
                    'id' => $candidate->id,
                    'order' => '1',
                      'text' => $candidate->name .' ('.$candidate->party_name.')'
                ];
            }
            
            
            // Add "भाजपा से अन्य" and "नहीं जानते" options
$candidatesothersOptions[] = [
    'id' => 'other', // You can choose a unique id here
    'order' => '1',
    'text' => 'अन्य'
];

$candidatesothersOptions[] = [
    'id' => '1', // You can choose a unique id here
    'order' => '1',
    'text' => 'नहीं जानते'
];


            // Construct the final object
            $resultothers = [
                'id' => '36',
                'name' => 'आपके अनुसार इस क्षेत्र से अन्य दलों का विधायक उम्मीदवार किसे होना चाहिए? ',
                'options' => $candidatesothersOptions
            ];


$mergedArraynewnew = array_merge($mergedArraynew, [$resultothers]);




            
            

 
            // Define options with all candidates
            $candidatesOptions = [];
            foreach ($candidates as $candidate) {
                $candidatesOptions[] = [
                    'id' => $candidate->id,
                    'order' => '1',
                    'text' => $candidate->name .' ('.$candidate->party_name.')'
                ];
            }


            // Add "भाजपा से अन्य" and "नहीं जानते" options
$candidatesOptions[] = [
    'id' => 'other', // You can choose a unique id here
    'order' => '1',
    'text' => 'अन्य'
];

$candidatesOptions[] = [
    'id' => '1', // You can choose a unique id here
    'order' => '1',
    'text' => 'नहीं जानते'
];




            // Construct the final object
            $result = [
                'id' => '37',
                'name' => 'इन सभी में से आप किसको अगले विधायक के रुप में देखना चाहते हैं?',
                'options' => $candidatesOptions
            ];

            $finalMergedArray = array_merge($mergedArraynewnew, [$result]);

            // Get additional questions
            $questionsFinal = $this->Question_model->get_questions_with_optionslast();
            $lastArray = array_merge($finalMergedArray, $questionsFinal);


            $q = $this->db->query(" SELECT election.name as appname FROM election, `zone` , tbl_users WHERE 
election.id=zone.election and 
tbl_users.zoneId =zone.id and tbl_users.userId ='$userid' ");
            $row = $q->row();
            if (!empty($row)) {
                $appname = $row->appname;
            }



            if (!empty($candidates)) {
                // Successful data sync
                $response = array(
                    'status' => true,
                    'message' => 'Data sync successfully',

                    'data' => array(
                        'app_name' => $appname,
                        'userDataCount' => $countdata ? $countdata->total_feedback : 0,
                        'questions' => $lastArray
                    )
                );
            } else {
                // No candidates found
                $response = array(
                    'status' => false,
                    'message' => 'No MLA found'
                );
            }
        }

        // Return the response in JSON format
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function syncAllDatacopy()
    {
        // Set validation rules
        $this->form_validation->set_rules('userId', 'User Id', 'required|integer');
        $this->form_validation->set_rules('zoneId', 'Zone Id', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails
            $response = array(
                'status' => false,
                'message' => validation_errors()
            );
        } else {
            // Get email and password from request
            $userid = $this->input->post('userId');
            $zoneid = $this->input->post('zoneId');

            // Check the user in the database
            $candidates = $this->Question_model->get_all_candidates($zoneid);
            $questions = $this->Question_model->get_questions_with_options();

            // Use Query Builder to avoid SQL injection
            $this->db->select('COUNT(*) AS total_feedback');
            $this->db->from('feedback');
            $this->db->where('qt_id', $userid);
            $this->db->group_by('qt_id');
            $query = $this->db->get();
            $countdata = $query->row();

            // Define gender options
            $options = [
                ['id' => '1', 'text' => 'नाम नहीं सुना', 'order' => '1'],
                ['id' => '2', 'text' => 'सिर्फ नाम सुना है- जानते नहीं हैं', 'order' => '2'],
                ['id' => '3', 'text' => 'जानते हैं, पर पसंद नहीं है', 'order' => '3'],
                ['id' => '4', 'text' => 'पसंद नहीं है- पर विधायक बनने लायक नहीं है', 'order' => '4'],
                ['id' => '5', 'text' => 'अगला विधायक बनने लायक है', 'order' => '5'],
                ['id' => '6', 'text' => 'मैं समर्थन करूंगा/करूंगी', 'order' => '6']
            ];

            // Initialize an array to hold the candidates with options
            $candidatesWithOptions = [];
            foreach ($candidates as $candidate) {
                $candidateData = [
                    'id' => $candidate->id,
                    'name' => 'विधायक संभावित उम्मीदवार ' . $candidate->name, // Concatenate the string with the candidate's name
                    'options' => $options
                ];
                $candidatesWithOptions[] = $candidateData;
            }

            $mergedArray = array_merge($questions, $candidatesWithOptions);

            // Define options with all candidates
            $candidatesOptions =  [
                ];
                
            foreach ($candidates as $candidate) {
                $candidatesOptions[] = [
                    'id' => $candidate->id,
                    'order' => '1',
                    'text' => 'विधायक संभावित उम्मीदवार ' . $candidate->name
                ];
            }

 
            // Construct the final object
            $result = [
                'id' => '1',
                'name' => 'इन सभी में से आप किसको अगले विधायक के रुप में देखना चाहते हैं?',
                'options' => $candidatesOptions
            ];

            $finalMergedArray = array_merge($mergedArray, [$result]);

            // Get additional questions
            $questionsFinal = $this->Question_model->get_questions_with_optionslast();
            $lastArray = array_merge($finalMergedArray, $questionsFinal);


            $q = $this->db->query(" SELECT election.name as appname FROM election, `zone` , tbl_users WHERE 
election.id=zone.election and 
tbl_users.zoneId =zone.id and tbl_users.userId ='$userid' ");
            $row = $q->row();
            if (!empty($row)) {
                $appname = $row->appname;
            }



            if (!empty($candidates)) {
                // Successful data sync
                $response = array(
                    'status' => true,
                    'message' => 'Data sync successfully',

                    'data' => array(
                        'app_name' => $appname,
                        'userDataCount' => $countdata ? $countdata->total_feedback : 0,
                        'questions' => $lastArray
                    )
                );
            } else {
                // No candidates found
                $response = array(
                    'status' => false,
                    'message' => 'No MLA found'
                );
            }
        }

        // Return the response in JSON format
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function submit_feedback()
    {
    // Set validation rules
    $this->form_validation->set_rules('userid', 'User Id', 'required');
    $this->form_validation->set_rules('userdata', 'userdata', 'required');
     if ($this->form_validation->run() == FALSE) {
        // If validation fails
        $response = array(
            'status' => false,
            'message' => validation_errors()
        );
    } else {
        $userdata = $this->input->post('userdata');
        $ccc = json_decode($userdata, true);
 
        $this->load->model('feedback_model');
        $qc_id = 0;
        $zc_id = 0;
        $electionName = 0;
        $zoneid = 0;
        $userid = $this->input->post('userid');
        $q = $this->db->query("SELECT * FROM `tbl_users` WHERE `userId`='$userid' ");
        $row = $q->row(); 
          
        if (!empty($row)) {
            $qc_id = $row->qc_id;
            $zc_id = $row->zc_id;
            $zoneid = $row->zoneId;

            $q1 = $this->db->query("SELECT election.id as electionName FROM `zone`, election WHERE election.id = zone.election AND zone.id = '$zoneid' ");
            $row1 = $q1->row();
            if (!empty($row1)) {
                $electionName = $row1->electionName;
            }
        }
   // Set the timezone
        date_default_timezone_set('Asia/Kolkata');
        $current_time = date('Y-m-d H:i:s');

        foreach ($ccc as $each_data) {
            $lat = $each_data['latitude'];
            $lng = $each_data['longitude'];
            $name = $each_data['name'];
            $phone = $each_data['phone'];
            $pic = $each_data['pic'];

            // Decode base64 image data
            $img_data = base64_decode($pic);
            $img_name = uniqid() . '.png';
            $img_path = './uploads/' . $img_name;

            // Save the image file
            file_put_contents($img_path, $img_data);

            $feedbackData = array(
                'electionid' => $electionName,
                'zoneid' => $zoneid,
                'name' => $name,
                'mobile' => $phone,
                'qc_id' => $qc_id,
                'zc_id' => $zc_id,
                'qt_id' => $userid, 
                'lat' => $lat,
                'lng' => $lng,
                'customer_file' => $img_name,
                // 'customer_file_data' => $pic,
                'created_at' => $current_time  

                // 'ans_string_1' => $ans_string_1,
            );

            foreach ($each_data['questionDataList'] as $ech_que_data) {
                $questionId = $ech_que_data['id']; // Get the correct question ID
                foreach ($ech_que_data['optionsDataArrayList'] as $each_que_data) {
                    if ($each_que_data['isSelected'] == true) {
                        $feedbackData["ans_string_$questionId"] = $each_que_data['text'];
                    }
                }
            }

            // print_r($feedbackData);
            $insertId = $this->feedback_model->insert_feedback($feedbackData);
        }

        if ($insertId) {
            // Successful insertion
            $response = array(
                'status' => true,
                'message' => 'Feedback submitted successfully',
                'data' => array('feedbackId' => $feedbackData)
            );
        } else {
            // Error inserting data
            $response = array(
                'status' => false,
                'message' => 'Failed to submit feedback'
            );
        }
    }

    // Return the response in JSON format
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}


}
