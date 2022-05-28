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
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['login'] = 'login/index';
$route['login/authenticate'] = 'login/authenticate';
$route['log_out'] = 'home/log_out';
$route['check_otp'] = 'login/check_otp';
$route['register'] = 'login/register';
$route['register/submit'] = 'login/register_submit';
$route['register/verify'] = 'login/register_verify';
$route['home'] = 'home/index';
$route['roles'] = 'roles/index';

$route['users'] = 'users/index';
$route['users/get/all'] = 'users/getAllUsers';
$route['users/get'] = 'users/getUser';
$route['users/save'] = 'users/saveUser';
$route['users/delete'] = 'users/deleteUser';

$route['patients'] = 'patients/index';
$route['patients/get/all'] = 'patients/getAllPatients';
$route['patients/get'] = 'patients/getPatient';
$route['patients/save'] = 'patients/savePatient';
$route['patients/delete'] = 'patients/deletePatient';
$route['patients/otp'] = 'patients/sendOtp';
$route['patients/otp/check'] = 'patients/checkOtp';
$route['patients/download'] = 'patients/download';

$route['doctors'] = 'doctors/index';
$route['doctors/get/all'] = 'doctors/getAllDoctors';
$route['doctors/get'] = 'doctors/getDoctor';
$route['doctors/save'] = 'doctors/saveDoctor';
$route['doctors/delete'] = 'doctors/deleteDoctor';

$route['encounters'] = 'encounters/index';
$route['encounters/get/all'] = 'encounters/getAllEncounters';
$route['encounters/get'] = 'encounters/getEncounter';
$route['encounters/save'] = 'encounters/saveEncounter';
$route['encounters/delete'] = 'encounters/deleteEncounter';

$route['members'] = 'members/index';
$route['members/get/all'] = 'members/getAllMembers';
$route['members/get'] = 'members/getMember';
$route['members/save'] = 'members/saveMember';
$route['members/delete'] = 'members/deleteMember';

$route['profile'] = 'profile/index';
$route['profile/get'] = 'profile/getProfile';
$route['profile/save'] = 'profile/saveProfile';

$route['reports'] = 'reports/index';
$route['reports/get/all'] = 'reports/getAll';
$route['reports/send/otp'] = 'reports/sendOtp';

$route['forgot'] = 'login/forgot';
$route['forgot/submit'] = 'login/forgot_submit';
$route['login/reset'] = 'login/reset_password';

$route['emergency'] = 'emergency/index';
$route['emergency/get/all'] = 'emergency/getAllPatients';
$route['emergency/submit'] = 'emergency/emergencySubmit';
$route['emergency/otp'] = 'emergency/emergencyOtp';

$route['medical_records'] = 'medical_records/index';
$route['medical_records/get/all'] = 'medical_records/getAllMedicalRecords';
$route['medical_records/get'] = 'medical_records/getMedicalRecords';
$route['medical_records/save'] = 'medical_records/saveMedicalRecords';
$route['medical_records/delete'] = 'medical_records/deleteMedicalRecords';


