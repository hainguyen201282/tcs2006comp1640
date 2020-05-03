<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

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
        $this->global['pageTitle'] = ' Dashboard';

        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = ' 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

    function sendMessage()
    {
        $message = $this->input->post('message');
        $messageEntity = (object) array_merge($message, array('createdDate' => date('Y-m-d H:i:s')));

        $messageAttr = $this->input->post('messageAttr');

        // save message entity
        $result = $this->message_model->saveMessage($messageEntity, $messageAttr);

        // prepare data to send email
        $email = $this->input->post('email');
        $content = $messageEntity->content;

        if ($result != NULL && $result >= 0) {

            $this->load->model('user_model');
            $this->load->model('student_model');
            $studentId = 0;
            $tutorId = 0;
            if ($message['senderRole'] == 4) {
                $studentId = $message['senderId'];
                $tutorId = $messageAttr['receiverId'];
            } else {
                $studentId = $messageAttr['receiverId'];
                $tutorId = $message['senderId'];
            }

            $tutorInfo = $this->user_model->getUserInfoWithRole($tutorId);
            $studentInfo = $this->student_model->getStudentProfile($studentId);

            $logStudentInfo = array(
                'studentId' => $studentId,
                'notification_text' => ($message['senderRole'] == 4) ? "You've just sent message to tutor " . $tutorInfo->name : "You've just received message from tutor " . $tutorInfo->name,
                'createdBy' => ($message['senderRole'] == 4) ? $studentId : $tutorId,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $result = $this->student_model->submitAddStudentNotificationLog($logStudentInfo);

            $logTutorInfo = array(
                'tutorId' => $tutorId,
                'notification_text' => ($message['senderRole'] == 4) ? "You've just received message from student " . $studentInfo->name : "You've just sent message to student " . $studentInfo->name,
                'createdBy' => ($message['senderRole'] == 4) ? $studentId : $tutorId ,
                'createdDtm' => date('Y-m-d H:i:s')
            );

            $result1 = $this->student_model->submitAddTutorNotificationLog($logTutorInfo);

            require APPPATH . '../vendor/autoload.php';

            $client = new Client(new Version2X(NOTIFICATION_ROOT_URL));

            $client->initialize();
            // send message to connected clients
            $messagePayload = [
                'eventName' => 'send_message',
                'student_ids' => $studentId,
                'student_name' => $studentInfo->name,
                'tutor_id' => $tutorId,
                'tutor_name' => $tutorInfo->name,
                'sent_by_student' => ($message['senderRole'] == 4) ? 1 : 0
            ];

            $client->emit('send_notification', $messagePayload);
            $client->close();

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
