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
$route['default_controller'] = 'home';
$route['404_override'] = 'home/loaddefaultpage';
$route['translate_uri_dashes'] = FALSE;
$route['api/store-info'] = 'home/getStoreDetailsApi';
// Questions Routes
$route['questions'] = 'welcome/openquestions';
$route['questions/add'] = 'welcome/addquestion_ajax';
$route['questions/edit/(:num)'] = 'welcome/getquestion_ajax/$1';
$route['questions/update'] = 'welcome/updatequestion_ajax';
$route['questions/delete'] = 'welcome/deletequestion_ajax';
$route['questions/topics/(:num)'] = 'welcome/gettopicsbycourse_ajax/$1';
$route['questions/preview/(:num)'] = 'welcome/previewquestion_ajax/$1';

// Topics Routes
$route['topics'] = 'welcome/opentopics';
$route['topics/add'] = 'welcome/addtopic_ajax';
$route['topics/edit/(:num)'] = 'welcome/gettopic_ajax/$1';
$route['topics/update'] = 'welcome/updatetopic_ajax';
$route['topics/delete'] = 'welcome/deletetopic_ajax';

// Courses Routes
$route['courses'] = 'welcome/opencourses';
$route['courses/add'] = 'welcome/addcourse_ajax';
$route['courses/edit/(:num)'] = 'welcome/getcourse_ajax/$1';
$route['courses/update'] = 'welcome/updatecourse_ajax';
$route['courses/delete'] = 'welcome/deletecourse_ajax';
$route['courses/departments/(:num)'] = 'welcome/getdepartmentsbyschool_ajax/$1';

// Departments Routes
$route['departments'] = 'welcome/opendepartments';
$route['departments/add'] = 'welcome/adddepartment_ajax';
$route['departments/edit/(:num)'] = 'welcome/getdepartment_ajax/$1';
$route['departments/update'] = 'welcome/updatedepartment_ajax';
$route['departments/delete'] = 'welcome/deletedepartment_ajax';

// Schools Routes
$route['schools'] = 'welcome/openschools';
$route['schools/add'] = 'welcome/addschool_ajax';
$route['schools/edit/(:num)'] = 'welcome/getschool_ajax/$1';
$route['schools/update'] = 'welcome/updateschool_ajax';
$route['schools/delete'] = 'welcome/deleteschool_ajax';

//Batch Upload Routes
$route['questions/batch'] = 'welcome/batchupload';
$route['questions/batch/upload'] = 'welcome/processbatchupload';
$route['questions/batch/template/(:any)'] = 'welcome/downloadtemplate/$1';
$route['batch/departments/(:num)'] = 'welcome/getdepartmentsforschool/$1';
$route['batch/courses/(:num)/(:num)'] = 'welcome/getcoursesfordepartment/$1/$2';
$route['batch/topics/(:num)'] = 'welcome/gettopicsforcourse/$1';

$route['home/productslist1'] = 'home/productslist1';
$route['home/productslist1/(:num)'] = 'home/productslist1/$1';
$route['sitemap\.xml'] = 'sitemap';


// API Routes
$route['api/questions/credentials'] = 'welcome/api_questions_credentials';
$route['api/questions/data'] = 'welcome/api_questions_data';
$route['api/questions/filters'] = 'welcome/api_questions_filters';
$route['api/questions/(:num)'] = 'welcome/api_question_by_id/$1';

// If you want versioned API routes
$route['api/v1/questions/credentials'] = 'welcome/api_questions_credentials';
$route['api/v1/questions/data'] = 'welcome/api_questions_data';
$route['api/v1/questions/filters'] = 'welcome/api_questions_filters';
$route['api/v1/questions/(:num)'] = 'welcome/api_question_by_id/$1';