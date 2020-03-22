<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Message extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
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

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

//    function addNewMessage()
//    {
//        $this->load->model('message_model');
//
//        $this->global['pageTitle'] = 'CodeInsect : Add New Message';
//
//        $this->loadViews("addNewMessage", $this->global, NULL);
//    }

//    function submitAddMessage()
//    {
//        $this->load->library('form_validation');
//
//        $this->form_validation->set_rules('receiverId', 'receiverId', 'trim|required|max_length[200]');
//        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[50]');
//        $this->form_validation->set_rules('messageContent', 'Message Content', 'trim|required|max_length[250]');
//
//        if ($this->form_validation->run() == FALSE) {
//            $this->addNewMessage();
//        } else {
//            $receiverId = $this->input->post('receiverId');
//            $subject = $this->input->post('subject');
//            $messageContent = $this->input->post('messageContent');
//
//            $messageInfo = array(
//                'receiverId' => $receiverId,
//                'subject' => $subject,
//                'messageContent' => $messageContent,
//                'createdBy' => $this->vendorId,
//                'createdDtm' => date('Y-m-d H:i:s'));
//
//            $this->load->model('message_model');
//            $result = $this->message_model->submitAddMessage($messageInfo);
//
//            if ($result > 0) {
//                $this->session->set_flashdata('success', 'New Message created successfully');
//            } else {
//                $this->session->set_flashdata('error', 'Message creation failed');
//            }
//
//            redirect('addNewMessage');
//        }
//
//    }

//    function messageListing()
//    {
//        $searchText = $this->security->xss_clean($this->input->post('searchText'));
//        $data['searchText'] = $searchText;
//
//        $this->load->library('pagination');
//
//        $count = $this->message_model->messageListingCount($searchText);
//
//        $returns = $this->paginationCompress("messageListing/", $count, 10);
//
//        $data['messageRecords'] = $this->message_model->messageListing($searchText, $returns["page"], $returns["segment"]);
//
//        $this->global['pageTitle'] = 'CodeInsect : Message Listing';
//
//        $this->loadViews("message", $this->global, $data, NULL);
//    }

//Update 18-03-2020, 19-03-2020
    function messageListing()
    {
        $this->global['pageTitle'] = 'CodeInsect : Student Listing';

        $this->load->library('pagination');

        $data['messageRecords1'] = array();
        $data['messageRecords2'] = array();

        if (TUTOR == $this->role) {
            $data['messageRecords1'] =
                $this->message_model->getMessageReceivedByTutor($this->vendorId);
            $data['messageRecords2'] =
                $this->message_model->getMessageSentByTutor($this->vendorId);
        } else {
            redirect(messageListing);
        }

        $this->loadViews("message", $this->global, $data, NULL);
    }

    function messageListingByStudent()
    {
        $this->global['pageTitle'] = 'CodeInsect : Student Listing';

        $this->load->library('pagination');

        $data['messageRecords1'] = array();
        $data['messageRecords2'] = array();

        if (STUDENT == $this->role) {
            $data['messageRecords1'] =
                $this->message_model->getMessageReceivedByStudent($this->vendorId);
            $data['messageRecords2'] =
                $this->message_model->getMessageSentByStudent($this->vendorId);
        } else {
            redirect(messageListing);
        }

        $this->loadViews("messageViewByStudent", $this->global, $data, NULL);
    }

    function submitAddMessage()
    {
        if (TUTOR == $this->role) {
            $this->load->library('form_validation');


            $this->form_validation->set_rules('receiver', 'Receiver', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('messageContent', 'Message Content', 'trim|required|max_length[250]');

            if ($this->form_validation->run() == FALSE) {
                $this->addNewMessage();
            } else {
                $receiverByStudentId = $this->input->post('receiver');
                $subject = $this->input->post('subject');
                $messageContent = $this->input->post('messageContent');

                $messageInfo = array(
                    'receiverByUserId' => NULL,
                    'receiverByStudentId' => $receiverByStudentId,
                    'subject' => $subject,
                    'messageContent' => $messageContent,
                    'senderByUserId' => $this->vendorId,
                    'senderByStudentId' => NULL,
                    'createdDtm' => date('Y-m-d H:i:s'));

                $this->load->model('message_model');
                $result = $this->message_model->submitAddMessage($messageInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New Message sent successfully');
                } else {
                    $this->session->set_flashdata('error', 'Message sent failed');
                }

                redirect('addNewMessage');
            }
        } else if (STUDENT == $this->role) {

            $this->load->library('form_validation');


            $this->form_validation->set_rules('receiver', 'Receiver', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('messageContent', 'Message Content', 'trim|required|max_length[250]');

            if ($this->form_validation->run() == FALSE) {
                $this->addNewMessage();
            } else {
                $receiverByUserId = $this->input->post('receiver');
                $subject = $this->input->post('subject');
                $messageContent = $this->input->post('messageContent');

                $messageInfo = array(
                    'receiverByUserId' => $receiverByUserId,
                    'receiverByStudentId' => NULL,
                    'subject' => $subject,
                    'messageContent' => $messageContent,
                    'senderByUserId' => NULL,
                    'senderByStudentId' => $this->vendorId,
                    'createdDtm' => date('Y-m-d H:i:s'));

                $this->load->model('message_model');
                $result = $this->message_model->submitAddMessage($messageInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New Message sent successfully');
                } else {
                    $this->session->set_flashdata('error', 'Message sent failed');
                }

                redirect('addNewMessage');
            }

        }
    }

//End of Update 18-03-2020

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function viewOldMessage($id = NULL)
    {
        if($id == null)
        {
            redirect('messageListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : View Message';

        $data['messageInfo'] = $this->message_model->getMessageInfo($id);

        $this->loadViews("viewOldMessage", $this->global, $data, NULL);
    }

    function viewMessage()
    {
        $id = $this->input->post('id');

        redirect('messageListing');
    }

    function addNewMessage()
    {
        $this->load->model('message_model');

        $this->global['pageTitle'] = 'CodeInsect : Add New Message';

        $this->loadViews("addNewMessage", $this->global, NULL);
    }


}

