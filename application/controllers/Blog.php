<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Blog extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('blog_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        
        $this->loadViews("blog", $this->global, NULL , NULL);
    }

    function addNewBlog()
    {
        $this->load->model('blog_model');
        
        $this->global['pageTitle'] = 'CodeInsect : Add New Blog';

        $this->loadViews("addNewBlog", $this->global, NULL, NULL);
    

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('fname','Blog Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('topic','Topic','trim|required|max_length[128]');
        $this->form_validation->set_rules('content','Content','trim|required|max_length[128]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->addNewBlog();
        }
        else
        {
            $fname = $this->input->post('fname');
            $topic = $this->input->post('topic');
            $content = $this->input->post('content');
            
            $blogInfo = array(
                'blogName'=>$fname, 
                'topic'=>$topic,
                'content'=> $content,
                'author'=>$this->vendorId,
                'updatedBy'=>$this->vendorId,
                'updatedBy'=>date('Y-m-d H:i:s'),
                'createdBy'=>date('Y-m-d H:i:s'));
                
            $this->load->model('blog_model');
            $result = $this->blog_model->addNewBlog($blogInfo);
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New blog created successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'blog creation failed');
            }
            redirect('blog');
        }
        
    }

    function submitNewBlog()
    {
        $this->load->model('blog_model');
        
        $this->global['pageTitle'] = 'CodeInsect : Add New Blog';

        $this->loadViews("addNewBlog", $this->global, NULL, NULL);
    

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('fname','Blog Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('topic','Topic','trim|required|max_length[128]');
        $this->form_validation->set_rules('content','Content','trim|required|max_length[128]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->addNewBlog();
        }
        else
        {
            $fname = $this->input->post('fname');
            $topic = $this->input->post('topic');
            $content = $this->input->post('content');
            
            $blogInfo = array(
                'blogName'=>$fname, 
                'topic'=>$topic,
                'content'=> $content,
                'author'=>$this->vendorId,
                'updatedBy'=>$this->vendorId,
                'updatedBy'=>date('Y-m-d H:i:s'),
                'createdBy'=>date('Y-m-d H:i:s'));
                
            $this->load->model('blog_model');
            $result = $this->blog_model->addNewBlog($blogInfo);
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New blog created successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'blog creation failed');
            }
            redirect('blog');
        }
    }
}
?>