<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

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

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    /**
     * This function used to load the first screen of the blog
     */
    function blogListing()
    {
        $this->load->library('pagination');

        $data['blogRecords'] = $this->blog_model->getAllBlog();

        $this->global['pageTitle'] = 'CodeInsect : Blog Listing';
        $this->loadViews("blog", $this->global, $data, NULL);
    }

    function addNewBlog()
    {
        $this->load->model('blog_model');
        $this->global['pageTitle'] = 'CodeInsect : Add New Blog';
        $this->loadViews("addNewBlog", $this->global, array('error' => ' '), NULL);
    }

    function submitNewBlog()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Blog Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('topic', 'Topic', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('content', 'Content', 'trim|required|max_length[650000]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewBlog();
        } else {

            $uploadResult = array();
            if (!empty($_FILES['userfile']['name'])) {
                $uploadResult = $this->upload(COVER_PATH);
            }

            $title = $this->input->post('title');
            $topic = $this->input->post('topic');
            $content = $this->input->post('content');

            $blogInfo = array(
                'title' => $title,
                'topic' => $topic,
                'content' => $content,
                'status' => PUBLISH,
                'author' => $this->vendorId,
                'role' => $this->role,
                'coverImg' => $uploadResult['filename'] == NULL ? 'cover.png' : $uploadResult['filename'],
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s'));

            $this->load->model('blog_model');
            $result = $this->blog_model->addNewBlog($blogInfo);

            if ($result > 0) {
                redirect('blogListing');
            } else if ($result <= 0) {
                redirect('addNewBlog');
                $this->session->set_flashdata('error', 'Blog creation failed');
            }
        }
    }

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

            $uploadResult = $this->upload(COVER_PATH);

            $title = $this->input->post('title');
            $content = $this->input->post('content');
            $coverImg = $this->input->post('coverImg');

            $blogInfo = array(
                'title' => $title,
                'content' => $content,
                'coverImg' => $uploadResult['filename'] == NULL ? $coverImg : $uploadResult['filename'],
                'updatedDate' => date('Y-m-d H:i:s')
            );

            $result = $this->blog_model->editBlog($id, $blogInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'Blog update successfully');
            } else if ($uploadResult['filename'] == NULL) {
                $error = empty($uploadResult['error']) ? 'Blog update failed' : $uploadResult['error'];
                $this->session->set_flashdata('error', $error);
            }
            redirect(array('editViewBlog', 'id' => $id));
        }
    }

    function deleteBlog()
    {
        $id = $this->input->post('blogId');

        $blogInfo = array(
            'status' => DELETE,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('blog_model');
        $result = $this->blog_model->deleteBlog($id, $blogInfo);

        if ($result > 0) {
            echo(json_encode(array('status' => true)));
        } else {
            echo(json_encode(array('status' => false)));
        }
    }

    function blogDetail($blogId)
    {
        $data['blogTopics'] = $this->blog_model->getAllTopic();

        $data['blogInfo'] = $this->blog_model->getBlogInfoById($blogId);
        $data['authorInfo'] = $this->getUserInfo($data['blogInfo']->role, $data['blogInfo']->author);

        $comments = $this->blog_model->getAllCommentByBlogId($blogId);
        foreach ($comments as $comment) {

            $userComment = $this->getUserInfo($comment->userRole, $comment->userId);

            $comment->name = $userComment->name;
            $comment->imgAvatar = ($userComment->imgAvatar == NULL) ? 'avatar.png' : $userComment->imgAvatar;
        }
        $data['blogComments'] = $comments;

        $this->global['pageTitle'] = 'CodeInsect : Blog Detail';
        $this->loadViews("blogDetail", $this->global, $data, NULL);
    }

    /**
     * @param $role
     * @param $userId
     * @return mixed
     */
    function getUserInfo($role, $userId)
    {
        switch ($role) {
            case STUDENT:
                $this->load->model('student_model');
                return $this->student_model->getStudentProfile($userId);
            case TUTOR:
                $this->load->model('user_model');
                return $this->user_model->getUserInfo($userId);
            default:
                return array(
                    'name' => 'Unknown',
                    'imgAvatar' => 'avatar.png',
                );
        }
    }

    function submitComment()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|max_length[650000]');

        $blogId = $this->input->post('blogId');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $message = $this->input->post('message');

            $commentInfo = array(
                'content' => $message,
                'status' => ACTIVATE,
                'userId' => $this->vendorId,
                'userRole' => $this->role,
                'blogId' => $blogId,
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s'));

            $this->load->model('blog_model');

            $result = $this->blog_model->addComment($commentInfo);

            if ($result <= 0) {
                $this->session->set_flashdata('error', 'Comment failed');
            }
        }
        redirect(array('blogDetail', 'blogId' => $blogId));
    }

    function deleteComment()
    {
        $id = $this->input->post('commentId');

        $commentInfo = array(
            'status' => DELETE,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('blog_model');
        $result = $this->blog_model->deleteComment($id, $commentInfo);

        if ($result > 0) {
            echo(json_encode(true));
        } else {
            echo(json_encode(false));
        }
    }

    function updateComment()
    {
        $id = $this->input->post('commentId');
        $content = $this->input->post('content');

        $commentInfo = array(
            'content' => $content,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('blog_model');
        $result = $this->blog_model->deleteComment($id, $commentInfo);

        if ($result > 0) {
            echo(json_encode(true));
        } else {
            echo(json_encode(false));
        }
    }
}

?>
