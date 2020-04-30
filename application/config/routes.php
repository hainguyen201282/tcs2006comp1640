<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = ["login", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['404_override'] = ['error_404', [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['translate_uri_dashes'] = [FALSE, [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];

/*********** USER DEFINED ROUTES *******************/

$route['dashboard'] = ['user', [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['loginMe'] = ['login/loginMe', [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['loginStudent'] = ['student/loginStudent', [AUTHORISED_STAFF, ADMIN, TUTOR] ];
$route['logout'] = ['user/logout', [AUTHORISED_STAFF, ADMIN, TUTOR] ];

/* USER MANAGEMENT */
$route['userListing'] = ['user/userListing', [AUTHORISED_STAFF, ADMIN] ];
$route['addNew'] = ["user/addNew", [AUTHORISED_STAFF, ADMIN] ];
$route['addNewUser'] = ["user/addNewUser", [AUTHORISED_STAFF, ADMIN] ];
$route['editOld'] = ["user/editOld", [AUTHORISED_STAFF, ADMIN] ];
$route['editOld/(:num)'] = ["user/editOld/$1", [AUTHORISED_STAFF, ADMIN] ];
$route['editUser'] = ["user/editUser", [AUTHORISED_STAFF, ADMIN] ];
$route['deleteUser'] = ["user/deleteUser", [AUTHORISED_STAFF, ADMIN] ];
$route['deleteUser'] = ["user/deleteUser", [AUTHORISED_STAFF, ADMIN] ];
$route['importUsers'] = ["user/importUsers", [AUTHORISED_STAFF, ADMIN] ];
$route['exportUsers'] = ["user/exportUsers", [AUTHORISED_STAFF, ADMIN] ];
$route['tutorDashboard/(:num)'] = ["user/tutorDashboard/$1", [AUTHORISED_STAFF, ADMIN] ];
$route['studentDashboard/(:num)'] = ["user/studentDashboard/$1", [AUTHORISED_STAFF, ADMIN] ];
/* ---THOSE API ACCEPTABLE FOR AUTHORISED_STAFF AND ADMIN--- */

/* PROFILE */
$route['profile'] = ["user/profile", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['profile/(:any)'] = ["user/profile/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['profileUpdate'] = ["user/profileUpdate", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['profileUpdate/(:any)'] = ["user/profileUpdate/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['uploadAvatar/(:num)'] = ["user/uploadAvatar/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['loadChangePass'] = ["user/loadChangePass", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['changePassword'] = ["user/changePassword", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['changePassword/(:any)'] = ["user/changePassword/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
/* -------------------------------------------------------------------- */

/* STUDENT MANAGEMENT */
$route['studentListing'] = ['student/studentListing', [AUTHORISED_STAFF, ADMIN, TUTOR] ];
$route['addNewStudent'] = ["student/addNewStudent", [AUTHORISED_STAFF, ADMIN] ];
$route['submitAddStudent'] = ["student/submitAddStudent", [AUTHORISED_STAFF, ADMIN] ];
$route['editOldStudent'] = ["student/editOldStudent", [AUTHORISED_STAFF, ADMIN] ];
$route['editOldStudent/(:num)'] = ["student/editOldStudent/$1", [AUTHORISED_STAFF, ADMIN] ];
$route['editStudent'] = ["student/editStudent", [AUTHORISED_STAFF, ADMIN] ];
$route['deleteStudent'] = ["student/deleteStudent", [AUTHORISED_STAFF, ADMIN] ];
$route['assignStudent'] = ["student/assignStudent", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['assignOldStudent'] = ["student/assignOldStudent", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['assignOldStudent/(:num)'] = ["student/assignOldStudent/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['viewAssignTutor'] = ["student/viewAssignTutor", [AUTHORISED_STAFF, ADMIN] ];
$route['assignTutor'] = ["student/assignTutor", [AUTHORISED_STAFF, ADMIN] ];
$route['unassignTutor'] = ["student/unassignTutor", [AUTHORISED_STAFF, ADMIN] ];
$route['getAllStudentByTutorId'] = ["student/getAllStudentByTutorId", [AUTHORISED_STAFF, ADMIN] ];
$route['importStudents'] = ["student/importStudents", [AUTHORISED_STAFF, ADMIN] ];
$route['exportStudents'] = ["student/exportStudents", [AUTHORISED_STAFF, ADMIN] ];
/* -------------------------------------------------------------------- */

/* MESSAGE MANAGEMENT */
$route['sendMessage'] = ["message/sendMessage", [TUTOR, STUDENT] ];
/* -------------------------------------------------------------------- */

/* CONFERENCE MANAGEMENT */
$route['conferenceListing'] = ["conference/conferenceListing", [TUTOR, STUDENT] ];
$route['addNewConference'] = ["conference/addNewConference", [TUTOR, STUDENT] ];
$route['getAvailableTime'] = ["conference/getAvailableTime", [TUTOR, STUDENT] ];
$route['submitNewConference'] = ["conference/submitNewConference", [TUTOR, STUDENT] ];
$route['editConferenceView/(:num)'] = ["conference/editConferenceView/$1", [TUTOR, STUDENT] ];
$route['editConference'] = ["conference/editConference", [TUTOR, STUDENT] ];
$route['searchUser'] = ["conference/searchUser", [TUTOR, STUDENT] ];
$route['addAttender'] = ["conference/addAttender", [TUTOR, STUDENT] ];
$route['deleteConference'] = ["conference/deleteConference", [TUTOR, STUDENT] ];
$route['deleteAttender'] = ["conference/deleteAttender", [TUTOR, STUDENT] ];
/* -------------------------------------------------------------------- */


$route['pageNotFound'] = ["user/pageNotFound", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['checkEmailExists'] = ["user/checkEmailExists", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['login-history'] = ["user/loginHistory", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['login-history/(:num)'] = ["user/loginHistory/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['login-history/(:num)/(:num)'] = ["user/loginHistory/$1/$2", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];

$route['forgotPassword'] = ["login/forgotPassword", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['resetPasswordUser'] = ["login/resetPasswordUser", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser'] = ["login/resetPasswordConfirmUser", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser/(:any)'] = ["login/resetPasswordConfirmUser/$1", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser/(:any)/(:any)'] = ["login/resetPasswordConfirmUser/$1/$2", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser/(:any)/(:any)/(:any)'] = ["login/resetPasswordConfirmUser/$1/$2/$3", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];
$route['createPasswordUser'] = ["login/createPasswordUser", [AUTHORISED_STAFF, ADMIN, TUTOR, STUDENT] ];

/* BLOG MANAGEMENT */
$route['addNewBlog'] = ["blog/addNewBlog", [TUTOR, STUDENT] ];
$route['submitNewBlog'] = ["blog/submitNewBlog", [TUTOR, STUDENT] ];
$route['blogListing'] = ["blog/blogListing", [TUTOR, STUDENT] ];
$route['editViewBlog/(:any)'] = ["blog/editViewBlog/$1", [TUTOR, STUDENT] ];
$route['editBlog'] = ["blog/editBlog", [TUTOR, STUDENT] ];
$route['deleteBlog'] = ["blog/deleteBlog", [TUTOR, STUDENT] ];
$route['blogDetail/(:any)'] = ["blog/blogDetail/$1", [TUTOR, STUDENT] ];
$route['submitComment'] = ["blog/submitComment", [TUTOR, STUDENT] ];
$route['updateComment'] = ["blog/updateComment", [TUTOR, STUDENT] ];
$route['deleteComment'] = ["blog/deleteComment", [TUTOR, STUDENT] ];
/* -------------------------------------------------------------------- */

/* End of file routes.php */
/* Location: ./application/config/routes.php */
