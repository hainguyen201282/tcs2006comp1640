<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();

        if ($this->role == STUDENT) {
            $this->load->model('student_model');
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);

            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        }

        if ($this->role == TUTOR) {
            $tutorNotificationLogsInfo = $this->user_model->getTutorLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $tutorNotificationLogsInfo;
        }
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $viewData = [];

        if (isset($this->role)) {
            switch ($this->role) {
                case AUTHORISED_STAFF:
                case ADMIN:
                    $this->roleText = "AUTHORISED STAFF";
                    if ($this->role == ADMIN) {
                        $this->roleText = "ADMIN";
                    }
                    $averageMessagesSentByTutor = $this->user_model->getAverageNumberMessageSentByTutor(null, null);
                    $averageMessagesSentToTutor = $this->user_model->getAverageNumberMessageSentToTutor(null, null);
                    foreach ($averageMessagesSentByTutor as $key => &$tutorMessageInfo) {
                        $tutorMessageInfo->avg_message_count_sent_to_tutor = $averageMessagesSentToTutor[$key]->avg_message_count_sent_to_tutor;
                    }

                    $numberOfMessageIn7Days = $this->user_model->getLastMessagesIn7Days();

                    $studentWithoutInteractionIn7Days = $this->user_model->getStudentsWithoutInteraction(7);
                    $studentWithoutInteractionIn28Days = $this->user_model->getStudentsWithoutInteraction(28);

                    $this->load->model('student_model');
                    $numberOfStudentWithoutTutor = $this->student_model->getStudentWithoutTutor();


                    $viewData['numberOfMessageIn7Days'] = $numberOfMessageIn7Days;
                    $viewData['numberOfStudentWithoutTutor'] = $numberOfStudentWithoutTutor;
                    $viewData['averageMessagesSentByTutor'] = $averageMessagesSentByTutor;
                    $viewData['studentWithoutInteractionIn7Days'] = count($studentWithoutInteractionIn7Days);
                    $viewData['studentWithoutInteractionIn28Days'] = count($studentWithoutInteractionIn28Days);

                    $this->global['pageTitle'] = 'Dashboard';
                    $this->loadViews("dashboardChiefStaff", $this->global, $viewData, NULL);
                    break;

                case TUTOR:
                    $this->tutorDashboard($this->vendorId);
                    break;

                case STUDENT:
                    $this->studentDashboard($this->vendorId);
                    break;
            }
        }

    }

    function tutorDashboard($tutorId)
    {
        $this->roleText = "TUTOR";
        $numberMessageStudentSentToTutor = $this->user_model->getNumberMessageStudentSentToTutor($tutorId);
        $numberMessageStudentReceivedFromTutor = $this->user_model->getNumberMessageStudentReceivedFromTutor($tutorId);

        foreach ($numberMessageStudentSentToTutor as $key => &$studentMessageInfo) {
            $studentMessageInfo->received_msg_count = $numberMessageStudentReceivedFromTutor[$key]->received_msg_count;
        }

        $viewData['numberMessageStudentSentToTutor'] = $numberMessageStudentSentToTutor;

        $this->loadViews("dashboardTutor", $this->global, $viewData, NULL);
    }

    function studentDashboard($studentId)
    {
        $this->roleText = "STUDENT";

        $this->load->model('student_model');
        $getMessagesYouReceivedFromTutor = $this->student_model->getMessagesYouReceivedFromTutor($studentId);
        $getMessagesYouSentToTutor = $this->student_model->getMessagesYouSentToTutor($studentId);

        $studentTutorMessages = array_merge($getMessagesYouReceivedFromTutor, $getMessagesYouSentToTutor);
        usort($studentTutorMessages, function ($a, $b) {
            return strcmp($a->createdDate, $b->createdDate);
        });

        $viewData['studentTutorMessages'] = $studentTutorMessages;

        $this->loadViews("dashboardStudent", $this->global, $viewData, NULL);
    }

    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        $this->load->library('pagination');

        $data['userRecords'] = $this->user_model->getAllUsers();

        $this->global['pageTitle'] = 'CodeInsect : User Listing';
        $this->loadViews("users", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        $this->load->model('user_model');
        $data['roles'] = $this->user_model->getUserRoles();

        $this->global['pageTitle'] = 'CodeInsect : Add New User';
        $this->loadViews("addNew", $this->global, $data, NULL);
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExistsNew($email);
        } else {
            $result = $this->user_model->checkEmailExistsNew($email, $userId);
        }

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    function importUsers()
    {
        $this->load->helper('string');
        $excelConfig = [
            'upload_path' => 'uploads/tmp/excels/',
            'allowed_types' => 'xlsx|xls',
            'max_size' => 20480000,
            'file_name' => random_string('alnum', 15) . '_' . time(),
        ];

        $this->load->library('upload', $excelConfig);

        if ($this->upload->do_upload('uploadUserData')) {
            $uploadedData = $this->upload->data();

            $excel_file = 'uploads/tmp/excels/' . $uploadedData['file_name'];

            //load the excel library
            $this->load->library('excel');

            //read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($excel_file);

            //get only the Cell Collection
            $objPHPExcel->setActiveSheetIndexByName('User');
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

            $userData = $this->user_model->getAllUsers();
            $emailArr = [];
            foreach ($userData as $key => $user) {
                $emailArr[] = $user->email;
            }

            $recordArr = [];
            foreach ($excelData['values'] as $data) {

                if (isset($data['B']) && $data['B'] != '' && !in_array($data['B'], $emailArr)) {

                    $roleId = 3;
                    switch ($data['F']) {
                        case 'Authorised Staff':
                            $roleId = 1;
                            break;

                        case 'Staff':
                            $roleId = 2;
                            break;

                        case 'Tutor':
                            $roleId = 3;
                            break;

                        case 'Student':
                            $roleId = 4;
                            break;
                    }
                    $recordArr[] = [
                        'email' => isset($data['B']) ? $data['B'] : '',
                        'password' => getHashedPassword('12345'),
                        'name' => isset($data['C']) ? $data['C'] : '',
                        'mobile' => isset($data['D']) ? $data['D'] : '',
                        'address' => isset($data['E']) ? $data['E'] : '',
                        'roleId' => $roleId,
                        'createdBy' => $this->vendorId,
                        'createdDtm' => date('Y-m-d H:i:s')
                    ];
                }
            }
            $this->user_model->addBatchUser($recordArr);

            redirect('userListing');
        }
    }

    function exportUsers()
    {
        //load the excel library
        $this->load->library('excel');

        //read file from path
        $objPHPExcel = new PHPExcel();

        //get only the Cell Collection
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle("User");
        // $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $tableColumns = [
            'User Id',
            'Email',
            'Name',
            'Mobile',
            'Address',
            'Role',
        ];

        $column = 0;

        foreach ($tableColumns as $key => $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $userData = $this->user_model->getAllUsers();

        $excelRow = 2;

        foreach ($userData as $key => $user) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excelRow, $user->userId);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excelRow, $user->email);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excelRow, $user->name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excelRow, $user->mobile);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excelRow, $user->address);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excelRow, $user->role);

            $excelRow++;
        }

        $objectWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="user_list.xlsx"');
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        $objectWriter->save('php://output');
    }

    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|max_length[10]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNew();
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $gender = $this->input->post('gender');

            $userInfo = array(
                'email' => $email,
                'password' => getHashedPassword($password),
                'roleId' => $roleId,
                'name' => $name,
                'gender' => $gender,
                'mobile' => $mobile,
                'createdBy' => $this->vendorId,
                'createdDtm' => date('Y-m-d H:i:s'));

            $this->load->model('user_model');
            $result = $this->user_model->addNewUser($userInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New User created successfully');
            } else {
                $this->session->set_flashdata('error', 'User creation failed');
            }
            redirect('userListing');
        }
    }


    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if ($userId == null) {
            redirect('userListing');
        }

        $data['roles'] = $this->user_model->getUserRoles();
        $data['userInfo'] = $this->user_model->getUserInfo($userId);

        $this->global['pageTitle'] = 'CodeInsect : Edit User';
        $this->loadViews("editOld", $this->global, $data, NULL);
    }


    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        $this->load->library('form_validation');

        $userId = $this->input->post('userId');

        $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|max_length[10]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->editOld($userId);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $roleId = $this->input->post('role');
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $gender = $this->input->post('gender');

            $userInfo = array();

            if (empty($password)) {
                $userInfo = array(
                    'email' => $email,
                    'roleId' => $roleId,
                    'name' => $name,
                    'gender' => $gender,
                    'mobile' => $mobile,
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s'));
            } else {
                $userInfo = array(
                    'email' => $email,
                    'password' => getHashedPassword($password),
                    'roleId' => $roleId,
                    'name' => ucwords($name),
                    'gender' => $gender,
                    'mobile' => $mobile,
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );
            }

            $result = $this->user_model->editUser($userInfo, $userId);
            if ($result == true) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'User updation failed');
            }
            redirect('userListing');
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return void $result : TRUE / FALSE
     */
    function deleteUser()
    {
        $userId = $this->input->post('userId');
        $userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

        $result = $this->user_model->deleteUser($userId, $userInfo);
        if ($result > 0) {
            echo(json_encode(array('status' => TRUE)));
        } else {
            echo(json_encode(array('status' => FALSE)));
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
     * @param number $userId : This is user id
     */
    function loginHistory($userId = NULL)
    {
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
        $this->loadViews("loginHistory", $this->global, $data, NULL);
    }

    /**
     * This function is used to show users profile
     * @param string $active
     */
    function profile($active = "details")
    {
        // get profile
        if ($this->session->userdata('role') == STUDENT) {

            $this->load->model('student_model');

            $data["userInfo"] = $this->student_model->getStudentProfile($this->vendorId);
            $data["userInfo"]->role = STUDENT;
        } else {
            $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        }

        $data["userInfo"]->roleText = '';
        if (isset($data["userInfo"]->role)) {

            switch ($data["userInfo"]->role) {
                case AUTHORISED_STAFF:
                    $data["userInfo"]->roleText = "AUTHORISED STAFF";
                    break;
                case ADMIN:
                    $data["userInfo"]->roleText = "ADMIN";
                    break;
                case TUTOR:
                    $data["userInfo"]->roleText = "TUTOR";
                    break;
                case STUDENT:
                    $data["userInfo"]->roleText = "STUDENT";
                    break;
            }
        }

        // get inbox
        $this->load->library('pagination');

        $messageInfo = array(
            'id' => $this->vendorId,
            'role' => $this->role,
        );
        $data["inboxRecords"] = $this->user_model->getInboxMessage($messageInfo);

        $data["sentRecords"] = $this->user_model->getSendMessage($messageInfo);

        $data["active"] = $active;

        $this->global['pageTitle'] = $active == "details" ? 'CodeInsect : My Profile' : 'CodeInsect : Change Password';
        $this->loadViews("profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param string $active : This is flag to set the active tab
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

            $userInfo = array(
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'updatedBy' => $this->vendorId,
                'updatedDtm' => date('Y-m-d H:i:s'));

            if ($this->session->userdata('role') == STUDENT) {
                $this->load->model('student_model');
                $result = $this->student_model->editStudent($userInfo, $this->vendorId);
            } else {
                $result = $this->user_model->editUser($userInfo, $this->vendorId);
            }

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
     * @param string $active : This is flag to set the active tab
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

            if ($this->session->userdata('role') == STUDENT) {
                $this->load->model('student_model');
                $resultPas = $this->student_model->matchOldPassword($this->vendorId, $oldPassword);
            } else {
                $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            }

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/' . $active);
            } else {
                $usersData = array(
                    'password' => getHashedPassword($newPassword),
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s'));

                if ($this->session->userdata('role') == STUDENT) {
                    $result = $this->student_model->changePassword($this->vendorId, $usersData);
                } else {
                    $result = $this->user_model->changePassword($this->vendorId, $usersData);
                }

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Password updation successful');
                } else {
                    $this->session->set_flashdata('error', 'Password updation failed');
                }
                redirect('profile/' . $active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     * @return bool
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExistsNew($email);
        } else {
            $result = $this->user_model->checkEmailExistsNew($email, $userId);
        }

        if (empty($result)) {
            $return = true;
        } else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }
        return $return;
    }

    function uploadAvatar($userId)
    {
        $uploadResult = $this->upload(AVATAR_PATH);

        $result = $this->user_model->uploadAvatar($uploadResult['filename'], $userId);
        if ($result > 0 && !empty($uploadResult['filename'])) {
            $this->session->set_flashdata('success', 'Upload image successfully');
        } else {
            $error = empty($uploadResult['error']) ? 'Upload image failure' : $uploadResult['error'];
            $this->session->set_flashdata('error', $error);
        }
        redirect('profile');
    }
}
?>
