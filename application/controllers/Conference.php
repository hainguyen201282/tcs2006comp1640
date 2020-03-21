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

        if ($this->role == STUDENT) {
            $this->load->model('student_model');
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        }
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function addNewConference()
    {
        $this->load->model('conference_model');

        $this->global['pageTitle'] = 'CodeInsect : Add New Conference';

        $this->loadViews("addNewConference", $this->global,NULL);
    }

    function submitAddConference()
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

    function conferenceListing()
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

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function editOldConference($id = NULL)
    {
        if($id == null)
        {
            redirect('conferenceListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Edit Conference';

        $data['conferenceInfo'] = $this->conference_model->getConferenceInfo($id);

        $this->loadViews("editOldConference", $this->global, $data, NULL);
    }


    /**
     * This function is used to edit the student information
     */
    function editConference()
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

    function deleteOldConference($id = NULL)
    {
        if($id == null)
        {
            redirect('conferenceListing');
        }

        $this->global['pageTitle'] = 'CodeInsect : Delete Conference';

        $data['conferenceInfo'] = $this->conference_model->getConferenceInfo($id);

        $this->loadViews("deleteOldConference", $this->global, $data, NULL);
    }


    /**
     * This function is used to delete the student information
     */
    function deleteConference()
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
            $this->deleteOldConference($id);
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

            $result = $this->conference_model->deleteConference($conferenceInfo, $id);

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

    function calendar()
    {
        $this->load->view( 'calendar' );
    }

    function upload_ckeditor()
    {
        $uploadImageConfig = [
            'upload_path' => './uploads/',
            'allowed_types' => 'gif|jpg|png|jpeg|JPG|JPEG|GIF|PNG',
            'max_size' => 10240000,
            'max_width' => 20000,
            'max_height' => 20000,
        ];

        $this->load->library('upload', $uploadImageConfig);

        if ($this->upload->do_upload('upload')) {
            $fileData = $this->upload->data();
            echo json_encode(array('file_name' => $fileData['file_name']));
        } else {
            echo json_encode(array('error' => $this->upload->display_errors()));
        }
    }

    function file_browser(){
        $data['filelist'] = glob('uploads');

        $this->load->view('file_browser', $data);
    }

}