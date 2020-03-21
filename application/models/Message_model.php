<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model
{
    function submitAddMessage($messageInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_message', $messageInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function messageListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.subject  LIKE '%" . $searchText . "%'
                        OR  BaseTbl.messageContent LIKE '%" . $searchText . "%'
                        OR  BaseTbl.createdDtm  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->num_rows();
    }


    function messageListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.subject  LIKE '%" . $searchText . "%'
                        OR  BaseTbl.messageContent LIKE '%" . $searchText . "%'
                        OR  BaseTbl.createdDtm  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getMessageInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, 
                            BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    function getMessageInfoById($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, 
                            BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function deleteMessage($id, $messageInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_message', $messageInfo);
        return $this->db->affected_rows();
    }


    //Update 18-03-2020
    function  getMessageSentByTutor($tutorId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, 
                            BaseTbl.subject, BaseTbl.messageContent, TutorTbl.name, BaseTbl.createdDtm, StudentTbl.name as studentName');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = BaseTbl.senderByUserId');
        $this->db->join('tbl_student as StudentTbl', 'StudentTbl.studentId = BaseTbl.receiverByStudentId');
        $this->db->where('TutorTbl.userId', $tutorId);
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function  getMessageReceivedByTutor($tutorId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, 
                            BaseTbl.subject, BaseTbl.messageContent, TutorTbl.name, BaseTbl.createdDtm, StudentTbl.name as studentName');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->join('tbl_users as TutorTbl', 'TutorTbl.userId = BaseTbl.receiverByUserId');
        $this->db->join('tbl_student as StudentTbl', 'StudentTbl.studentId = BaseTbl.senderByStudentId');
        $this->db->where('TutorTbl.userId', $tutorId);
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    function  getAllMessage(){
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function  getMessageSentByStudent($studentId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->join('tbl_student as StudentTbl', 'StudentTbl.studentId = BaseTbl.senderByStudentId');
        $this->db->where('StudentTbl.studentId', $studentId);
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function  getMessageReceivedByStudent($studentId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.senderByUserId, BaseTbl.senderByStudentId, BaseTbl.receiverByUserId, BaseTbl.receiverByStudentId, BaseTbl.subject, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->join('tbl_student as StudentTbl', 'StudentTbl.studentId = BaseTbl.receiverByStudentId') or $this->db->join('tbl_student as StudentTbl', 'StudentTbl.studentId = BaseTbl.senderByStudentId');
        $this->db->where('StudentTbl.studentId', $studentId);
        $this->db->order_by('BaseTbl.id', 'ASC');
        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    function viewMessage($messageInfo, $id)
    {
        $this->db->where('id', $id);

        return TRUE;
    }

    function getAllStudents()
    {
        $this->db->select('BaseTbl.studentId, BaseTbl.name as studentName');
        $this->db->from('tbl_student as BaseTbl');

        $query = $this->db->get();

        return $query->result();
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
    //End of Update 19-03-2020
}

