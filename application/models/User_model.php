<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    function getAllUsers()
    {
        $this->db->select('UserTbl.userId, UserTbl.email, UserTbl.name, UserTbl.mobile, UserTbl.createdDtm, RoleTbl.role');
        $this->db->from('tbl_users as UserTbl');
        $this->db->join('tbl_roles as RoleTbl', 'UserTbl.roleId = RoleTbl.roleId', 'left');
        $this->db->where('UserTbl.isDeleted = 0 AND RoleTbl.roleId != 1');
        $this->db->order_by('UserTbl.userId', 'DESC');
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to get active tutor notification log
     * @param {number} $tutorId : This is tutor id
     * @return {mixed} $result : This is searched result
     */
    function getTutorLogs($tutorId)
    {
        $this->db->select('*');
        $this->db->from('tbl_tutor_notification_log as BaseTbl');
        $this->db->where('BaseTbl.tutorId', $tutorId);
        $this->db->where('BaseTbl.is_read', 0);
        $this->db->order_by('BaseTbl.createdDtm DESC');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if ($userId != 0) {
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('UserTbl.userId,
            UserTbl.email,
            UserTbl.name,
            UserTbl.mobile,
            UserTbl.address,
            UserTbl.description,
            UserTbl.imgAvatar,
            UserTbl.roleId,
            UserTbl.updatedDtm,
            UserTbl.createdDtm'
        );
        $this->db->from('tbl_users as UserTbl');
        $this->db->where('UserTbl.isDeleted', 0);
        $this->db->where('UserTbl.roleId !=', 1);
        $this->db->where('UserTbl.userId', $userId);

        return $this->db->get()->row();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return TRUE;
    }


    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

        $user = $query->result();

        if (!empty($user)) {
            if (verifyHashedPassword($oldPassword, $user[0]->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }


    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     */
    function loginHistoryCount($userId, $searchText, $fromDate, $toDate)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if (!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '" . date('Y-m-d', strtotime($fromDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if (!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '" . date('Y-m-d', strtotime($toDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if ($userId >= 1) {
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function loginHistory($userId, $searchText, $fromDate, $toDate, $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        $this->db->from('tbl_last_login as BaseTbl');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if (!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '" . date('Y-m-d', strtotime($fromDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if (!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '" . date('Y-m-d', strtotime($toDate)) . "'";
            $this->db->where($likeCriteria);
        }
        if ($userId >= 1) {
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfoById($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId, address');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getUserInfoWithRole($userId)
    {
        $this->db->select('UserTbl.userId, 
            UserTbl.email, 
            UserTbl.name, 
            UserTbl.mobile, 
            UserTbl.roleId, 
            UserTbl.address,
            UserTbl.imgAvatar,
            RoleTbl.role'
        );
        $this->db->from('tbl_users as UserTbl');
        $this->db->join('tbl_roles as RoleTbl', 'RoleTbl.roleId = UserTbl.roleId');
        $this->db->where('UserTbl.userId', $userId);
        $this->db->where('UserTbl.isDeleted', 0);

        return $this->db->get()->row();
    }

    /**
     * This function used to get user role
     * @return array $result : This is user role
     */
    function getAllRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $query = $this->db->get();

        return $query->result();
    }

    function getLastMessagesIn7Days()
    {
        $now = time();
        $moment7Daysago = $now - (60 * 60 * 24 * 7);
        $this->db->select("*, UNIX_TIMESTAMP(str_to_date(`createdDtm`, '%Y-%m-%d %H:%i:%s')) as createdDtmTimestamp");
        $this->db->from('tbl_message as BaseTbl');
        $this->db->having(" (createdDtmTimestamp >= $moment7Daysago AND createdDtmTimestamp < $now) ");
        $query = $this->db->get();
        // echo "<PRE>" . print_r($this->db->last_query(), true) . "</PRE>";
        return $query->num_rows();
    }

    function uploadAvatar($filename, $userId)
    {
        if ($filename == NULL) {
            return -1;
        }

        $userEntity = array(
            'imgAvatar' => $filename,
        );

        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userEntity);

        return $this->db->affected_rows();
    }
}

