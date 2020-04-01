<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends CI_Model
{
    function saveMessage($messageEntity, $messageAttr)
    {
        // save message
        $this->db->trans_start();

        $this->db->insert('tbl_message', $messageEntity);
        $message_id = $this->db->insert_id();

        // create message attr entity with message id
        $messageAttrEntity = (object)array_merge($messageAttr, array('messageId' => $message_id));

        // save message attr
        $this->db->insert('tbl_message_attr', $messageAttrEntity);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }
}

