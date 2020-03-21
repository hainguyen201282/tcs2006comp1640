<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Blog extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('blog_model');
        $this->isLoggedIn();  

        if ($this->role == STUDENT) {
            $this->load->model('student_model');
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        } 
    }

     /**
     * This function used to load the first screen of the user
     */
    public function index() {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }

    /**
     * This function used to load the first screen of the blog
     */
    function blogListing() {
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
        $data['searchText'] = $searchText;

        $this->load->library('pagination');

        $count = $this->blog_model->blogListingCount($searchText);
        $returns = $this->paginationCompress("blogListing/", $count, 10);

        $data['blogRecords'] = $this->blog_model->blogListing($searchText, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'CodeInsect : Blog Listing';
        $this->loadViews("blog", $this->global, $data, NULL);
    }
    
    function addNewBlog() {
        $this->load->model('blog_model');
        $this->global['pageTitle'] = 'CodeInsect : Add New Blog';
        $this->loadViews("addNewBlog", $this->global, array('error' => ' ' ), NULL);
    }

    function submitNewBlog() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('title','Blog Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('topic','Topic','trim|required|max_length[128]');
        $this->form_validation->set_rules('content','Content','trim|required|max_length[650000]');
        
        if($this->form_validation->run() == FALSE) {
            $this->addNewBlog();
        }
        else {
            $title = $this->input->post('title');
            $topic = $this->input->post('topic');
            $content = $this->input->post('content');
            
            $blogInfo = array(
                'title' => $title, 
                'topic' => $topic,
                'content' => $content,
                'status' => 'PUBLISH',
                'authorId' => $this->vendorId,
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s'));

            $this->load->model('blog_model');
            $result = $this->blog_model->addNewBlog($blogInfo);

            if($result > 0 && $_FILES['theFile']['name'] !='') {
                $this->upload($result);
            } else if ($result <= 0) {
                $this->session->set_flashdata('error', 'blog creation failed'); 
            }
            redirect('blogListing');
        }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editViewBlog($id = NULL)
    {
        if ($id == null) {
            redirect('blogListing');
        }

        $data['blogInfo'] = $this->blog_model->getBlogInfoById($id);

        $this->global['pageTitle'] = 'CodeInsect : Edit Blog';

        $this->loadViews("editBlog", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the user information
     */
    function editBlog()
    {
        $this->load->library('form_validation');

        $id = $this->input->post('blogId');

        $this->form_validation->set_rules('title', 'Topic', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('content', 'Content', 'trim|required|max_length[65000]');

        if ($this->form_validation->run() == FALSE) {
            $this->editViewBlog($id);
        } else {
            $title = $this->input->post('title');
            $content = $this->input->post('content');

                $blogInfo = array(
                    'title' => $title,
                    'content' => $content,
                    'updatedDate' => date('Y-m-d H:i:s')
                );

            $result = $this->blog_model->editBlog($blogInfo, $id);

            if ($result == true) {
                $this->session->set_flashdata('success', 'Blog updated successfully');
            } else {
                $this->session->set_flashdata('error', 'Blog updation failed');
            }
            redirect('blogListing');
        }
    }

    function deleteBlog()
    {
        $id = $this->input->post('blogId');
        
        $blogInfo = array(
            'status' => 'DELETED', 
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $result = $this->blog_model->deleteBlog($id, $blogInfo);

        if ($result > 0) {
            echo(json_encode(array('status' => TRUE)));
        } else {
            echo(json_encode(array('status' => FALSE)));
        }
    }

    function upload($blogId) {
        // set preference 
        $config['upload_path']          = './assets/uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config); //load a library for initializing the Upload class

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
            return;
        }
        $fileData = $this->upload->data(); // get data about the file
        $fileName = $fileData['file_name']; // get name file

        $result = $this->blog_model->editCoverBlog($fileName, $blogId);
        if($result > 0) {
            redirect('blogListing');
        }
        $this->session->set_flashdata('error', 'upload image failed');
    }
}
?>