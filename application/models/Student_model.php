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
        $this->db->select('*, BaseTbl.studentId userId');
        $this->db->from('tbl_student as BaseTbl');
        $this->db->where('BaseTbl.studentId', $studentId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
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

    function studentListingCount($searchText = '', $vendorId)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Tutor.name as tutorName, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        $this->db->join('tbl_users as Tutor', 'Tutor.userId = BaseTbl.tutorId');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%" . $searchText . "%'
                        OR  BaseTbl.name LIKE '%" . $searchText . "%'
                        OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Tutor.userId', $vendorId);
        $query = $this->db->get();

        return $query->num_rows();
    }


    function studentListing($searchText = '', $page, $segment, $vendorId)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Tutor.name as tutorName, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        $this->db->join('tbl_users as Tutor', 'Tutor.userId = BaseTbl.tutorId', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%" . $searchText . "%'
                        OR  BaseTbl.name LIKE '%" . $searchText . "%'
                        OR  BaseTbl.mobile  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('Tutor.userId', $vendorId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.studentId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function editStudent($studentInfo, $studentId)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_student', $studentInfo);

        return TRUE;
    }

    function getStudentInfo($studentId)
    {
        $this->db->select(
            'StudentTbl.studentId, StudentTbl.email, StudentTbl.name, StudentTbl.mobile, StudentTbl.roleId, StudentTbl.gender, StudentTbl.tutorId, StudentTbl.createdDtm, 
            TutorTbl.name as tutorName,'
        );
        $this->db->from('tbl_student as StudentTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = StudentTbl.tutorId');
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

}

