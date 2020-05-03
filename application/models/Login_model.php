<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Login_model (Login Model)
 * Login model class to get to authenticate user credentials
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Login_model extends CI_Model
{

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($email, $password)
    {
        $this->db->select('BaseTbl.userId, 
            BaseTbl.password, 
            BaseTbl.name,
            BaseTbl.imgAvatar, 
            BaseTbl.roleId, 
            Roles.role'
        );
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles', 'Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.email', $email);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();

        $user = $query->row();

        if (!empty($user)) {
            if (verifyHashedPassword($password, $user->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMeNew($email, $password)
    {
        $query = <<<EOT
SELECT * FROM (
SELECT 
`tbl_student`.`studentId` as studentId, 
NULL as userId, 
`tbl_student`.`password` as password,
`tbl_student`.`name` as name,
`tbl_student`.`imgAvatar` as imgAvatar,
`tbl_student`.`roleId` as roleId,
`tbl_student`.`isDeleted` as isDeleted,
'Student' as role, 
`tbl_student`.`email` AS email FROM `tbl_student` 
UNION 
SELECT 
NULL as studentId, 
`tbl_users`.`userId` as userId,
`tbl_users`.`password` as password,
`tbl_users`.`name` as name,
`tbl_users`.`imgAvatar` as imgAvatar,
`tbl_users`.`roleId` as roleId,
`tbl_users`.`isDeleted` as isDeleted,
`roles`.`role` as role, 
`tbl_users`.`email` AS email FROM `tbl_users`
LEFT JOIN `tbl_roles` as `roles` ON (`roles`.`roleId`= `tbl_users`.`roleId`)
) as studentUserUnion
WHERE `studentUserUnion`.`email` = '{$email}'
WHERE `studentUserUnion`.`isDeleted` = 0
EOT;

        $queryResult = $this->db
            ->query($query);

        $user = $queryResult->row();

        if (!empty($user)) {
            if (verifyHashedPassword($password, $user->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email, $role)
    {
        $this->db->select('*');
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        if ($role == 'student') {
            $query = $this->db->get('tbl_student');
        } else {
            $query = $this->db->get('tbl_users');
        }

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
        $result = $this->db->insert('tbl_reset_password', $data);

        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateResetPasswordUserSentStatus($id, $resetPasswordInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_reset_password', $resetPasswordInfo);
        return TRUE;
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    {
        $this->db->select('userId, email, name');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->row();
    }

    function getUnsentResetPasswordMails()
    {
        $this->db->select('*');
        $this->db->from('tbl_reset_password');
        $this->db->where(['isDeleted' => 0, 'isMailSent' => 0]);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password, $role)
    {
        $this->db->where('email', $email);
        $this->db->where('isDeleted', 0);
        if ($role == 'student') {
            $this->db->update('tbl_student', array('password' => getHashedPassword($password)));
        } else {
            $this->db->update('tbl_users', array('password' => getHashedPassword($password)));
        }
        $this->db->delete('tbl_reset_password', array('email' => $email, 'role' => $role));
    }

    /**
     * This function used to save login information of user
     * @param array $loginInfo : This is users login information
     */
    function lastLogin($loginInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_last_login', $loginInfo);
        $this->db->trans_complete();
    }

    /**
     * This function is used to get last login info by user id
     * @param number $userId : This is user id
     * @return number $result : This is query result
     */
    function lastLoginInfo($userId)
    {
        $this->db->select('BaseTbl.createdDtm');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tbl_last_login as BaseTbl');

        return $query->row();
    }
}

?>
