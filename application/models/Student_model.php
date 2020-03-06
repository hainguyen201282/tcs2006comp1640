<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Student_model (Student Model)
 * Student model class to get to handle student related data
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Student_model extends CI_Model
{
    /**
     * This function is used to get the student listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function studentListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.gender, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_students as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get the student listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function studentListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.gender, BaseTbl.createdDtm, Role.role');
        $this->db->from('tbl_students as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->order_by('BaseTbl.studentId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the student roles information
     * @return array $result : This is result of the query
     */
    function getStudentRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $studentId : This is student id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $studentId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_students");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if($studentId != 0){
            $this->db->where("studentId !=", $studentId);
        }
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to add new student to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewStudent($studentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_students', $studentInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get student information by id
     * @param number $studentId : This is student id
     * @return array $result : This is student information
     */
    function getStudentInfo($studentId)
    {
        $this->db->select('studentId, name, email, mobile, roleId');
        $this->db->from('tbl_students');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('studentId', $studentId);
        $query = $this->db->get();

        return $query->row();
    }


    /**
     * This function is used to update the student information
     * @param array $studentInfo : This is students updated information
     * @param number $studentId : This is student id
     */
    function editStudent($studentInfo, $studentId)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_students', $studentInfo);

        return TRUE;
    }



    /**
     * This function is used to delete the student information
     * @param number $studentId : This is student id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteStudent($studentId, $studentInfo)
    {
        $this->db->where('studentId', $studentId);
        $this->db->update('tbl_students', $studentInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to match students password for change password
     * @param number $studentId : This is student id
     */
    function matchOldPassword($studentId, $oldPassword)
    {
        $this->db->select('studentId, password');
        $this->db->where('studentId', $studentId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_students');

        $student = $query->result();

        if(!empty($student)){
            if(verifyHashedPassword($oldPassword, $student[0]->password)){
                return $student;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change students password
     * @param number $studentId : This is student id
     * @param array $studentInfo : This is student updation info
     */
    function changePassword($studentId, $studentInfo)
    {
        $this->db->where('studentId', $studentId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_students', $studentInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to get student login history
     * @param number $studentId : This is student id
     */
    function loginHistoryCount($studentId, $searchText, $fromDate, $toDate)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.studentAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($studentId >= 1){
            $this->db->where('BaseTbl.studentId', $studentId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get student login history
     * @param number $studentId : This is student id
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function loginHistory($studentId, $searchText, $fromDate, $toDate, $page, $segment)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.studentAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        $this->db->from('tbl_last_login as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($studentId >= 1){
            $this->db->where('BaseTbl.studentId', $studentId);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function used to get student information by id
     * @param number $studentId : This is student id
     * @return array $result : This is student information
     */
    function getStudentInfoById($studentId)
    {
        $this->db->select('studentId, name, email, mobile, roleId');
        $this->db->from('tbl_students');
        $this->db->where('isDeleted', 0);
        $this->db->where('studentId', $studentId);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to get student information by id with role
     * @param number $studentId : This is student id
     * @return aray $result : This is student information
     */
    function getStudentInfoWithRole($studentId)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_students as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.studentId', $studentId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }

}