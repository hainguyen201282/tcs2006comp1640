<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends CI_Model
{

    /**
     * This function used to check the login credentials of the student
     * @param string $email : This is email of the student
     * @param string $password : This is encrypted password of the student
     */
    function loginStudent($email, $password)
    {
        $this->db->select('stuTbl.*');
        $this->db->from('tbl_student as stuTbl');
        $this->db->where('stuTbl.email', $email);
        $this->db->where('stuTbl.isDeleted', 0);
        $query = $this->db->get();

        $student = $query->row();

        if (!empty($student)) {
            if (verifyHashedPassword($password, $student->password)) {
                return $student;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to get last login info by user id
     * @param number $userId : This is user id
     * @return number $result : This is query result
     */
    function lastLoginInfo($userId)
    {
        $this->db->select('BaseTbl.createdDtm');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tbl_student_last_login as BaseTbl');

        return $query->row();
    }

    /**
     * This function used to save login information of user
     * @param array $loginInfo : This is users login information
     */
    function lastLogin($loginInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_student_last_login', $loginInfo);
        $this->db->trans_complete();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getStudentProfile($studentId)
    {
        $this->db->select('StudentTbl.studentId as userId, 
            StudentTbl.email, 
            StudentTbl.name, 
            StudentTbl.gender, 
            StudentTbl.address, 
            StudentTbl.mobile, 
            StudentTbl.description, 
            StudentTbl.imgAvatar, 
            StudentTbl.roleId, 
            StudentTbl.tutorId, 
            StudentTbl.updatedDtm,
            StudentTbl.createdDtm,
            TutorTbl.roleId as TutorRoleId, 
            TutorTbl.name as tutorName, 
            TutorTbl.email as tutorEmail'
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'StudentTbl.tutorId = TutorTbl.userId', 'left');
        $this->db->where('StudentTbl.isDeleted', 0);
        $this->db->where('StudentTbl.studentId', $studentId);
        $this->db->order_by('StudentTbl.studentId', 'ASC');

        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getStudentLogs($studentId)
    {
        $this->db->select('*');
        $this->db->from('tbl_student_notification_log as BaseTbl');
        $this->db->where('BaseTbl.studentId', $studentId);
        $this->db->where('BaseTbl.is_read', 0);
        $this->db->order_by('BaseTbl.createdDtm DESC');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('studentId, password');
        $this->db->where('studentId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_student');

        $user = $query->result();

        if (!empty($user)) {
            if (verifyHashedPassword($oldPassword, $user[0]->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('studentId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_student', $userInfo);

        return $this->db->affected_rows();
    }

    function submitAddStudent($studentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_student', $studentInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function addBatchStudent($studentData){
        if ($studentData) {
            $this->db
                ->insert_batch('tbl_student', $studentData);
            return true;
        } else {
            return false;
        }
    }

    function submitAddStudentNotificationLog($logInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_student_notification_log', $logInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function submitAddTutorNotificationLog($logInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_tutor_notification_log', $logInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function editStudent($studentInfo, $studentId)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_student', $studentInfo);
        return TRUE;
    }

    function getStudentInfo($studentId)
    {
        $this->db->select('StudentTbl.studentId, 
            StudentTbl.email, 
            StudentTbl.name,
            StudentTbl.gender, 
            StudentTbl.mobile, 
            StudentTbl.address, 
            StudentTbl.roleId, 
            StudentTbl.tutorId, 
            StudentTbl.updatedDtm, 
            StudentTbl.createdDtm, 
            TutorTbl.name as tutorName,'
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = StudentTbl.tutorId', 'left');
        $this->db->where('StudentTbl.studentId', $studentId);
        $query = $this->db->get();

        return $query->row();
    }


    function deleteStudent($studentId, $studentInfo)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_student', $studentInfo);
        return $this->db->affected_rows();
    }

    function assignStudent($studentInfo, $studentId)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_student', $studentInfo);
        return TRUE;
    }

    function getAllTutors()
    {
        $this->db->select('BaseTbl.userId, BaseTbl.name');
        $this->db->from('tbl_users as BaseTbl');
        $likeCriteria = "(BaseTbl.roleId = 3 AND BaseTbl.isDeleted = 0)";
        $this->db->where($likeCriteria);
        $query = $this->db->get();

        return $query->result();
    }

    function getAllStudents()
    {
        $this->db->select(
            'StudentTbl.*,
            TutorTbl.name as tutorName, '
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'StudentTbl.tutorId = TutorTbl.userId', 'left');
        $this->db->where('StudentTbl.isDeleted', 0);
        $this->db->order_by('StudentTbl.studentId', 'ASC');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getAllStudentsByTutorId($tutorId)
    {
        $this->db->select(
            'StudentTbl.studentId, StudentTbl.email, StudentTbl.name, StudentTbl.mobile, StudentTbl.gender, StudentTbl.createdDtm,
            StudentTbl.tutorId, StudentTbl.roleId, TutorTbl.name as tutorName'
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = StudentTbl.tutorId', 'left');
        $this->db->where('TutorTbl.userId', $tutorId);
        $this->db->where('StudentTbl.isDeleted', 0);
        $this->db->order_by('StudentTbl.studentId', 'ASC');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getAllStudentFree()
    {
        $this->db->select(
            'StudentTbl.studentId, StudentTbl.email, StudentTbl.name, '
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->where('StudentTbl.tutorId', 0);
        $this->db->order_by('StudentTbl.studentId', 'ASC');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getAllStudentByTutorId($tutorId) {
        $this->db->select(
            'StudentTbl.studentId, StudentTbl.email, StudentTbl.name, '
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->where('StudentTbl.tutorId', $tutorId);
        $this->db->order_by('StudentTbl.studentId', 'ASC');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getStudentWithoutTutor(){
        $this->db->select("StudentTbl.*");
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = StudentTbl.tutorId', 'left');
        $this->db->where("(TutorTbl.userId IS NULL OR StudentTbl.tutorId = 0)");
        $query = $this->db->get();
        // echo "<PRE>" . print_r($this->db->last_query(), true) . "</PRE>";
        return $query->num_rows();
    }

    function getMessagesYouReceivedFromTutor($studentId = 1){
                $query = <<<EOT
SELECT `student`.`name` as student_name, `user`.`name` as tutor_name, `msg`.`createdDate`, `msg`.`content`, `msg`.`subject`, 0 as studentSender, `student`.`imgAvatar` as studentAvatar, `user`.`imgAvatar` as tutorAvatar FROM
`tbl_student` as student
LEFT JOIN `tbl_users` as user ON (`user`.`userId` = `student`.`tutorId` AND `user`.`roleId` = 3)
LEFT JOIN `tbl_message_attr` as msg_attr ON (`student`.`studentId` = `msg_attr`.`receiverId` AND `msg_attr`.`receiverRole` = 4)
LEFT JOIN `tbl_message` as msg ON (`msg`.`id` = `msg_attr`.`messageId` AND `msg`.`senderId` = `user`.`userId` AND `msg`.`senderRole` = 3)
WHERE `student`.`isDeleted` = 0 AND `student`.`studentId` = {$studentId} AND `msg`.`id` IS NOT NULL
ORDER BY `msg`.`createdDate` ASC
EOT;
        $queryResult = $this->db
            ->query($query);
            
        return $queryResult->result();
    }

    function getMessagesYouSentToTutor($studentId = 1){
                $query = <<<EOT
SELECT `student`.`name` as student_name, `user`.`name` as tutor_name, `msg`.`createdDate`, `msg`.`content`, `msg`.`subject`, 1 as studentSender, `student`.`imgAvatar` as studentAvatar, `user`.`imgAvatar` as tutorAvatar FROM
`tbl_student` as student
LEFT JOIN `tbl_users` as user ON (`user`.`userId` = `student`.`tutorId` AND `user`.`roleId` = 3)
LEFT JOIN `tbl_message` as msg ON (`student`.`studentId` = `msg`.`senderId` AND `msg`.`senderRole` = 4)
WHERE `student`.`isDeleted` = 0 AND  `student`.`studentId` = {$studentId} AND `msg`.`id` IS NOT NULL
ORDER BY `msg`.`createdDate` ASC
EOT;
        $queryResult = $this->db
            ->query($query);
            
        return $queryResult->result();
    }
}

