<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

	public function __construct(){ 
		parent::__construct();

		$this->load->model('admin/modelo', 'modelo'); 
		$this->load->model('admin/catalogo', 'catalogo'); 
		$this->load->library(array('email')); 
	}


//***********************secciones**********************************//

  

  
  public function listado_secciones(){
  

   if ( $this->session->userdata('session') !== TRUE ) {
        redirect('login');
    } else {
        $id_perfil=$this->session->userdata('id_perfil');

        $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
              $coleccion_id_operaciones = array();
         }   


      //$html = $this->load->view( 'catalogos/colores',$data ,true);   
      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'catalogos/secciones');
          break;
        case 2:
        case 3:
        case 4:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )   { 
                $this->load->view( 'catalogos/secciones');
              }  else  {
                redirect('');
              } 
          break;


        default:  
          redirect('');
          break;
      }



    }    
    
  }

 
 public function procesando_cat_secciones(){

    $data=$_POST;
    $busqueda = $this->catalogo->buscador_cat_secciones($data);
    echo $busqueda;
  } 

    // crear
  function nuevo_seccion(){
if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'catalogos/secciones/nuevo_seccion');
          break;
        case 2:
        case 3:
        case 4:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                $this->load->view( 'catalogos/secciones/nuevo_seccion');
              }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('index');
    }
  }

  function validar_nuevo_seccion(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('seccion', 'seccion', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['seccion']   = $this->input->post('seccion');

         $existe            =  $this->catalogo->check_existente_seccion( $data );
         if ( $existe !== TRUE ){

              $data         =   $this->security->xss_clean($data);  
              $guardar            = $this->catalogo->anadir_seccion( $data );
              if ( $guardar !== FALSE ){
                echo true;
              } else {
                echo '<span class="error"><b>E01</b> - La nueva seccion no pudo ser agregada</span>';
              }
         } else {
            echo '<span class="error"><b>E01</b> - La composición que desea agregar ya existe. No es posible agregar dos secciones iguales.</span>';
         }  

      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_seccion( $id = '' ){
     
      if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

            $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
            if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                  $coleccion_id_operaciones = array();
             }   


              $data['id']  = $id;
            switch ($id_perfil) {    
              case 1:
                    $data['seccion'] = $this->catalogo->coger_seccion($data);
                    if ( $data['seccion'] !== FALSE ){
                        $this->load->view( 'catalogos/secciones/editar_seccion', $data );
                    } else {
                          redirect('');
                    }               
                break;
              case 2:
              case 3:
              case 4:
                   if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                      $data['seccion'] = $this->catalogo->coger_seccion($data);
                      if ( $data['seccion'] !== FALSE ){
                          $this->load->view( 'catalogos/secciones/editar_seccion', $data );
                      } else {
                            redirect('');
                      }       
                   }   
                break;


              default:  
                redirect('');
                break;
            }
          }
          else{ 
            redirect('');
          }      
 
  }


function validacion_edicion_seccion(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'seccion', 'seccion', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['seccion']         = $this->input->post('seccion');
          
          $existe            =  $this->catalogo->check_existente_seccion( $data );
          if ( $existe !== TRUE ){

            $data               = $this->security->xss_clean($data);  
            $guardar            = $this->catalogo->editar_seccion( $data );

            if ( $guardar !== FALSE ){
              echo true;

            } else {
              echo '<span class="error"><b>E01</b> - La nueva  seccion no pudo ser agregada</span>';
            }

         } else {
            echo '<span class="error"><b>E01</b> - La composición que desea agregar ya existe. No es posible agregar dos secciones iguales.</span>';
         }  


      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_seccion($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

            $data['nombrecompleto']   = base64_decode($nombrecompleto);

      switch ($id_perfil) {    
        case 1:
            $data['id']         = $id;
            $this->load->view( 'catalogos/secciones/eliminar_seccion', $data );

          break;
        case 2:
        case 3:
        case 4:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                $data['id']         = $id;
                $this->load->view( 'catalogos/secciones/eliminar_seccion', $data );
             }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('');
    }
  }


  function validar_eliminar_seccion(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_seccion(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la seccion</span>';
    }
  }   
   

	



//////////////////////////////////grupos/////////////////////////////////////////////
//////////////////////////////////grupos/////////////////////////////////////////////
//////////////////////////////////grupos/////////////////////////////////////////////  
  public function listado_grupos(){
  

   if ( $this->session->userdata('session') !== TRUE ) {
        redirect('login');
    } else {
        $id_perfil=$this->session->userdata('id_perfil');

        $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
              $coleccion_id_operaciones = array();
         }   


      //$html = $this->load->view( 'catalogos/colores',$data ,true);   
      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'catalogos/grupos');
          break;
        case 2:
        case 3:
        case 4:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )   { 
                $this->load->view( 'catalogos/grupos');
              }  else  {
                redirect('');
              } 
          break;


        default:  
          redirect('');
          break;
      }



    }    
    
  }

 
 public function procesando_cat_grupos(){

    $data=$_POST;
    $busqueda = $this->catalogo->buscador_cat_grupos($data);
    echo $busqueda;
  } 

    // crear
  function nuevo_grupo(){
if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'catalogos/grupos/nuevo_grupo');
          break;
        case 2:
        case 3:
        case 4:
             if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                $this->load->view( 'catalogos/grupos/nuevo_grupo');
              }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('index');
    }
  }

  function validar_nuevo_grupo(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules('grupo', 'grupo', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      if ($this->form_validation->run() === TRUE){
          $data['grupo']   = $this->input->post('grupo');

         $existe            =  $this->catalogo->check_existente_grupo( $data );
         if ( $existe !== TRUE ){

              $data         =   $this->security->xss_clean($data);  
              $guardar            = $this->catalogo->anadir_grupo( $data );
              if ( $guardar !== FALSE ){
                echo true;
              } else {
                echo '<span class="error"><b>E01</b> - La nueva grupo no pudo ser agregada</span>';
              }
         } else {
            echo '<span class="error"><b>E01</b> - La composición que desea agregar ya existe. No es posible agregar dos grupos iguales.</span>';
         }  

      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }


  // editar
  function editar_grupo( $id = '' ){
     
      if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

            $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
            if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
                  $coleccion_id_operaciones = array();
             }   


              $data['id']  = $id;
            switch ($id_perfil) {    
              case 1:
                    $data['grupo'] = $this->catalogo->coger_grupo($data);
                    if ( $data['grupo'] !== FALSE ){
                        $this->load->view( 'catalogos/grupos/editar_grupo', $data );
                    } else {
                          redirect('');
                    }               
                break;
              case 2:
              case 3:
              case 4:
                   if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                      $data['grupo'] = $this->catalogo->coger_grupo($data);
                      if ( $data['grupo'] !== FALSE ){
                          $this->load->view( 'catalogos/grupos/editar_grupo', $data );
                      } else {
                            redirect('');
                      }       
                   }   
                break;


              default:  
                redirect('');
                break;
            }
          }
          else{ 
            redirect('');
          }      
 
  }


function validacion_edicion_grupo(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      $this->form_validation->set_rules( 'grupo', 'grupo', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');

      if ($this->form_validation->run() === TRUE){
            $data['id']           = $this->input->post('id');
          $data['grupo']         = $this->input->post('grupo');
          
          $existe            =  $this->catalogo->check_existente_grupo( $data );
          if ( $existe !== TRUE ){

            $data               = $this->security->xss_clean($data);  
            $guardar            = $this->catalogo->editar_grupo( $data );

            if ( $guardar !== FALSE ){
              echo true;

            } else {
              echo '<span class="error"><b>E01</b> - La nueva  grupo no pudo ser agregada</span>';
            }

         } else {
            echo '<span class="error"><b>E01</b> - La composición que desea agregar ya existe. No es posible agregar dos grupos iguales.</span>';
         }  


      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

  // eliminar


  function eliminar_grupo($id = '', $nombrecompleto=''){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

      $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
      if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
            $coleccion_id_operaciones = array();
       }   

            $data['nombrecompleto']   = base64_decode($nombrecompleto);

      switch ($id_perfil) {    
        case 1:
            $data['id']         = $id;
            $this->load->view( 'catalogos/grupos/eliminar_grupo', $data );

          break;
        case 2:
        case 3:
        case 4:
              if  ( (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones))  )  { 
                $data['id']         = $id;
                $this->load->view( 'catalogos/grupos/eliminar_grupo', $data );
             }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('');
    }
  }


  function validar_eliminar_grupo(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    $eliminado = $this->catalogo->eliminar_grupo(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la grupo</span>';
    }
  }   
   

	






/////////////////validaciones/////////////////////////////////////////	
	function valid_nacimiento( $str, $campo ){
		if ($this->input->post($campo)){
			$hoy =  new DateTime (date("Y-m-d", strtotime(date("d-m-Y"))) );
			$fecha_nac = new DateTime ( date("Y-m-d", strtotime($this->input->post($campo)) ) );
			$fecha = date_diff($hoy, $fecha_nac);
			if ( ($fecha->y>=5) && ($fecha->y<=150) ) {
				return true;
			} else {
				$this->form_validation->set_message( 'valid_nacimiento',"<b class='requerido'>*</b> Su <b>%s</b> debe ser mayor a 18 años." );	
				return false;
			}

		} else {
			$this->form_validation->set_message( 'valid_nacimiento',"<b class='requerido'>*</b> Es obligatorio <b>%s</b>." );
			return false;
		}	

	}


	public function valid_cero($str)
	{
		return (  preg_match("/^(0)$/ix", $str)) ? FALSE : TRUE;
	}

	function nombre_valido( $str ){
		 $regex = "/^([A-Za-z ñáéíóúÑÁÉÍÓÚ]{2,60})$/i";
		//if ( ! preg_match( '/^[A-Za-zÁÉÍÓÚáéíóúÑñ \s]/', $str ) ){
		if ( ! preg_match( $regex, $str ) ){			
			$this->form_validation->set_message( 'nombre_valido','<b class="requerido">*</b> La información introducida en <b>%s</b> no es válida.' );
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function valid_phone( $str ){
		if ( $str ) {
			if ( ! preg_match( '/\([0-9]\)| |[0-9]/', $str ) ){
				$this->form_validation->set_message( 'valid_phone', '<b class="requerido">*</b> El <b>%s</b> no tiene un formato válido.' );
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	function valid_option( $str ){
		if ($str == 0) {
			$this->form_validation->set_message('valid_option', '<b class="requerido">*</b> Es necesario que selecciones una <b>%s</b>.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function valid_date( $str ){

		$arr = explode('-', $str);
		if ( count($arr) == 3 ){
			$d = $arr[0];
			$m = $arr[1];
			$y = $arr[2];
			if ( is_numeric( $m ) && is_numeric( $d ) && is_numeric( $y ) ){
				return checkdate($m, $d, $y);
			} else {
				$this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD-MM-YYYY.');
				return FALSE;
			}
		} else {
			$this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD/MM/YYYY.');
			return FALSE;
		}
	}

	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

	////Agregado por implementacion de registro insitu para evento/////
	public function opcion_valida( $str ){
		if ( $str == '0' ){
			$this->form_validation->set_message('opcion_valida',"<b class='requerido'>*</b>  Selección <b>%s</b>.");
			return FALSE;
		} else {
			return TRUE;
		}
	}


}

/* End of file main.php */
/* Location: ./app/controllers/main.php */