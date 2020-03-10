<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends CI_Model
{
    function submitAddStudent($studentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_student', $studentInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function studentListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                        OR  BaseTbl.name LIKE '%".$searchText."%'
                        OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->num_rows();
    }


    function studentListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                        OR  BaseTbl.name LIKE '%".$searchText."%'
                        OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
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
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        $this->db->where('BaseTbl.studentId', $studentId);
        $query = $this->db->get();

        return $query->row();
    }



    function getStudentInfoById($studentId)
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, BaseTbl.gender, BaseTbl.tutorId, BaseTbl.createdDtm,');
        $this->db->from('tbl_student as BaseTbl');
        $this->db->where('BaseTbl.studentId', $studentId);
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
}

