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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'welcome';

$route['api/get_kelas_all'] = 'Kelas_Controller/get_kelas_all';
$route['api/get_kelas_by_id/(:any)'] = 'Kelas_Controller/get_kelas_by_id/$1';
$route['api/add_kelas'] = 'Kelas_Controller/add_kelas';
$route['api/update_kelas'] = 'Kelas_Controller/update_kelas';
$route['api/delete_kelas/(:any)'] = 'Kelas_Controller/delete_kelas/$1';

$route['api/get_dosen_all'] = 'Dosen_Controller/get_dosen_all';
$route['api/get_dosen_by_id/(:any)'] = 'Dosen_Controller/get_dosen_by_id/$1';
$route['api/add_dosen'] = 'Dosen_Controller/add_dosen';
$route['api/update_dosen'] = 'Dosen_Controller/update_dosen';
$route['api/delete_dosen/(:any)'] = 'Dosen_Controller/delete_dosen/$1';

$route['api/get_ruangan_all'] = 'Ruangan_Controller/get_ruangan_all';
$route['api/get_ruangan_by_id/(:any)'] = 'Ruangan_Controller/get_ruangan_by_id/$1';
$route['api/add_ruangan'] = 'Ruangan_Controller/add_ruangan';
$route['api/update_ruangan'] = 'Ruangan_Controller/update_ruangan';
$route['api/delete_ruangan/(:any)'] = 'Ruangan_Controller/delete_ruangan/$1';

$route['api/get_mahasiswa_all'] = 'Mahasiswa_Controller/get_mahasiswa_all';
$route['api/get_mahasiswa_by_id/(:any)'] = 'Mahasiswa_Controller/get_mahasiswa_by_id/$1';
$route['api/add_mahasiswa'] = 'Mahasiswa_Controller/add_mahasiswa';
$route['api/update_mahasiswa'] = 'Mahasiswa_Controller/update_mahasiswa';
$route['api/delete_mahasiswa/(:any)'] = 'Mahasiswa_Controller/delete_mahasiswa/$1';

$route['api/get_user_by_email'] = 'User_Controller/get_user_by_email';
$route['api/get_user_all'] = 'User_Controller/get_user_all';
$route['api/add_user'] = 'User_Controller/add_user';
$route['api/update_user'] = 'User_Controller/update_user';
$route['api/delete_user'] = 'User_Controller/delete_user';

$route['api/find_recom'] = 'Sidang_Controller/find_recom';