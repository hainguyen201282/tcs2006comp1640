<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends CI_Model
{

    function addNewDocument($documentInfo)
    {
        $this->db->trans_start();

        $this->db->insert('tbl_document', $documentInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function addNewDocumentFileInfo($documentFileInfo)
    {
        $this->db->trans_start();

        $this->db->insert('tbl_document_file_info', $documentFileInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function getFileInfoById($fileId){
        $this->db->select('*');
        $this->db->from('tbl_document_file_info');
        $this->db->where('fileId', $fileId);

        return $this->db->get()->row();
    }

    function deleteFileInfoById($fileId)
    {
        $this->db->where('fileId', $fileId);
        $this->db->delete('tbl_document_file_info');
    }

    /**
     * This function is used to edit document information
     * @param $documentInfo
     * @param $id
     * @return
     */
    function editDocument($id, $documentInfo)
    {
        $this->db->where('documentId', $id);
        $this->db->update('tbl_document', $documentInfo);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to edit document File information
     * @param $documentInfo
     * @param $id
     * @return
     */
    function editDocumentFile($documentId = null, $documentFileInfo)
    {
        if ($documentId) {
            $this->db->set('documentId', 'NULL', false);
            $this->db->where('documentId', $documentId);
            $this->db->update('tbl_document_file_info');
            $result = $this->db->affected_rows();
        }
        
        $this->db->update_batch('tbl_document_file_info', $documentFileInfo, "fileId");
    }

    function deleteDocument($documentId)
    {
        $this->db->where('documentId', $documentId);
        $this->db->delete('tbl_document');
        $result = $this->db->affected_rows();

        return $result;
    }

    function getAllDocument()
    {
        $this->db->select('DISTINCT(DocumentTbl.documentId) as unique_doc_id, DocumentTbl.topic, DocumentFileTbl.*');
        $this->db->from('tbl_document AS DocumentTbl');
        $this->db->join('tbl_document_file_info as DocumentFileTbl', 'DocumentFileTbl.documentId = DocumentTbl.documentid', 'left');
        $this->db->group_by("DocumentTbl.documentId");
        $this->db->order_by("DocumentTbl.updatedDate DESC");
        $query = $this->db->get();

        return $query->result();
    }

    function getDocumentInfoById($documentid)
    {
        $this->db->select('DocumentTbl.topic, DocumentFileTbl.*');
        $this->db->from('tbl_document as DocumentTbl');
        $this->db->join('tbl_document_file_info as DocumentFileTbl', 'DocumentFileTbl.documentId = DocumentTbl.documentid', 'left');
        $this->db->where('DocumentTbl.documentid', $documentid);

        return $this->db->get()->result();
    }

    function getAllCommentByDocumentId($documentId)
    {
        $this->db->select('CommentTbl.id,
            CommentTbl.content,
            CommentTbl.status,
            CommentTbl.userId,
            CommentTbl.userRole, 
            CommentTbl.updatedDate,
            CommentTbl.createdDate'
        );
        $this->db->from('tbl_document_comment as CommentTbl');
        $this->db->where('CommentTbl.status', 'ACTIVATE');
        $this->db->where('CommentTbl.documentId', $documentId);

        return $this->db->get()->result();
    }

    function editCoverDocument($cover, $documentId)
    {
        $value = array(
            'cover' => $cover
        );
        $this->db->where('id', $documentId);
        $this->db->update('tbl_document', $value);

        return TRUE;
    }

    function addComment($commentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_document_comment', $commentInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    function deleteComment($id, $commentInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_document_comment', $commentInfo);

        return $this->db->affected_rows();
    }
}
