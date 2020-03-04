<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Student (StudentController)
 * Student Class to control all student related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Student extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the student
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }

    /**
     * This function is used to load the student list
     */
    function studentListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->student_model->studentListingCount($searchText);

            $returns = $this->paginationCompress ( "studentListing/", $count, 10 );

            $data['studentRecords'] = $this->student_model->studentListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'CodeInsect : Student Listing';

            $this->loadViews("students", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('student_model');
            $data['roles'] = $this->student_model->getStudentRoles();

            $this->global['pageTitle'] = 'CodeInsect : Add New Student';

            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $studentId = $this->input->post("studentId");
        $email = $this->input->post("email");

        if(empty($studentId)){
            $result = $this->student_model->checkEmailExists($email);
        } else {
            $result = $this->student_model->checkEmailExists($email, $studentId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }

    /**
     * This function is used to add new student to the system
     */
    function addNewStudent()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');

            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));

                $studentInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId, 'name'=> $name,
                    'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

                $this->load->model('student_model');
                $result = $this->student_model->addNewStudent($studentInfo);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Student created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Student creation failed');
                }

                redirect('addNew');
            }
        }
    }


    /**
     * This function is used load student edit information
     * @param number $studentId : Optional : This is student id
     */
    function editOld($studentId = NULL)
    {
        if($this->isAdmin() == TRUE || $studentId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($studentId == null)
            {
                redirect('studentListing');
            }

            $data['roles'] = $this->student_model->getStudentRoles();
            $data['studentInfo'] = $this->student_model->getStudentInfo($studentId);

            $this->global['pageTitle'] = 'CodeInsect : Edit Student';

            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to edit the student information
     */
    function editStudent()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $studentId = $this->input->post('studentId');

            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');

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

                $studentInfo = array();

                if(empty($password))
                {
                    $studentInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                        'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $studentInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId,
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }

                $result = $this->student_model->editStudent($studentInfo, $studentId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Student updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Student updation failed');
                }

                redirect('studentListing');
            }
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
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $studentId = ($studentId == NULL ? 0 : $studentId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["studentInfo"] = $this->student_model->getStudentInfoById($studentId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;

            $this->load->library('pagination');

            $count = $this->student_model->loginHistoryCount($studentId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$studentId."/", $count, 10, 3);

            $data['studentRecords'] = $this->student_model->loginHistory($studentId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'CodeInsect : Student Login History';

            $this->loadViews("loginHistory", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to show students profile
     */
    function profile($active = "details")
    {
        $data["studentInfo"] = $this->student_model->getStudentInfoWithRole($this->vendorId);
        $data["active"] = $active;

        $this->global['pageTitle'] = $active == "details" ? 'CodeInsect : My Profile' : 'CodeInsect : Change Password';
        $this->loadViews("profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the student details
     * @param text $active : This is flag to set the active tab
     */
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');

        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));

            $studentInfo = array('name'=>$name, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

            $result = $this->student_model->editStudent($studentInfo, $this->vendorId);

            if($result == true)
            {
                $this->session->set_studentdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the student
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');

        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');

            $resultPas = $this->student_model->matchOldPassword($this->vendorId, $oldPassword);

            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/'.$active);
            }
            else
            {
                $studentsData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                    'updatedDtm'=>date('Y-m-d H:i:s'));

                $result = $this->student_model->changePassword($this->vendorId, $studentsData);

                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }

                redirect('profile/'.$active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is students email
     */
    function emailExists($email)
    {
        $studentId = $this->vendorId;
        $return = false;

        if(empty($studentId)){
            $result = $this->student_model->checkEmailExists($email);
        } else {
            $result = $this->student_model->checkEmailExists($email, $studentId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }
}

?>

