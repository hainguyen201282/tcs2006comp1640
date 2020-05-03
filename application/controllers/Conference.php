<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

class Conference extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('conference_model');
        $this->isLoggedIn();

        if ($this->role == STUDENT) {
            $this->load->model('student_model');
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        }

        if ($this->role == TUTOR) {
            $this->load->model('user_model');
            $tutorNotificationLogsInfo = $this->user_model->getTutorLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $tutorNotificationLogsInfo;
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = ' 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    public function index()
    {
        $this->global['pageTitle'] = ' Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function conferenceListing()
    {
        $this->load->library('pagination');

        $data['conferenceRecords'] = $this->conference_model->conferenceListing($this->vendorId, $this->role);

        $this->global['pageTitle'] = ' Meeting Listing';
        $this->loadViews("conference", $this->global, $data, NULL);
    }

    function addNewConference()
    {
        $this->global['pageTitle'] = ' Add New Meeting';
        $this->loadViews("addNewConference", $this->global, NULL);
    }

    function getAvailableTime()
    {
        $result = array();

        $appDate = $this->input->post('appDate');

        $this->load->model('conference_model');
        $data = $this->conference_model->getAvailableTimeByDate($appDate);
        foreach ($data as $item) {
            $time = explode(" ", $item->appTime);
            array_push($result, $time[1]);
        }
        echo(json_encode(array('result' => $result)));
    }

    function check_default($post_string)
    {
        return $post_string == '0' ? FALSE : TRUE;
    }

    function submitNewConference()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('appDate', 'Appointment Date', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('appTime', 'Time', 'required|callback_check_default');
        $this->form_validation->set_message('check_default', 'You need to select Time other than the default');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('topic', 'Topic', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('location', 'Location', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|max_length[10]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewConference();
        } else {
            $appDate = $this->input->post('appDate');
            $appTime = $this->input->post('appTime');
            $title = $this->input->post('title');
            $location = $this->input->post('location');
            $topic = $this->input->post('topic');
            $type = $this->input->post('type');
            $description = $this->input->post('desc');

            $conferenceInfo = array('appTime' => $appDate . ' ' . $appTime,
                'location' => $location,
                'title' => $title,
                'topic' => $topic,
                'type' => $type,
                'description' => $description,
                'status' => ACTIVATE,
                'host' => $this->vendorId,
                'role' => $this->role,
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s'));

            $this->load->model('conference_model');
            $result = $this->conference_model->addConference($conferenceInfo);

            $attenderInfo = array(
                'userId' => $this->vendorId,
                'userRole' => $this->role,
                'conferenceId' => $result
            );
            $result = $this->conference_model->addAttender($attenderInfo);

            if ($result > 0) {
                redirect('conferenceListing');
            } else {
                $this->session->set_flashdata('error', 'Meeting creation failed');
                redirect('addNewConference');
            }
        }
    }

    function editConferenceView($id = NULL)
    {
        if ($id == null) {
            redirect('conferenceListing');
        }

        $data['conferenceInfo'] = $this->conference_model->getConferenceInfoById($id);

        if ($this->vendorId != $data['conferenceInfo']->host || $this->role != $data['conferenceInfo']->role) {
            $this->loadThis();
        }

        $attenderRecords = $this->conference_model->getAllAttenderByConferenceId($id, $this->vendorId, STUDENT);

        $tutorRecords = $this->conference_model->getAllAttenderByConferenceId($id, $this->vendorId, TUTOR);

        $attenderRecords = array_merge($tutorRecords, $attenderRecords);

        $data['attenderRecords'] = $attenderRecords;

        $this->global['pageTitle'] = ' Edit Meeting';
        $this->loadViews("editConferenceView", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the Conference information
     */
    function editConference()
    {
        $id = $this->input->post('id');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('location', 'Location', 'trim|required|max_length[256]');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[65000]');

        if ($this->form_validation->run() == FALSE) {
            $this->editConferenceView($id);
        } else {
            $title = $this->input->post('title');
            $location = $this->input->post('location');
            $description = $this->input->post('description');

            $conferenceInfo = array(
                'title' => $title,
                'location' => $location,
                'description' => $description,
                'updatedDate' => date('Y-m-d H:i:s'));

            $this->load->model('conference_model');
            $result = $this->conference_model->updateConference($id, $conferenceInfo);

            if ($result == true) {
                redirect('conferenceListing');
            } else {
                $this->session->set_flashdata('error', 'Meeting updated failed');
                $this->editConferenceView($id);
            }
        }
    }

    function deleteConference()
    {
        $id = $this->input->post('id');

        $conferenceInfo = array(
            'status' => DEACTIVATE,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('conference_model');
        $result = $this->conference_model->deleteConference($id, $conferenceInfo);

        if ($result > 0) {
            echo(json_encode(array('status' => true)));
        } else {
            echo(json_encode(array('status' => false)));
        }
    }

    function searchUser()
    {
        $searchText = $this->input->post('search');
        $conferenceId = $this->input->post('conferenceId');

        // get student data
        $this->load->model('student_model');
        $students = $this->student_model->getStudentAvailableForConference($searchText, $conferenceId);
        echo json_encode($students);
    }

    function addAttender()
    {
        $userId = $this->input->post('userId');
        $conferenceId = $this->input->post('id');

        $attenderInfo = array(
            'userId' => $userId,
            'userRole' => STUDENT,
            'conferenceId' => $conferenceId
        );

        $this->load->model('conference_model');
        $result = $this->conference_model->addAttender($attenderInfo);

        $this->load->model('student_model');
        $logStudentInfo = array(
            'studentId' => $userId,
            'notification_text' => "You are just invited to a conference by " . $this->name,
            'createdBy' => $this->vendorId,
            'createdDtm' => date('Y-m-d H:i:s')
        );

        $result1 = $this->student_model->submitAddStudentNotificationLog($logStudentInfo);

        require APPPATH . '../vendor/autoload.php';

        $client = new Client(new Version2X(NOTIFICATION_ROOT_URL));

        $client->initialize();
        // send message to connected clients
        $messagePayload = [
            'eventName' => 'invite_student_to_conference',
            'student_ids' => $userId,
            'sender_id' => $this->vendorId,
            'sender_role' => $this->role,
            'sender_name' => $this->name,
        ];

        $client->emit('send_notification', $messagePayload);
        $client->close();

        if ($result > 0) {
            echo(json_encode(['attendRecordId' => $result]));
        } else {
            echo(json_encode(false));
        }
    }

    function deleteAttender()
    {
        $attendId = $this->input->post('attendId');

        $attenderInfo = $this->conference_model->getConferenceAttenderInfoById($attendId);

        $this->load->model('conference_model');
        $this->conference_model->deleteAttender($attendId);

        $this->load->model('student_model');
        $logStudentInfo = array(
            'studentId' => $attenderInfo->userId,
            'notification_text' => "Sorry, you've left a conference organized by " . $this->name,
            'createdBy' => $this->vendorId,
            'createdDtm' => date('Y-m-d H:i:s')
        );

        $result1 = $this->student_model->submitAddStudentNotificationLog($logStudentInfo);

        require APPPATH . '../vendor/autoload.php';

        $client = new Client(new Version2X(NOTIFICATION_ROOT_URL));

        $client->initialize();
        // send message to connected clients
        $messagePayload = [
            'eventName' => 'student_leave_conference',
            'student_ids' => $attenderInfo->userId,
            'sender_id' => $this->vendorId,
            'sender_role' => $this->role,
            'sender_name' => $this->name,
        ];

        $client->emit('send_notification', $messagePayload);
        $client->close();

        echo(json_encode(true));
    }
}
