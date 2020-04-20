<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

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

        if ($this->role == STUDENT) {
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        }

        if ($this->role == TUTOR) {
            $this->load->model('user_model');
            $tutorNotificationLogsInfo = $this->user_model->getTutorLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $tutorNotificationLogsInfo;
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

        if ($this->form_validation->run() == FALSE) {
            redirect('loginMe');
        } else {
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');

            $result = $this->student_model->loginStudent($email, $password);

            if (!empty($result)) {
                $lastLogin = $this->student_model->lastLoginInfo($result->studentId);

                $sessionArray = array('userId' => $result->studentId,
                    'imgAvatar' => $result->imgAvatar,
                    'role' => STUDENT,
                    'roleText' => 'Student',
                    'name' => $result->name,
                    'lastLogin' => isset($lastLogin->createdDtm) ? $lastLogin->createdDtm : date('Y-m-d H:i:s'),
                    'isLoggedIn' => TRUE
                );

                $this->session->set_userdata($sessionArray);

                unset($sessionArray['studentId'], $sessionArray['isLoggedIn'], $sessionArray['lastLogin']);

                $loginInfo = array("userId" => $result->studentId, "sessionData" => json_encode($sessionArray), "machineIp" => $_SERVER['REMOTE_ADDR'], "userAgent" => getBrowserAgent(), "agentString" => $this->agent->agent_string(), "platform" => $this->agent->platform());

                $this->student_model->lastLogin($loginInfo);

                redirect('/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                redirect('loginMe');
            }
        }
    }

    function importStudents(){
        $this->load->helper('string');
        $excelConfig = [
            'upload_path' => 'uploads/tmp/excels/',
            'allowed_types' => 'xlsx|xls',
            'max_size' => 20480000,
            'file_name' => random_string('alnum',15) . '_' . time(),
        ];

        $this->load->library('upload', $excelConfig);

        if ($this->upload->do_upload('uploadStudentData')) {
            $uploadedData = $this->upload->data();

            $excel_file = 'uploads/tmp/excels/' . $uploadedData['file_name'];

            //load the excel library
            $this->load->library('excel');

            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($excel_file);

            //get only the Cell Collection
            $objPHPExcel->setActiveSheetIndexByName('Student');
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

            //extract to a PHP readable array format
            foreach ($cell_collection as $cell) {
                $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                //header will/should be in row 1 only. of course this can be modified to suit your need.
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    $arr_data[$row][$column] = $data_value;
                }
            }

            //send the data in an array format
            $excelData['header'] = array_values($header);
            $excelData['values'] = array_values($arr_data);

            $studentData = $this->student_model->getAllStudents();
            $emailArr = [];
            foreach ($studentData as $key => $student) {
                $emailArr[] = $student->email;
            }

            $recordArr = [];
            foreach ($excelData['values'] as $data) {

                if (isset($data['B']) && $data['B'] != '' && !in_array($data['B'], $emailArr)) {
                    $recordArr[] = [
                        'email' => isset($data['B']) ? $data['B'] : '',
                        'password' => getHashedPassword('12345'), 
                        'name' => isset($data['C']) ? $data['C'] : '', 
                        'mobile' => isset($data['D']) ? $data['D'] : '', 
                        'address' => isset($data['E']) ? $data['E'] : '', 
                        'gender' => isset($data['F']) ? $data['F'] : '', 
                        'roleId' => STUDENT, 
                        'createdBy' => $this->vendorId, 
                        'createdDtm' => date('Y-m-d H:i:s')
                    ];
                }
            }

            $this->student_model->addBatchStudent($recordArr);

            redirect('studentListing');

        }
    }

    function exportStudents()
    {
        //load the excel library
        $this->load->library('excel');

        //read file from path
        $objPHPExcel = new PHPExcel();

        //get only the Cell Collection
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle("Student");
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $tableColumns = [
            'Student Id',
            'Email',
            'Name',
            'Mobile',
            'Address',
            'Gender',
        ];

        $column = 0;

        foreach ($tableColumns as $key => $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $studentData = $this->student_model->getAllStudents();

        $excelRow = 2;

        foreach ($studentData as $key => $student) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excelRow, $student->studentId);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excelRow, $student->email);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excelRow, $student->name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excelRow, $student->mobile);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excelRow, $student->address);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excelRow, $student->gender);

            $excelRow++;
        }

        $objectWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="student_list.xlsx"');
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        $objectWriter->save('php://output');
    }

    function addNewStudent()
    {
        $this->global['pageTitle'] = 'CodeInsect : Add New Student';

        $this->load->model('student_model');
        $data['tutors'] = $this->student_model->getAllTutors();

        $this->loadViews("addNewStudent", $this->global, $data, NULL);
    }

    public function validateForm()
    {
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|max_length[10]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|max_length[10]');
    }

    function submitAddStudent()
    {
        $this->load->library('form_validation');

        $this->validateForm();

        if ($this->form_validation->run() == FALSE) {
            $this->addNewStudent();
        } else {
            $email = $this->input->post('email');
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $password = $this->input->post('password');
            $gender = $this->input->post('gender');
            $tutorId = $this->input->post('tutor');

            $studentInfo = array('email' => $email,
                'name' => $name,
                'mobile' => $mobile,
                'password' => getHashedPassword($password),
                'roleId' => 4,
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
        $this->global['pageTitle'] = 'CodeInsect : Student Listing';
        $this->load->library('pagination');

        $data['studentRecords'] = array();

        if (AUTHORISED_STAFF == $this->role || STAFF == $this->role) {
            $data['studentRecords'] =
                $this->student_model->getAllStudents();
        } else if (TUTOR == $this->role) {
            $data['studentRecords'] =
                $this->student_model->getAllStudentsByTutorId($this->vendorId);
        }

        $this->loadViews("student", $this->global, $data, NULL);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editOldStudent($studentId = NULL)
    {
        if ($studentId == null) {
            redirect('studentListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Edit Student';

        $data['tutors'] = $this->student_model->getAllTutors();
        $data['studentInfo'] = $this->student_model->getStudentInfo($studentId);

        $this->loadViews("editOldStudent", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the student information
     */
    function editStudent()
    {
        $this->load->library('form_validation');

        $studentId = $this->input->post('studentId');
        
        $this->validateForm();

        if ($this->form_validation->run() == FALSE) {
            $this->editOldStudent($studentId);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('name'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $gender = $this->input->post('gender');
            $tutorId = $this->input->post('tutor');

            $studentInfo = array();

            if (empty($password)) {
                $studentInfo = array(
                    'email' => $email,
                    'name' => $name,
                    'mobile' => $mobile,
                    'gender' => $gender,
                    'tutorId' => $tutorId,
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s'));
            } else {
                $studentInfo = array(
                    'email' => $email,
                    'password' => getHashedPassword($password),
                    'roleId' => $roleId,
                    'name' => ucwords($name),
                    'mobile' => $mobile,
                    'gender' => $gender,
                    'tutorId' => $tutorId,
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
            }

            $oldStudentInfo = $this->student_model->getStudentInfo($studentId);
            $result = $this->student_model->editStudent($studentInfo, $studentId);

            $this->load->model('user_model');
            $tutorInfo = $this->user_model->getUserInfoWithRole($tutorId);

            if ($oldStudentInfo->tutorId != $tutorId) {
                $logStudentInfo = array(
                    'studentId' => $studentId,
                    'notification_text' => "You are just assigned to tutor " . $tutorInfo->name,
                    'createdBy' => $this->vendorId,
                    'createdDtm' => date('Y-m-d H:i:s')
                );

                $result = $this->student_model->submitAddStudentNotificationLog($logStudentInfo);

                $logTutorInfo = array(
                    'tutorId' => $tutorId,
                    'notification_text' => 'Students with IDs: ' . $studentId . ' are assigned to you',
                    'createdBy' => $this->vendorId,
                    'createdDtm' => date('Y-m-d H:i:s')
                );

                $result1 = $this->student_model->submitAddTutorNotificationLog($logTutorInfo);

                require APPPATH . '../vendor/autoload.php';

                $client = new Client(new Version2X(NOTIFICATION_ROOT_URL));

                $client->initialize();
                // send message to connected clients
                $messagePayload = [
                    'eventName' => 'assign_student_to_tutor',
                    'student_ids' => $studentId,
                    'tutor_id' => $tutorId
                ];

                $client->emit('send_notification', $messagePayload);
                $client->close();

                $emailList = [
                    [
                        "email" => $email,
                        'content' => "You are just assigned to tutor " . $tutorInfo->name,
                    ],
                    [
                        "email" => $tutorInfo->email,
                        'content' => 'Students with IDs: ' . $studentId . ' are assigned to you',
                    ],
                ];

                foreach ($emailList as $key => $emailParams) {
                    $emailFullURL = FIREBASE_NOTIFICATION_EMAIL_URL . http_build_query($emailParams);

                    $ch = curl_init();
                    // set url
                    curl_setopt($ch, CURLOPT_URL, $emailFullURL);
                    //return the transfer as a string
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    // $output contains the output string
                    $response = curl_exec($ch);
                    // close curl resource to free up system resources
                    curl_close($ch);
                }
            }

            if ($result == true) {
                $this->session->set_flashdata('success', 'Student updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Student updated failed');
            }
        }
        redirect('studentListing');
    }

    /**
     * This function is used to delete the student using studentId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteStudent()
    {
        $studentId = $this->input->post('studentId');
        $studentInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

        $result = $this->student_model->deleteStudent($studentId, $studentInfo);

        if ($result > 0) {
            echo(json_encode(array('status' => TRUE)));
        } else {
            echo(json_encode(array('status' => FALSE)));
        }
    }

    /**
     * This function used to show login history
     * @param number $studentId : This is student id
     */
    function loginHistoy($studentId = NULL)
    {
        if ($studentId == null) {
            redirect('studentListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Edit Student';

        $data['studentInfo'] = $this->student_model->getStudentInfo($studentId);

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

    function viewAssignTutor($active = "details")
    {
        $this->global['pageTitle'] = $active == "details" ? 'CodeInsect : Allocate' : 'CodeInsect : Reallocate';

        $data["active"] = $active;
        $data["tutors"] = $this->student_model->getAllTutors();
        $data['studentRecords'] = $this->student_model->getAllStudentFree();

        $this->loadViews("assignTutor", $this->global, $data, NULL);
    }

    function assignTutor()
    {
        $studentIds = $this->input->post('studentIds');
        $tutorId = $this->input->post('tutorId');

        $status = $this->updateTutorToStudent($studentIds, $tutorId);

        $this->load->model('user_model');
        $tutorInfo = $this->user_model->getUserInfoWithRole($tutorId);

        $notificationText = "You are just assigned to tutor " . $tutorInfo->name;
        foreach ($studentIds as $key => $studentId) {

            $studentInfo = $this->student_model->getStudentInfo($studentId);

            $logStudentInfo = array(
                'studentId' => $studentId,
                'notification_text' => $notificationText,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $result = $this->student_model->submitAddStudentNotificationLog($logStudentInfo);

            $emailParams = [
                "email" => $studentInfo->email,
                'content' => $notificationText,
            ];

            $emailFullURL = FIREBASE_NOTIFICATION_EMAIL_URL . http_build_query($emailParams);

            $ch = curl_init();
            // set url
            curl_setopt($ch, CURLOPT_URL, $emailFullURL);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $response = curl_exec($ch);
            // close curl resource to free up system resources
            curl_close($ch);
        }

        $logTutorInfo = array(
            'tutorId' => $tutorId,
            'notification_text' => 'Students (' . implode(",", $studentIds) . ') are assigned to you',
            'createdBy' => $this->vendorId,
            'createdDtm' => date('Y-m-d H:i:s')
        );

        $result1 = $this->student_model->submitAddTutorNotificationLog($logTutorInfo);

        $emailParams = [
            "email" => $tutorInfo->email,
            'content' => 'Students (' . implode(",", $studentIds) . ') are assigned to you',
        ];

        $emailFullURL = FIREBASE_NOTIFICATION_EMAIL_URL . http_build_query($emailParams);

        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $emailFullURL);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $response = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);

        require APPPATH . '../vendor/autoload.php';

        $client = new Client(new Version2X(NOTIFICATION_ROOT_URL));

        $client->initialize();
        // send message to connected clients
        $messagePayload = [
            'eventName' => 'assign_student_to_tutor',
            'student_ids' => implode(",", $studentIds),
            'tutor_id' => $tutorId
        ];

        $client->emit('send_notification', $messagePayload);
        $client->close();

        echo(json_encode(array(
            'status' => $status,
        )));
    }

    function getAllStudentByTutorId()
    {
        $tutorId = $this->input->post('tutorId');
        $result = $this->student_model->getAllStudentByTutorId($tutorId);

        echo(json_encode(array(
            'result' => $result,
        )));
    }

    function unassignTutor()
    {
        $studentIds = $this->input->post('studentIds');

        $status = $this->updateTutorToStudent($studentIds);
        echo(json_encode(array(
            'status' => $status,
        )));
    }

    function updateTutorToStudent($studentIds, $tutor = 0)
    {
        $result = array();
        foreach ($studentIds as $studentId) {

            $studentInfo = array(
                'tutorId' => $tutor,
                'updatedBy' => $this->vendorId,
                'updatedDtm' => date('Y-m-d H:i:s'));

            array_push($result, $this->student_model->assignStudent($studentInfo, $studentId));
        }
        if (sizeof($result) == sizeof($studentIds)) {
            return true;
        } else {
            return false;
        }
    }
}
