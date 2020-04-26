<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conference_model extends CI_Model
{
    function addConference($conferenceInfo)
    {
        $this->db->trans_start();

        $this->db->insert('tbl_conference', $conferenceInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function conferenceListing()
    {
        $this->db->select('ConfTbl.id, 
            ConfTbl.appTime, 
            ConfTbl.location, 
            ConfTbl.title, 
            ConfTbl.topic, 
            ConfTbl.type, 
            ConfTbl.description, 
            ConfTbl.host, 
            ConfTbl.role, 
            ConfTbl.status, 
            ConfTbl.updatedDate,
            ConfTbl.createdDate');
        $this->db->from('tbl_conference as ConfTbl');

        return $this->db->get()->result();
    }

    function updateConference($id, $conferenceInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_conference', $conferenceInfo);

        return $this->db->affected_rows();
    }

    function deleteConference($id, $conferenceInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_conference', $conferenceInfo);

        return $this->db->affected_rows();
    }

    function getConferenceInfoById($id)
    {
        $this->db->select('ConfTbl.id, 
            ConfTbl.appTime, 
            ConfTbl.location, 
            ConfTbl.title, 
            ConfTbl.topic, 
            ConfTbl.type, 
            ConfTbl.description, 
            ConfTbl.host, 
            ConfTbl.role, 
            ConfTbl.status, 
            ConfTbl.updatedDate,
            ConfTbl.createdDate');
        $this->db->from('tbl_conference as ConfTbl');
        $this->db->where('ConfTbl.id', $id);

        return $this->db->get()->row();
    }

    function getAvailableTimeByDate($appDate)
    {
        $this->db->select('ConfTbl.id, ConfTbl.appTime');
        $this->db->from('tbl_conference as ConfTbl');

        $likeCriteria = "(ConfTbl.appTime LIKE '" . $appDate . "%')";
        $this->db->where($likeCriteria);

        return $this->db->get()->result();
    }

    function getAllAttenderByConferenceId($conferenceId, $currentVendorId)
    {
        $this->db->select('AttendTbl.id,
            StudentTbl.name'
        );
        $this->db->from('tbl_attend as AttendTbl');

        $this->db->join('tbl_student as StudentTbl', 'AttendTbl.userId = StudentTbl.studentId');
        $this->db->where('AttendTbl.conferenceId', $conferenceId);
        $this->db->where('StudentTbl.studentId', $currentVendorId);

        return $this->db->get()->result();
    }

    function addAttender($attenderInfo)
    {
        $this->db->select('AttendTbl.id');
        $this->db->from('tbl_attend as AttendTbl');
        $this->db->where('AttendTbl.userId', $attenderInfo['userId']);
        $this->db->where('AttendTbl.conferenceId', $attenderInfo['conferenceId']);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return -1;
        }

        $this->db->trans_start();

        $this->db->insert('tbl_attend', $attenderInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function deleteAttender($attendId)
    {
        $this->db->where('id', $attendId);
        $this->db->delete('tbl_attend');
    }
}
