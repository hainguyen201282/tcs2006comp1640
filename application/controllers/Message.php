<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

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
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function addNewMessage()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('message_model');

            $this->global['pageTitle'] = 'CodeInsect : Add New Message';

            $this->loadViews("addNewMessage", $this->global,NULL);
        }
    }

    function submitAddMessage()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('receiverId','receiverId','trim|required|max_length[11]');
            $this->form_validation->set_rules('subject','subject','trim|required|max_length[200]');
            $this->form_validation->set_rules('messageStatus','messageStatus','trim|required|max_length[50]');
            $this->form_validation->set_rules('messageContent','messageContent','trim|required|max_length[200]');

            if($this->form_validation->run() == FALSE)
            {
                $this->addNewMessage();
            }
            else
            {
                $receiverId = $this->input->post('receiverId');
                $subject = $this->input->post('subject');
                $messageStatus = $this->input->post('messageStatus');
                $messageContent = $this->input->post('messageContent');

                $messageInfo = array(
                    'receiverId'=>$receiverId,
                    'subject'=>$subject,
                    'messageStatus'=> $messageStatus,
                    'messageContent'=>$messageContent,
                    'createdBy'=>$this->vendorId,
                    'createdDtm'=>date('Y-m-d H:i:s'));

                $this->load->model('message_model');
                $result = $this->message_model->submitAddMessage($messageInfo);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Message created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Message creation failed');
                }

                redirect('addNewMessage');
            }
        }
    }

    function messageListing()
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

            $count = $this->message_model->messageListingCount($searchText);

            $returns = $this->paginationCompress ( "messageListing/", $count, 10 );

            $data['messageRecords'] = $this->message_model->messageListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'CodeInsect : Message Listing';

            $this->loadViews("message", $this->global, $data, NULL);
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editOldMessage($id = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('messageListing');
            }

            $this->global['pageTitle'] = 'CodeInsect : View Message';

            $data['messageInfo'] = $this->message_model->getMessageInfo($id);

            $this->loadViews("editOldMessage", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to view message information
     */

    function editmessage()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
//            $this->load->library('form_validation');

            $id = $this->input->get('id');
//
//            $this->form_validation->set_rules('receiverId','receiverId','trim|required|max_length[11]');
//            $this->form_validation->set_rules('subject','subject','trim|required|max_length[200]');
//            $this->form_validation->set_rules('messageStatus','messageStatus','trim|required|max_length[50]');
//            $this->form_validation->set_rules('messageContent','messageContent','trim|required|max_length[200]');
//
//            if($this->form_validation->run() == FALSE)
//            {
//                $this->editOldmessage($id);
//            }
//            else
//            {
//                $receiverId = $this->input->get('receiverId');
//                $subject = $this->input->get('subject');
//                $messageStatus = $this->input->get('messageStatus');
//                $messageContent = $this->input->get('messageContent');
//
//                $messageInfo = array();
//
//                $messageInfo = array(
//                    'receiverId'=>$receiverId,
//                    'subject'=>$subject,
//                    'messageStatus'=> $messageStatus,
//                    'messageContent'=>$messageContent);
//
//                $result = $this->message_model->editmessage($messageInfo, $id);
//
//                if($result == true)
//                {
//                    $this->session->set_flashdata('success', 'message updated successfully');
//                }
//                else
//                {
//                    $this->session->set_flashdata('error', 'message updated failed');
//                }

                redirect('messageListing');
//            }
        }
    }




}

