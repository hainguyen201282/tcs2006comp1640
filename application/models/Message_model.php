<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

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
        $this->db->select('BaseTbl.id, BaseTbl.receiverId, BaseTbl.subject, BaseTbl.messageStatus, BaseTbl.messageContent, BaseTbl.createdBy, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.receiverId  LIKE '%".$searchText."%'                        
                        OR  BaseTbl.subject  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->num_rows();
    }


    function messageListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.receiverId, BaseTbl.subject, BaseTbl.messageStatus, BaseTbl.messageContent, BaseTbl.createdBy, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.receiverId  LIKE '%".$searchText."%'
                        OR  BaseTbl.subject  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function editMessage($messageInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_message', $messageInfo);

        return TRUE;
    }

    function getMessageInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.receiverId, BaseTbl.subject, BaseTbl.messageStatus, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();

        return $query->row();
    }



    function getMessageInfoById($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.receiverId, BaseTbl.subject, BaseTbl.messageStatus, BaseTbl.messageContent, BaseTbl.createdDtm,');
        $this->db->from('tbl_message as BaseTbl');
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

//    function deleteMessage($id, $messageInfo)
//    {
//        $this->db->where('id', $id);
//        $this->db->update('tbl_message', $messageInfo);
//        return $this->db->affected_rows();
//    }
}
