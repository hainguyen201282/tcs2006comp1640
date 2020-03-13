<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Student extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model');
        if ($this->uri->segments[1] != 'loginStudent') {
            $this->isStudentLoggedIn();
        }
        
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    /**
     * This function used to logged in student
     */
    public function loginStudent()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            
            $result = $this->student_model->loginStudent($email, $password);
            
            if(!empty($result))
            {
                $lastLogin = $this->student_model->lastLoginInfo($result->studentId);

                $sessionArray = array('userId'=> $result->studentId,                    
                                        'role'=> STUDENT,
                                        'roleText'=> 'Student',
                                        'name'=> $result->name,
                                        'lastLogin'=> isset($lastLogin->createdDtm) ? $lastLogin->createdDtm : date('Y-m-d H:i:s'),
                                        'isLoggedIn' => TRUE
                                );

                $this->session->set_userdata($sessionArray);

                unset($sessionArray['studentId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);

                $loginInfo = array("userId"=>$result->studentId, "sessionData" => json_encode($sessionArray), "machineIp"=>$_SERVER['REMOTE_ADDR'], "userAgent"=>getBrowserAgent(), "agentString"=>$this->agent->agent_string(), "platform"=>$this->agent->platform());

                $this->student_model->lastLogin($loginInfo);
                
                redirect('/dashboard');
            }
            else
            {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                
                redirect('loginMe');
            }
        }
    }

    function addNewStudent()
    {
        $this->load->model('student_model');

        $this->global['pageTitle'] = 'CodeInsect : Add New Student';

        $this->loadViews("addNewStudent", $this->global, NULL);
    }

    function submitAddStudent()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'email', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('name', 'name', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('mobile', 'mobile', 'trim|required|max_length[50]');
        //default value of student role Id is 4
//            $this->form_validation->set_rules('roleId','roleId','trim|required|max_length[11]');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('tutorId', 'tutorId', 'trim|required|max_length[11]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewStudent();
        } else {
            $email = $this->input->post('email');
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
//                $roleId = $this->input->post('roleId');
            $roleId = 4;
            $gender = $this->input->post('gender');
            $tutorId = $this->input->post('tutorId');

            $studentInfo = array('email' => $email,
                'name' => $name,
                'mobile' => $mobile,
                'roleId' => $roleId,
                'gender' => $gender,
                'tutorId' => $tutorId,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s'));

            $this->load->model('student_model');
            $result = $this->student_model->submitAddStudent($studentInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New Student created successfully');
            } else {
                $this->session->set_flashdata('error', 'Student creation failed');
            }

            redirect('addNewStudent');
        }
    }

    function studentListing()
    {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        $data['searchText'] = $searchText;

        $this->load->library('pagination');

        $count = $this->student_model->studentListingCount($searchText);

        $returns = $this->paginationCompress("studentListing/", $count, 10);

        $data['studentRecords'] = $this->student_model->studentListing($searchText, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'CodeInsect : Student Listing';

        $this->loadViews("student", $this->global, $data, NULL);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editOldStudent($studentDd = NULL)
    {
        if ($studentDd == null) {
            redirect('studentListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Edit Student';

        $data['studentInfo'] = $this->student_model->getStudentInfo($studentDd);

        $this->loadViews("editOldStudent", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the student information
     */
    function editStudent()
    {
        $this->load->library('form_validation');

        $studentId = $this->input->post('studentId');

            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            $this->form_validation->set_rules('gender','Gender','trim|required|max_length[10]');

            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($studentId);
            }
            else
            {

                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $gender = $this->input->post('gender');

                $studentInfo = array();

                if(empty($password))
                {
                    $studentInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                        'mobile'=>$mobile, 'gender'=>$gender, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $studentInfo = array(
                        'email'=>$email,
                        'password'=>getHashedPassword($password),
                        'roleId'=>$roleId,
                        'name'=>ucwords($name),
                        'mobile'=>$mobile,
                        'gender'=>$gender,
                        'updatedBy'=>$this->vendorId,
                        'updatedDtm'=>date('Y-m-d H:i:s')
                    );
                }

                $result = $this->student_model->editStudent($studentInfo, $studentId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Student updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Student updated failed');
                }

                redirect('studentListing');
            }

            redirect('studentListing');
        }
    }

    /**
     * This function is used to delete the student using studentId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteStudent()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $studentId = $this->input->post('studentId');
            $studentInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

            $result = $this->student_model->deleteStudent($studentId, $studentInfo);

            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $studentId : This is student id
     */
    function loginHistoy($studentId = NULL)
    {
        if ($studentDd == null) {
            redirect('studentListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Edit Student';

        $data['studentInfo'] = $this->student_model->getStudentInfo($studentDd);

        $this->loadViews("assignOldStudent", $this->global, $data, NULL);
    }

    function assignStudent()
    {
        $this->load->library('form_validation');

        $studentId = $this->input->post('studentId');

        $this->form_validation->set_rules('tutorId', 'tutorId', 'trim|required|max_length[11]');

        if ($this->form_validation->run() == FALSE) {
            $this->editOldStudent($studentId);
        } else {
            $email = $this->input->post('email');
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $roleId = $this->input->post('roleId');
            $gender = $this->input->post('gender');
            $tutorId = $this->input->post('tutorId');

            $StudentInfo = array();

            $studentInfo = array(
                'email' => $email,
                'name' => $name,
                'mobile' => $mobile,
                'roleId' => $roleId,
                'gender' => $gender,
                'tutorId' => $tutorId,
                'updatedBy' => $this->vendorId,
                'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->student_model->assignStudent($studentInfo, $studentId);

            if ($result == true) {
                $this->session->set_flashdata('success', 'Student updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Student updated failed');
            }

            redirect('studentListing');
        }
    }

//    function deleteStudent()
//    {
//        if($this->isAdmin() == TRUE)
//        {
//            echo(json_encode(array('status'=>'access')));
//        }
//        else
//        {
//            $id = $this->input->post('id');
//            $studentInfo = array('cstatus'=>"Deactivated",'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
//
//            $result = $this->student_model->deleteStudent($id, $studentInfo);
//
//            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
//            else { echo(json_encode(array('status'=>FALSE))); }
//        }
//    }

}

