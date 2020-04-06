<?php defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @version : 1.1
 */
class BaseController extends CI_Controller
{
    protected $role = '';
    protected $vendorId = '';
    protected $name = '';
    protected $roleText = '';
    protected $global = array();
    protected $lastLogin = '';

    /**
     * Takes mixed data and optionally a status code, then creates the response
     *
     * @access public
     * @param array|NULL $data
     *            Data to output to the user
     *            running the script; otherwise, exit
     */
    public function response($data = NULL)
    {
        $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit ();
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset ($isLoggedIn) || $isLoggedIn != TRUE) {
            redirect('login');
        } else {
            $this->globalSession();
        }

        if ($this->role && !in_array($this->role, $this->uri->roles)) {
            $this->loadThis();
        }
    }

    /**
     * This function used to check the user is logged in or not
     */
    function isStudentLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset ($isLoggedIn) || $isLoggedIn != TRUE) {
            redirect('loginStudent');
        } else {
            $this->globalSession();
        }

        if ($this->role && !in_array($this->role, $this->uri->roles)) {
            $this->loadThis();
        }
    }

    public function globalSession()
    {
        $this->role = $this->session->userdata('role');
        $this->vendorId = $this->session->userdata('userId');
        $this->name = $this->session->userdata('name');
        $this->roleText = $this->session->userdata('roleText');
        $this->lastLogin = $this->session->userdata('lastLogin');

        $this->global ['name'] = $this->name;
        $this->global ['vendorId'] = $this->vendorId;
        $this->global ['role'] = $this->role;
        $this->global ['role_text'] = $this->roleText;
        $this->global ['last_login'] = $this->lastLogin;
    }

    /**
     * This function is used to check the access
     */
    function isAdmin()
    {
        if ($this->role != AUTHORISED_STAFF) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to check the access
     */
    function isTicketter()
    {
        if ($this->role != AUTHORISED_STAFF || $this->role != STAFF) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to load the set of views
     */
    function loadThis()
    {
        $this->global ['pageTitle'] = 'CodeInsect : Access Denied';

        $this->load->view('includes/header', $this->global);
        $this->load->view('access');
        $this->load->view('includes/footer');
    }

    /**
     * This function is used to logged out user from system
     */
    function logout()
    {
        $this->session->sess_destroy();

        redirect('login');
    }

    /**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL)
    {
        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }

    /**
     * This function used provide the pagination resources
     * @param {string} $link : This is page link
     * @param {number} $count : This is page count
     * @param {number} $perPage : This is records per page limit
     * @return {mixed} $result : This is array of records and pagination data
     */
    function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT)
    {
        $this->load->library('pagination');

        $config ['base_url'] = base_url() . $link;
        $config ['total_rows'] = $count;
        $config ['uri_segment'] = $segment;
        $config ['per_page'] = $perPage;
        $config ['num_links'] = 5;
        $config ['full_tag_open'] = '<nav><ul class="pagination">';
        $config ['full_tag_close'] = '</ul></nav>';
        $config ['first_tag_open'] = '<li class="arrow">';
        $config ['first_link'] = 'First';
        $config ['first_tag_close'] = '</li>';
        $config ['prev_link'] = 'Previous';
        $config ['prev_tag_open'] = '<li class="arrow">';
        $config ['prev_tag_close'] = '</li>';
        $config ['next_link'] = 'Next';
        $config ['next_tag_open'] = '<li class="arrow">';
        $config ['next_tag_close'] = '</li>';
        $config ['cur_tag_open'] = '<li class="active"><a href="#">';
        $config ['cur_tag_close'] = '</a></li>';
        $config ['num_tag_open'] = '<li>';
        $config ['num_tag_close'] = '</li>';
        $config ['last_tag_open'] = '<li class="arrow">';
        $config ['last_link'] = 'Last';
        $config ['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = $config ['per_page'];
        $segment = $this->uri->segment($segment);

        return array(
            "page" => $page,
            "segment" => $segment
        );
    }

    function upload()
    {
        // set preference
        $config['upload_path'] = 'uploads/user_avatar';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1024;
        $config['max_width'] = 1920;
        $config['max_height'] = 1080;

        $this->load->library('upload', $config); //load a library for initializing the Upload class

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('userfile')) {
            return array('error' => $this->upload->display_errors());
        }
        $fileData = $this->upload->data(); // get data about the file
        return array(
            'filename' => $fileData['file_name'],
        );// get name file
    }
}
