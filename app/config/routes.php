<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/*
$route['default_controller'] = "aspirantes/index";
$route['404_override'] 		 = '';
*/


$route['default_controller']   = 'main/dashboard_principal';
$route['404_override'] 		   = '';



//////////////////////////////////Administracion////////////////////////////////////////

$route['admin']							= 'main/index';
$route['login']							= 'main/login';


$route['usuarios']						= 'main/listado_usuarios';
$route['procesando_usuarios']			= 'main/procesando_usuarios';


	/* necesita server de correo, para que notifique quien se da de alta*/
$route['nuevo_usuario']                 = 'main/nuevo_usuario';
$route['validar_nuevo_usuario']         = 'main/validar_nuevo_usuario';

$route['actualizar_perfil']		         = 'main/actualizar_perfil';
$route['editar_usuario/(:any)']			= 'main/actualizar_perfil/$1';
$route['validacion_edicion_usuario']    = 'main/validacion_edicion_usuario';

$route['eliminar_usuario/(:any)']		= 'main/eliminar_usuario/$1';
$route['validar_eliminar_usuario']    = 'main/validar_eliminar_usuario';


$route['salir']							= 'main/logout';

$route['validar_login']					= 'main/validar_login';


//recuperar contraseña /* necesita server de correo*/
$route['recuperar_contrasena']			= 'main/recuperar_contrasena';
$route['validar_recuperar_password']	= 'main/validar_recuperar_password';


//solo faltan estos modulos
			//historicos de accesos
				$route['historico_accesos']                 = 'main/historico_accesos';
	 $route['procesando_historico_accesos']                 = 'main/procesando_historico_accesos';
			
			//respaldar informacion	
			$route['respaldar']					= 'respaldo/respaldar';


//////////////////////////////////catalogos////////////////////////////////////////
//////////////////////////////////catalogos////////////////////////////////////////
//////////////////////////////////catalogos////////////////////////////////////////


//seccion
$route['secciones']					     = 'catalogos/listado_secciones';

$route['nuevo_seccion']                  = 'catalogos/nuevo_seccion';
$route['validar_nuevo_seccion']          = 'catalogos/validar_nuevo_seccion';

$route['editar_seccion/(:any)']			 = 'catalogos/editar_seccion/$1';
$route['validacion_edicion_seccion']     = 'catalogos/validacion_edicion_seccion';

$route['eliminar_seccion/(:any)/(:any)'] = 'catalogos/eliminar_seccion/$1/$2';
$route['validar_eliminar_seccion']    	 = 'catalogos/validar_eliminar_seccion';
$route['procesando_cat_secciones']    = 'catalogos/procesando_cat_secciones';

//grupo
$route['grupos']					     = 'catalogos/listado_grupos';

$route['nuevo_grupo']                  = 'catalogos/nuevo_grupo';
$route['validar_nuevo_grupo']          = 'catalogos/validar_nuevo_grupo';

$route['editar_grupo/(:any)']			 = 'catalogos/editar_grupo/$1';
$route['validacion_edicion_grupo']     = 'catalogos/validacion_edicion_grupo';

$route['eliminar_grupo/(:any)/(:any)'] = 'catalogos/eliminar_grupo/$1/$2';
$route['validar_eliminar_grupo']    	 = 'catalogos/validar_eliminar_grupo';
$route['procesando_cat_grupos']    = 'catalogos/procesando_cat_grupos';



//////////////////////////////////alumnos////////////////////////////////////////
//////////////////////////////////alumnos////////////////////////////////////////
//////////////////////////////////alumnos////////////////////////////////////////

$route['confirmar_asistencia']    = 'alumnos/confirmar_asistencia';
/*$route['confirmar_asistencia']    = 'alumnos/confirmar_asistencia';*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */