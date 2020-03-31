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
    }

    public function index()
    {
        $this->global['pageTitle'] = 'CodeInsect : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function sendMessage()
    {
        $message = $this->input->post('message');
        $messageEntity = (object) array_merge($message, array('createdDate' => date('Y-m-d H:i:s')));

        $messageAttr = $this->input->post('messageAttr');

        // save message entity
        $result = $this->message_model->saveMessage($message, $messageAttr);

        // prepare data to send email
        $email = $this->input->post('email');
        $content = $messageEntity->content;

        if ($result != NULL && $result >= 0) {
            // call function send message
            $emailParams = [
                "email" => $email,
                'content' => $content,
            ];
            $this->send($emailParams);

            // return status success or failure
            echo(json_encode(array(
                'result' => true,
            )));
        } else {
            echo(json_encode(array(
                'result' => false,
            )));
        }
    }

    function send($emailParams)
    {
        $emailFullURL = FIREBASE_NOTIFICATION_EMAIL_URL . http_build_query($emailParams);

        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $emailFullURL);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $response = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
    }
}
