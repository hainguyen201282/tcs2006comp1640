<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

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
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function addNewConference()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('conference_model');

            $this->global['pageTitle'] = 'CodeInsect : Add New Conference';

            $this->loadViews("addNewConference", $this->global,NULL);
        }
    }

    function submitAddConference()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('appointmentTime','AppointmentTime','trim|required|max_length[128]');
            $this->form_validation->set_rules('location','Location','trim|required|max_length[200]');
            $this->form_validation->set_rules('topic','Topic','trim|required|max_length[50]');
            $this->form_validation->set_rules('type','Type','trim|required|max_length[10]');
            $this->form_validation->set_rules('cstatus','Cstatus','trim|required|max_length[50]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[200]');

            if($this->form_validation->run() == FALSE)
            {
                $this->addNewConference();
            }
            else
            {
                $appointmentTime = $this->input->post('appointmentTime');
                $location = $this->input->post('location');
                $topic = $this->input->post('topic');
                $type = $this->input->post('type');
                $cstatus = $this->input->post('cstatus');
                $description = $this->input->post('description');

                $conferenceInfo = array('appointmentTime'=>$appointmentTime, 'location'=>$location, 'topic'=> $topic, 'type'=>$type, 'cstatus'=>$cstatus,
                    'description'=>$description, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

                $this->load->model('conference_model');
                $result = $this->conference_model->submitAddConference($conferenceInfo);

                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Conference created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Conference creation failed');
                }

                redirect('addNewConference');
            }
        }
    }

    function conferenceListing()
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

            $count = $this->conference_model->conferenceListingCount($searchText);

            $returns = $this->paginationCompress ( "conferenceListing/", $count, 10 );

            $data['conferenceRecords'] = $this->conference_model->conferenceListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'CodeInsect : Conference Listing';

            $this->loadViews("conference", $this->global, $data, NULL);
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editOldConference($id = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('conferenceListing');
            }

            $this->global['pageTitle'] = 'CodeInsect : Edit Conference';

            $data['conferenceInfo'] = $this->conference_model->getConferenceInfo($id);

            $this->loadViews("editOldConference", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to edit the student information
     */
    function editConference()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');

            $id = $this->input->post('id');

            $this->form_validation->set_rules('appointmentTime','AppointmentTime','trim|required|max_length[128]');
            $this->form_validation->set_rules('location','Location','trim|required|max_length[200]');
            $this->form_validation->set_rules('topic','Topic','trim|required|max_length[50]');
            $this->form_validation->set_rules('type','Type','trim|required|max_length[10]');
            $this->form_validation->set_rules('cstatus','Cstatus','trim|required|max_length[50]');
            $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[200]');

            if($this->form_validation->run() == FALSE)
            {
                $this->editOldConference($id);
            }
            else
            {
                $appointmentTime = $this->input->post('appointmentTime');
                $location = $this->input->post('location');
                $topic = $this->input->post('topic');
                $type = $this->input->post('type');
                $cstatus = $this->input->post('cstatus');
                $description = $this->input->post('description');

                $ConferenceInfo = array();

                $conferenceInfo = array(
                    'appointmentTime'=>$appointmentTime,
                    'location'=>$location,
                    'topic'=> $topic,
                    'type'=>$type,
                    'cstatus'=>$cstatus,
                    'description'=>$description,
                    'updatedBy'=>$this->vendorId,
                    'updatedDtm'=>date('Y-m-d H:i:s'));

                $result = $this->conference_model->editConference($conferenceInfo, $id);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Conference updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Conference updated failed');
                }

                redirect('conferenceListing');
            }
        }
    }

    function deleteConference()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $conferenceInfo = array('cstatus'=>"Deactivated",'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

            $result = $this->conference_model->deleteConference($id, $conferenceInfo);

            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

}