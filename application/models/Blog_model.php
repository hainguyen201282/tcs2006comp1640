<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

    /**
     * This function is used to edit blog information
     * @param $blogInfo
     * @param $id
     * @return
     */
    function editBlog($id, $blogInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_blog', $blogInfo);
        return $this->db->affected_rows();
    }

    function deleteBlog($id, $blogInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_blog', $blogInfo);
        return $this->db->affected_rows();
    }

    function getAllBlog()
    {
        $this->db->select('BlogTbl.id,
            BlogTbl.title,
            BlogTbl.topic,
            BlogTbl.content,
            BlogTbl.status,
            BlogTbl.author,
            BlogTbl.role, 
            BlogTbl.coverImg,
            BlogTbl.updatedDate,
            BlogTbl.createdDate'
        );
        $this->db->from('tbl_blog AS BlogTbl');
        return $this->db->get()->result();
    }

    function getBlogInfoById($id)
    {
        $this->db->select('BlogTbl.id,
            BlogTbl.title,
            BlogTbl.topic,
            BlogTbl.content,
            BlogTbl.status,
            BlogTbl.author,
            BlogTbl.role, 
            BlogTbl.coverImg,
            BlogTbl.updatedDate,
            BlogTbl.createdDate'
        );
        $this->db->from('tbl_blog as BlogTbl');
        $this->db->where('BlogTbl.id', $id);

        return $this->db->get()->row();
    }

    function getAllTopic()
    {
        $this->db->select('BlogTbl.topic');
        $this->db->from('tbl_blog AS BlogTbl');
        $this->db->group_by('BlogTbl.topic');

        return $this->db->get()->result();
    }

    function getAllCommentByBlogId($blogId)
    {
        $this->db->select('CommentTbl.id,
            CommentTbl.content,
            CommentTbl.status,
            CommentTbl.userId,
            CommentTbl.userRole, 
            CommentTbl.updatedDate,
            CommentTbl.createdDate'
        );
        $this->db->from('tbl_comment as CommentTbl');
        $this->db->where('CommentTbl.status', 'ACTIVATE');
        $this->db->where('CommentTbl.blogId', $blogId);

        return $this->db->get()->result();
    }

    function editCoverBlog($cover, $blogId)
    {
        $value = array(
            'cover' => $cover
        );
        $this->db->where('id', $blogId);
        $this->db->update('tbl_blog', $value);

        return TRUE;
    }

    function addComment($commentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_comment', $commentInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function deleteComment($id, $commentInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_comment', $commentInfo);

        return $this->db->affected_rows();
    }
}
