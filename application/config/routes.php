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

$route['default_controller'] = ["login", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['404_override'] = ['error_404', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['translate_uri_dashes'] = [FALSE, [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = ['login/loginMe', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['dashboard'] = ['user', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['logout'] = ['user/logout', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['userListing'] = ['user/userListing', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['userListing/(:num)'] = ["user/userListing/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['addNew'] = ["user/addNew", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['addNewUser'] = ["user/addNewUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOld'] = ["user/editOld", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOld/(:num)'] = ["user/editOld/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editUser'] = ["user/editUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['deleteUser'] = ["user/deleteUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['profile'] = ["user/profile", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['profile/(:any)'] = ["user/profile/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['profileUpdate'] = ["user/profileUpdate", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['profileUpdate/(:any)'] = ["user/profileUpdate/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];

//Dat-doing
$route['studentListing'] = ['student/studentListing', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['studentListing/(:num)'] = ["student/studentListing/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['addNewStudent'] = ["student/addNewStudent", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['submitAddStudent'] = ["student/submitAddStudent", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOldStudent'] = ["student/editOldStudent", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOldStudent/(:num)'] = ["student/editOldStudent/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editStudent'] = ["student/editStudent", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//$route['deleteUser'] = ["student/deleteUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//$route['profile'] = ["student/profile", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//$route['profile/(:any)'] = ["student/profile/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//$route['profileUpdate'] = ["student/profileUpdate", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//$route['profileUpdate/(:any)'] = ["student/profileUpdate/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];

//$route['conference'] = ['conference', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['conferenceListing'] = ['conference/conferenceListing', [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['conferenceListing/(:num)'] = ["conference/conferenceListing/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['addNewConference'] = ["conference/addNewConference", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['submitAddConference'] = ["conference/submitAddConference", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['deleteConference'] = ["conference/deleteConference", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editConference'] = ["conference/editConference", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOldConference'] = ["conference/editOldConference", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['editOldConference/(:num)'] = ["conference/editOldConference/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
//End of Dat-doing

$route['loadChangePass'] = ["user/loadChangePass", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['changePassword'] = ["user/changePassword", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['changePassword/(:any)'] = ["user/changePassword/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['pageNotFound'] = ["user/pageNotFound", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['checkEmailExists'] = ["user/checkEmailExists", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['login-history'] = ["user/loginHistoy", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['login-history/(:num)'] = ["user/loginHistoy/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['login-history/(:num)/(:num)'] = ["user/loginHistoy/$1/$2", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];

$route['forgotPassword'] = ["login/forgotPassword", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['resetPasswordUser'] = ["login/resetPasswordUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser'] = ["login/resetPasswordConfirmUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser/(:any)'] = ["login/resetPasswordConfirmUser/$1", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['resetPasswordConfirmUser/(:any)/(:any)'] = ["login/resetPasswordConfirmUser/$1/$2", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['createPasswordUser'] = ["login/createPasswordUser", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];

$route['blog'] = ["blog", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['addNewBlog'] = ["blog/addNewBlog", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];
$route['submitNewBlog'] = ["blog/submitNewBlog", [AUTHORISED_STAFF, STAFF, TUTOR, STUDENT] ];

/* End of file routes.php */
/* Location: ./application/config/routes.php */
