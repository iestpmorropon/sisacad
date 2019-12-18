<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['login/logout'] 					= 'home/logout';
$route['default_controller'] 			= 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['alumnos/nuevo/regular']			= 'alumnos/regulares';
$route['profile']						= 'home/profile';
$route['alumnos/nuevo/regular/(:any)']			= 'alumnos/regulares/$1';