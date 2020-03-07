<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends CI_Model
{
    function addNewBlog($blogInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_blog', $blogInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
}