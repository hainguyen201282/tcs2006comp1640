<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Conference_model extends CI_Model
{
    function submitAddConference($conferenceInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_conference', $conferenceInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function conferenceListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.appointmentTime, BaseTbl.location, BaseTbl.topic, BaseTbl.type, BaseTbl.cstatus, BaseTbl.description, BaseTbl.createdDtm,');
        $this->db->from('tbl_conference as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.location  LIKE '%".$searchText."%'
                        OR  BaseTbl.type LIKE '%".$searchText."%'
                        OR  BaseTbl.description  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->num_rows();
    }


    function conferenceListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.appointmentTime, BaseTbl.location, BaseTbl.topic, BaseTbl.type, BaseTbl.cstatus, BaseTbl.description, BaseTbl.createdDtm,');
        $this->db->from('tbl_conference as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.location  LIKE '%".$searchText."%'
                        OR  BaseTbl.type LIKE '%".$searchText."%'
                        OR  BaseTbl.description  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function editConference($conferenceInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_conference', $conferenceInfo);

        return TRUE;
    }

    function getConferenceInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.appointmentTime, BaseTbl.location, BaseTbl.topic, BaseTbl.type, BaseTbl.cstatus, BaseTbl.description, BaseTbl.createdDtm,');
        $this->db->from('tbl_conference as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();

        return $query->row();
    }



    function getConferenceInfoById($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.appointmentTime, BaseTbl.location, BaseTbl.topic, BaseTbl.type, BaseTbl.cstatus, BaseTbl.description, BaseTbl.createdDtm,');
        $this->db->from('tbl_conference as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function deleteConference($id, $conferenceInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_conference', $conferenceInfo);

        return TRUE;
    }

    //Update 19-3-2020

    function getCStudent($id)
    {
        $this->db->select('BaseTbl.conferenceId, BaseTbl.studentId, StudentTbl.name as studentName');
        $this->db->from('tbl_conference_student as BaseTbl');
        $this->db->join('tbl_student as StudentTbl', 'BaseTbl.studentId = StudentTbl.studentId');
        $this->db->where('BaseTbl.conferenceId', $id);
        $query = $this->db->get();

        return $query->row();
    }
    //End of update 19-3-2020
}
