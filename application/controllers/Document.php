<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Document extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document_model');
        $this->isLoggedIn();

        if ($this->role == STUDENT) {
            $this->load->model('student_model');
            $studentNotificationLogsInfo = $this->student_model->getStudentLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $studentNotificationLogsInfo;
        }

        if ($this->role == TUTOR) {
            $this->load->model('user_model');
            $tutorNotificationLogsInfo = $this->user_model->getTutorLogs($this->vendorId);
            $this->global ['notifficationLogs'] = $tutorNotificationLogsInfo;
        }
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = ' Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function uploadDocument() {
        $preview = $config = $errors = [];
        $input = 'input-res-3'; // the input name for the fileinput plugin

        if (empty($_FILES[$input])) {
            echo(json_encode([]));
        }

        $total = count($_FILES[$input]['name']); // multiple files
        $path = 'uploads/documents'; // your upload path
        for ($i = 0; $i < $total; $i++) {
            $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
            $fileName = $_FILES[$input]['name'][$i]; // the file name
            $uniqueFileName = md5(uniqid()) . '_' . $fileName;
            $ext = explode('.', basename($fileName));
            $fileSize = $_FILES[$input]['size'][$i]; // the file size
            $fileType = $_FILES[$input]['type'][$i]; // the file size
            
            //Make sure we have a file path
            if ($tmpFilePath != ""){
                //Setup our new file path
                $newFilePath = $path . DIRECTORY_SEPARATOR . $uniqueFileName;
                $newFileUrl = base_url() . $path . DIRECTORY_SEPARATOR . $uniqueFileName;
                
                //Upload the file into the new path
                if (!file_exists($newFilePath)) {
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $fileId = $fileName . $i; // some unique key to identify the file
                        $preview[] = $newFileUrl;
                        $aParams = [
                            'filekey' => $fileId,
                            'caption' => $fileName,
                            'realFilename' => $uniqueFileName,
                            'filetype' => $fileType,
                            'size' => $fileSize,
                            'downloadUrl' => $newFileUrl, // the url to download the file
                            'vendorId' => $this->vendorId, // the url to download the file
                            'vendorRole' => $this->role, // the url to download the file
                            'createdDtm' => date('Y-m-d H:i:s')
                        ];
                        $dbFileId = $this->document_model->addNewDocumentFileInfo($aParams);

                        $config[] = [
                            'key' => $fileId,
                            'caption' => $fileName,
                            'filetype' => $fileType,
                            'size' => $fileSize,
                            'downloadUrl' => $newFileUrl, // the url to download the file
                            'url' => base_url() . 'deleteDocumentFile/' . $dbFileId, // server api to delete the file based on key
                        ];
                    } else {
                        $errors[] = $fileName;
                    }
                } else {
                    $errors[] = $fileName;
                }
            } else {
                $errors[] = $fileName;
            }
        }
        $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true, 'dbFileId' => $dbFileId];
        if (!empty($errors)) {
            $img = count($errors) === 1 ? 'file "' . $errors[0]  . '" ' : 'files: "' . implode('", "', $errors) . '" ';
            $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
        }

        echo(json_encode($out));
    }


    /**
     * This function used to load the first screen of the document
     */
    function documentListing()
    {
        $this->load->library('pagination');

        $data['documentRecords'] = $this->document_model->getAllDocument();

        $this->global['pageTitle'] = ' Document Listing';
        $this->loadViews("document", $this->global, $data, NULL);
    }

    function addNewDocument()
    {
        $this->load->model('document_model');
        $this->global['pageTitle'] = ' Add New Document';
        $this->loadViews("addNewDocument", $this->global, array('error' => ' '), NULL);
    }

    function submitNewDocument()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('topic', 'Topic', 'trim|required|max_length[256]');

        if ($this->form_validation->run() == FALSE) {
            $this->addNewDocument();
        } else {

            $topic = $this->input->post('topic');
            $fileIDs = $this->input->post('fileIDs');

            $arrFileIDs = explode(",", $fileIDs);

            $documentInfo = array(
                'topic' => $topic,
                'vendorId' => $this->vendorId,
                'vendorRole' => $this->role,
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s')
            );

            $documentId = $this->document_model->addNewDocument($documentInfo);

            $documentFilesInfo = [];
            if ($arrFileIDs && count($arrFileIDs) > 0) {
                foreach ($arrFileIDs as $key => $fileId) {
                    $documentFilesInfo[] = [
                        'fileId' => $fileId,
                        'documentId' => $documentId
                    ];
                }

                $this->document_model->editDocumentFile(null, $documentFilesInfo);
            }


            if ($documentId > 0) {
                redirect('documentListing');
            } else if ($documentId <= 0) {
                redirect('addNewDocument');
                $this->session->set_flashdata('error', 'Document creation failed');
            }
        }
    }

    function editViewDocument($id = NULL)
    {
        if ($id == null) {
            redirect('documentListing');
        }

        $data['documentInfo'] = $this->document_model->getDocumentInfoById($id);

        $arrDocumentIDs = [];
        $arrInitialPreviewData = [];
        $arrInitialPreviewDataConfig = [];
        foreach ($data['documentInfo'] as $key => $document) {
            $arrDocumentIDs[] = $document->fileId;
            $arrInitialPreviewData[] = $document->downloadUrl;
            $arrInitialPreviewDataConfig[] = [
                'caption' => $document->caption,
                'filetype' => $document->filetype,
                'size' => $document->size,
                'downloadUrl' => $document->downloadUrl,
                'url' => base_url() . 'deleteDocumentFile/' . $document->fileId,
            ];
        }

        $data['arrDocumentIDs'] = $arrDocumentIDs;
        $data['arrInitialPreviewData'] = $arrInitialPreviewData;
        $data['arrInitialPreviewDataConfig'] = json_encode($arrInitialPreviewDataConfig);

        $this->global['pageTitle'] = ' Edit Document';
        $this->loadViews("editDocument", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the user information
     */
    function editDocument()
    {
        $this->load->library('form_validation');

        $documentId = $this->input->post('documentId');

        $this->form_validation->set_rules('topic', 'Topic', 'trim|required|max_length[256]');

        if ($this->form_validation->run() == FALSE) {
            $this->editViewDocument($documentId);
        } else {

            $topic = $this->input->post('topic');
            $fileIDs = $this->input->post('fileIDs');

            $arrFileIDs = explode(",", $fileIDs);

            $documentInfo = array(
                'topic' => $topic,
                'updatedDate' => date('Y-m-d H:i:s'),
            );

            $result = $this->document_model->editDocument($documentId, $documentInfo);

            $documentFilesInfo = [];
            if ($arrFileIDs && count($arrFileIDs) > 0) {
                foreach ($arrFileIDs as $key => $fileId) {
                    $documentFilesInfo[] = [
                        'fileId' => $fileId,
                        'documentId' => $documentId
                    ];
                }

                $this->document_model->editDocumentFile($documentId, $documentFilesInfo);
            }

            if ($result > 0) {
                $this->session->set_flashdata('success', 'Document update successfully');
            } else if ($result <= 0) {
                $error = 'Document update failed';
                $this->session->set_flashdata('error', $error);
            }

            redirect(array('editViewDocument', 'id' => $documentId));
        }
    }

    function deleteDocument()
    {
        $documentId = $this->input->post('documentId');

        $documentInfo = $this->document_model->getDocumentInfoById($documentId);
        if ($documentInfo && $documentInfo[0]->vendorId == $this->vendorId && 
            $documentInfo[0]->vendorRole = $this->role) {
            foreach ($documentInfo as $key => $document) {
                if (file_exists("uploads/documents/" . $document->realFilename)) {
                    unlink("uploads/documents/" . $document->realFilename);
                }

                $this->document_model->deleteFileInfoById($document->fileId);
            }
        }

        $result = $this->document_model->deleteDocument($documentId);

        if ($result > 0) {
            echo(json_encode(array('status' => true)));
        } else {
            echo(json_encode(array('status' => false)));
        }
    }

    function deleteDocumentFile($fileId)
    {
        $documentFileInfo = $this->document_model->getFileInfoById($fileId);

        if ($documentFileInfo->vendorId == $this->vendorId && 
            $documentFileInfo->vendorRole = $this->role) {
            
            if (file_exists("uploads/documents/" . $documentFileInfo->realFilename)) {
                unlink("uploads/documents/" . $documentFileInfo->realFilename);
            }

            $this->document_model->deleteFileInfoById($documentFileInfo->fileId);

            $out = [
                'dbFileId' => $fileId
            ];
            
            echo(json_encode($out));
        } else {
            $out = [
                'error' => "Oh snap! You have no authorization to delete file"
            ];
            
            echo(json_encode($out));
        }
    }

    function documentDetail($documentId)
    {
        $data['documentInfo'] = $this->document_model->getDocumentInfoById($documentId);

        $data['authorInfo'] = $this->getUserInfo($data['documentInfo'][0]->vendorRole, $data['documentInfo'][0]->vendorId);

        $comments = $this->document_model->getAllCommentByDocumentId($documentId);
        foreach ($comments as $comment) {

            $userComment = $this->getUserInfo($comment->userRole, $comment->userId);

            $comment->name = $userComment->name;
            $comment->imgAvatar = ($userComment->imgAvatar == NULL) ? 'avatar.png' : $userComment->imgAvatar;
        }
        $data['documentComments'] = $comments;

        $arrInitialPreviewData = [];
        $arrInitialPreviewDataConfig = [];
        foreach ($data['documentInfo'] as $key => $document) {
            $arrInitialPreviewData[] = $document->downloadUrl;
            $arrInitialPreviewDataConfig[] = [
                'caption' => $document->caption,
                'filetype' => $document->filetype,
                'size' => $document->size,
                'downloadUrl' => $document->downloadUrl,
                'url' => base_url() . 'deleteDocumentFile/' . $document->fileId,
            ];
        }

        $data['arrInitialPreviewData'] = $arrInitialPreviewData;
        $data['arrInitialPreviewDataConfig'] = json_encode($arrInitialPreviewDataConfig);

        $this->global['pageTitle'] = ' Document Detail';
        $this->loadViews("documentDetail", $this->global, $data, NULL);
    }

    /**
     * @param $role
     * @param $userId
     * @return mixed
     */
    function getUserInfo($role, $userId)
    {
        switch ($role) {
            case STUDENT:
                $this->load->model('student_model');
                return $this->student_model->getStudentProfile($userId);
            case TUTOR:
                $this->load->model('user_model');
                return $this->user_model->getUserInfo($userId);
            default:
                return array(
                    'name' => 'Unknown',
                    'imgAvatar' => 'avatar.png',
                );
        }
    }

    function submitComment()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|max_length[650000]');

        $documentId = $this->input->post('documentId');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $message = $this->input->post('message');

            $commentInfo = array(
                'content' => $message,
                'status' => ACTIVATE,
                'userId' => $this->vendorId,
                'userRole' => $this->role,
                'documentId' => $documentId,
                'updatedDate' => date('Y-m-d H:i:s'),
                'createdDate' => date('Y-m-d H:i:s'));

            $this->load->model('document_model');

            $result = $this->document_model->addComment($commentInfo);

            if ($result <= 0) {
                $this->session->set_flashdata('error', 'Comment failed');
            }
        }
        redirect(array('documentDetail', 'documentId' => $documentId));
    }

    function deleteComment()
    {
        $id = $this->input->post('commentId');

        $commentInfo = array(
            'status' => DELETE,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('document_model');
        $result = $this->document_model->deleteComment($id, $commentInfo);

        if ($result > 0) {
            echo(json_encode(true));
        } else {
            echo(json_encode(false));
        }
    }

    function updateComment()
    {
        $id = $this->input->post('commentId');
        $content = $this->input->post('content');

        $commentInfo = array(
            'content' => $content,
            'updatedDate' => date('Y-m-d H:i:s')
        );

        $this->load->model('document_model');
        $result = $this->document_model->deleteComment($id, $commentInfo);

        if ($result > 0) {
            echo(json_encode(true));
        } else {
            echo(json_encode(false));
        }
    }
}

?>
