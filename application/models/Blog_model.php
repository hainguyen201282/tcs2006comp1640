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
    
    // /**
    //  * This function is used to edit blog information
    //  */
    function editBlog($blogInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_blog', $blogInfo);
        
        return TRUE;
    }

    function blogListingCount($searchText = '')
    {
        $this->db->select('Basetbl.id, Basetbl.title, Basetbl.topic, Basetbl.content, Basetbl.authorId, Basetbl.createdDate, Basetbl.updatedDate');
        $this->db->from('tbl_blog as BaseTbl');

        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.title  LIKE '%".$searchText."%' OR  BaseTbl.topic  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.status', 'PUBLISH'); 
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * This function is blog to get the blog listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function blogListing($searchText = '', $page, $segment)
    {
        $this->db->select('Basetbl.id, Basetbl.title, Basetbl.topic, Basetbl.content, Basetbl.authorId, Basetbl.createdDate, Basetbl.updatedDate');
        $this->db->from('tbl_blog as BaseTbl');

        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.title  LIKE '%".$searchText."%' OR  BaseTbl.topic  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        // $this->db->where('BaseTbl.status', 'PUBLISH');
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    function getBlogInfoById($id)
    {
        $this->db->select('id, title, topic, content, authorId, status, createdDate, updatedDate, cover');
        $this->db->from('tbl_blog');
        $this->db->where('status', 'PUBLISH');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    function editCoverBlog($cover, $blogId)
    {
        $value=array(
            'cover' => $cover
        );
        $this->db->where('id', $blogId);
        $this->db->update('tbl_blog', $value);
        
        return TRUE;
    }
}