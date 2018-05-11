<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class modelo extends CI_Model{
		
		private $key_hash;
		private $timezone;

		function __construct(){
			parent::__construct();
			$this->load->database("default");
			$this->key_hash    = $_SERVER['HASH_ENCRYPT'];
			$this->timezone    = 'UM1';

				//usuarios
		      $this->usuarios             = $this->db->dbprefix('usuarios');
          $this->perfiles             = $this->db->dbprefix('perfiles');

          $this->configuraciones      = $this->db->dbprefix('catalogo_configuraciones');
          
          
          $this->historico_acceso     = $this->db->dbprefix('historico_acceso');


          $this->catalogo_secciones     = $this->db->dbprefix('catalogo_seccion');
          $this->catalogo_grupos     = $this->db->dbprefix('catalogo_grupo');


          

		}


        public function listado_configuraciones(){

            $this->db->select('c.id, c.configuracion, c.valor, c.activo');
            $this->db->from($this->configuraciones.' as c');
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }     




        public function listado_usuarios_correo( $id_perfil ){

            $this->db->select($this->usuarios.'.id, nombre,  apellidos');
            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->from($this->usuarios);
            $this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');

           // $this->db->where($this->usuarios.'.especial !=', 2);  //quitar en caso de no super-administrador
            //$this->db->where($this->usuarios.'.id_perfil', $id_perfil+1);
            //$this->db->or_where($this->usuarios.'.id_perfil', 1);  //quitar en caso de no super-administrador
            


          $where = '(
                     (
                        ('.$this->usuarios.'.especial <> 2 ) AND ('.$this->usuarios.'.especial <> 3 ) AND ('.$this->usuarios.'.id_perfil='.($id_perfil+1).')
                     ) OR ('.$this->usuarios.'.id_perfil=1)
            )';   
            


          $this->db->where($where);






            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }     



		//login
		public function check_login($data){
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->select("AES_DECRYPT(contrasena,'{$this->key_hash}') AS contrasena", FALSE);			
			$this->db->select($this->usuarios.'.nombre,'.$this->usuarios.'.apellidos');			
			$this->db->select($this->usuarios.'.id,'.$this->perfiles.'.id_perfil,'.$this->perfiles.'.perfil,'.$this->perfiles.'.operacion');
            $this->db->select($this->usuarios.'.especial');         

                
			$this->db->from($this->usuarios);
			$this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');
			$this->db->where('contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);


			$login = $this->db->get();

			if ($login->num_rows() > 0)
				return $login->result();
			else 
				return FALSE;
			$login->free_result();
		}

        //anadir al historico de acceso
        public function anadir_historico_acceso($data){

            $timestamp = time();
            $ip_address = $this->input->ip_address();
            $user_agent= $this->input->user_agent();

            $this->db->set( 'email', "AES_ENCRYPT('{$data->email}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data->id_perfil);

            $this->db->set( 'id_usuario', $data->id);
            $this->db->set( 'fecha',  gmt_to_local( $timestamp, 'UM1', TRUE) );
            $this->db->set( 'ip_address',  $ip_address, TRUE );
            $this->db->set( 'user_agent',  $user_agent, TRUE );
            

            $this->db->insert($this->historico_acceso );

            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();

        }

       public function total_acceso($limit=-1, $offset=-1){

            $fecha = date_create(date('Y-m-j'));
            date_add($fecha, date_interval_create_from_date_string('-1 month'));
            $data['fecha_inicial'] = date_format($fecha, 'm');
            $data['fecha_final'] = $data['fecha_final'] = (date('m'));


            $this->db->from($this->historico_acceso.' As h');
            $this->db->join($this->usuarios.' As u' , 'u.id = h.id_usuario','LEFT');
            $this->db->join($this->perfiles.' As p', 'u.id_perfil = p.id_perfil','LEFT');

            if  (($data['fecha_inicial']) and ($data['fecha_final'])) {
                $this->db->where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_inicial'] );
                $this->db->or_where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_final'] );
            } 

              

           $unidades = $this->db->get();            
           return $unidades->num_rows();
        }

       


		
		//Recuperar contraseña		
	    public function recuperar_contrasena($data){
			$this->db->select("AES_DECRYPT(u.email,'{$this->key_hash}') AS email", FALSE);						
			$this->db->select('u.nombre,u.apellidos');
			$this->db->select("AES_DECRYPT(u.telefono,'{$this->key_hash}') AS telefono", FALSE);			
			$this->db->select("AES_DECRYPT(u.contrasena,'{$this->key_hash}') AS contrasena", FALSE);
			$this->db->from($this->usuarios.' as u');
			$this->db->where('u.email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return $login->result();
			else 
				return FALSE;
			$login->free_result();		
	    }	

	
	
   
        public function coger_usuarios($limit=-1, $offset=-1, $uid ){

            $especial=$this->session->userdata('especial');

		    $this->db->select($this->usuarios.'.id, nombre,  apellidos');
            

            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->select( "AES_DECRYPT( telefono,'{$this->key_hash}') AS telefono", FALSE );
			$this->db->select($this->perfiles.'.id_perfil,'.$this->perfiles.'.perfil,'.$this->perfiles.'.operacion');
			$this->db->from($this->usuarios);
			$this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');
			$this->db->where( $this->usuarios.'.id !=', $uid );
            if ($especial==3) {
                $this->db->where( $this->usuarios.'.especial =3' );
            }


            if ($limit!=-1) {
                $this->db->limit($limit, $offset); 
            } 
             

			$result = $this->db->get();
			
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        

        //eliminar usuarios
        public function borrar_usuario( $uid ){
            $this->db->delete( $this->usuarios, array( 'id' => $uid ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }



        //editar	
        public function coger_catalogo_usuario( $uid ){
            $this->db->select('id, nombre, apellidos, id_perfil');
            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->select( "AES_DECRYPT( telefono,'{$this->key_hash}') AS telefono", FALSE );
            $this->db->select( "AES_DECRYPT( contrasena,'{$this->key_hash}') AS contrasena", FALSE );



          $this->db->select( "AES_DECRYPT( matricula,'{$this->key_hash}') AS matricula", FALSE );
          $this->db->select( "AES_DECRYPT( direccion,'{$this->key_hash}') AS direccion", FALSE );
          $this->db->select('id_seccion,id_grupo');
          $this->db->select("( CASE WHEN fecha_nac = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(fecha_nac),'%Y-%m-%d') END ) AS fecha_nac", FALSE); 


            $this->db->where('id', $uid);
            $result = $this->db->get($this->usuarios );
            if ($result->num_rows() > 0)
            	return $result->row();
            else 
            	return FALSE;
            $result->free_result();
        }  


		public function check_correo_existente($data){
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->from($this->usuarios);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return FALSE;
			else
				return TRUE;
			$login->free_result();
		}

		public function anadir_usuario( $data ){
            $timestamp = time();

            $id_session = $this->session->userdata('id');
            $this->db->set( 'fecha_pc',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->set( 'id_usuario',  $id_session );

            $this->db->set( 'id', "UUID()", FALSE);
			$this->db->set( 'nombre', $data['nombre'] );
            $this->db->set( 'apellidos', $data['apellidos'] );
            $this->db->set( 'email', "AES_ENCRYPT('{$data['email']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'telefono', "AES_ENCRYPT('{$data['telefono']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data['id_perfil']);

            $this->db->set( 'fecha_nac', strtotime(date( "d-m-Y", strtotime($data['fecha_nac']) )) ,false);
            $this->db->set( 'matricula', "AES_ENCRYPT('{$data['matricula']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'direccion', "AES_ENCRYPT('{$data['direccion']}','{$this->key_hash}')", FALSE );

            $this->db->set( 'id_seccion', $data['id_seccion']);
            $this->db->set( 'id_grupo', $data['id_grupo']);
            

            $this->db->set( 'contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'creacion',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->insert($this->usuarios );

            if ($this->db->affected_rows() > 0){
            		return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
            
        }

		public function check_usuario_existente($data){
			
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->from($this->usuarios);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$this->db->where('id !=',$data['id']);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return FALSE;
			else
				return TRUE;
			$login->free_result();
		}        


        public function edicion_usuario( $data ){

            $timestamp = time();

            $id_session = $this->session->userdata('id');
            $this->db->set( 'fecha_pc',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->set( 'id_usuario',  $id_session );

			$this->db->set( 'nombre', $data['nombre'] );
            $this->db->set( 'apellidos', $data['apellidos'] );
            $this->db->set( 'email', "AES_ENCRYPT('{$data['email']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'telefono', "AES_ENCRYPT('{$data['telefono']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data['id_perfil']);


            $this->db->set( 'fecha_nac', strtotime(date( "d-m-Y", strtotime($data['fecha_nac']) )) ,false);
            $this->db->set( 'matricula', "AES_ENCRYPT('{$data['matricula']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'direccion', "AES_ENCRYPT('{$data['direccion']}','{$this->key_hash}')", FALSE );

            $this->db->set( 'id_seccion', $data['id_seccion'], FALSE );
            $this->db->set( 'id_grupo', $data['id_grupo'], FALSE );
            
            
            $this->db->set( 'contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE );
            $this->db->where('id', $data['id'] );
            $this->db->update($this->usuarios );
            if ($this->db->affected_rows() > 0) {
				return TRUE;
			}  else
				 return FALSE;
        }		

//----------------**************catalogos-------------------************------------------
        public function coger_catalogo_perfiles(){
            $this->db->select( 'id_perfil, perfil, operacion' );
            $perfiles = $this->db->get($this->perfiles );
            if ($perfiles->num_rows() > 0 )
            	 return $perfiles->result();
            else
            	 return FALSE;
            $perfiles->free_result();
        }	    	

   			    


      public function buscador_usuarios($data){
          
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];


          switch ($columa_order) {
                   case '0':
                        $columna = 'u.nombre';
                     break;
                   case '1':
                        $columna = 'p.perfil';
                     break;
                   case '2':
                        $columna = 'email';
                     break;
                     
                   
                   default:
                        $columna = 'u.nombre';
                     break;
                 }                 

                                      

          //$id_session = $this->db->escape($this->session->userdata('id'));
           $id_session = $this->session->userdata('id');

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          

          $this->db->select('u.id, u.nombre, u.apellidos, u.id_perfil');
          $this->db->select( "AES_DECRYPT( u.email,'{$this->key_hash}') AS email", FALSE );
          $this->db->select( "AES_DECRYPT( u.telefono,'{$this->key_hash}') AS telefono", FALSE );
          $this->db->select( "AES_DECRYPT( u.contrasena,'{$this->key_hash}') AS contrasena", FALSE );
          $this->db->select('p.perfil');

          $this->db->select( "AES_DECRYPT( u.matricula,'{$this->key_hash}') AS matricula", FALSE );
          $this->db->select( "AES_DECRYPT( u.direccion,'{$this->key_hash}') AS direccion", FALSE );
          $this->db->select('s.seccion,g.grupo');

          $this->db->select("( CASE WHEN u.fecha_nac = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(u.fecha_nac),'%d-%m-%Y') END ) AS fecha_nac", FALSE);  

          $this->db->from($this->usuarios.' as u');
          $this->db->join($this->perfiles.' as p', 'u.id_perfil = p.id_perfil');
          $this->db->join($this->catalogo_secciones.' as s', 's.id = u.id_seccion');
          $this->db->join($this->catalogo_grupos.' as g', 'g.id = u.id_grupo');


          $this->db->where( 'u.id !=', $id_session);
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( u.nombre LIKE  "%'.$cadena.'%" ) OR (u.apellidos LIKE  "%'.$cadena.'%") OR (p.perfil LIKE  "%'.$cadena.'%") 
                        OR (  AES_DECRYPT( u.email,"{$this->key_hash}")  LIKE  "%'.$cadena.'%") 
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->perfil,
                                      2=>$row->nombre,
                                      3=>$row->apellidos,
                                      4=>$row->email,
                                      5=>$row->telefono,
                                      
                                      6=>$row->matricula,
                                      7=>$row->direccion,
                                      8=>$row->seccion,
                                      9=>$row->grupo,
                                      10=>$row->fecha_nac,

 

                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_usuarios() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      



        public function total_usuarios(){
            $id_session = $this->session->userdata('id');

            $especial=$this->session->userdata('especial');

            $this->db->from($this->usuarios.' as u');
            $this->db->join($this->perfiles.' as p', 'u.id_perfil = p.id_perfil');

            $this->db->where( 'u.id !=', $id_session );
                           
            
           $total = $this->db->get();            
           return $total->num_rows();
            
        }       






      public function historico_acceso($data){
          
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];


          switch ($columa_order) {
                  case '0':
                        $columna = 'u.nombre';
                     break;
                  case '1':
                        $columna = 'p.perfil';
                     break;
                  case '2':
                        $columna = 'h.email';
                     break;
                  case '3':
                        $columna = 'h.fecha';
                     break;  
                  case '4':
                        $columna = 'h.ip_address';
                     break;  
                  case '5':
                        $columna = 'h.user_agent';
                     break;                      
                   
                   default:
                        $columna = 'u.nombre';
                     break;
                 }                 

                                      

          //$id_session = $this->db->escape($this->session->userdata('id'));
           $id_session = $this->session->userdata('id');

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          




            $this->db->select("AES_DECRYPT(h.email,'{$this->key_hash}') AS email", FALSE);            
            $this->db->select('p.id_perfil, p.perfil, p.operacion');
            $this->db->select('u.nombre,u.apellidos');         
            $this->db->select('h.ip_address, h.user_agent, h.id_usuario');
            $this->db->select("( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%d-%m-%Y %H:%i:%s') END ) AS fecha", FALSE);  

            $this->db->from($this->historico_acceso.' As h');
            $this->db->join($this->usuarios.' As u' , 'u.id = h.id_usuario');
            $this->db->join($this->perfiles.' As p', 'u.id_perfil = p.id_perfil','LEFT');
          
          //filtro de busqueda
       
       
          $where = '(

                      (
                        ( u.nombre LIKE  "%'.$cadena.'%" ) OR (u.apellidos LIKE  "%'.$cadena.'%") OR (p.perfil LIKE  "%'.$cadena.'%") 
                        OR (  AES_DECRYPT( h.email,"{$this->key_hash}")  LIKE  "%'.$cadena.'%") 
                        OR (  DATE_FORMAT(FROM_UNIXTIME(h.fecha),"%d-%m-%Y %H:%i:%s")     LIKE  "%'.$cadena.'%") 
                        OR (h.ip_address LIKE  "%'.$cadena.'%")
                        OR (h.user_agent LIKE  "%'.$cadena.'%")
                       )
            )';   


        

  
  
          $this->db->where($where);
      

          //ordenacion
         $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

         

              if ( $result->num_rows() > 0 ) {
                
                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);
                    

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                     
                                      0=>$row->nombre,
                                      1=>$row->apellidos,
                                      2=>$row->perfil,
                                      3=>$row->email,
                                      4=>$row->fecha,
                                      5=>$row->ip_address,
                                      6=>$row->user_agent,
                                      
                                      
                                      
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_historico_acceso() ), 
                        "recordsFiltered" =>  $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      



        public function total_historico_acceso(){
            $id_session = $this->session->userdata('id');

            $especial=$this->session->userdata('especial');

            $this->db->from($this->historico_acceso.' As h');
            $this->db->join($this->usuarios.' As u' , 'u.id = h.id_usuario');
            $this->db->join($this->perfiles.' As p', 'u.id_perfil = p.id_perfil','LEFT');
          

            //$this->db->where( 'u.id !=', $id_session );
                           
            
           $total = $this->db->get();            
           return $total->num_rows();
            
        }       

  

        //catalogos
        public function get_catalogo_secciones(){
            $this->db->select( 'id, seccion nombre' );
            $resultado = $this->db->get($this->catalogo_secciones );
            if ($resultado->num_rows() > 0 )
               return $resultado->result();
            else
               return FALSE;
            $resultado->free_result();
        } 

        //catalogos
        public function get_catalogo_grupos(){
            $this->db->select( 'id, grupo nombre' );
            $resultado = $this->db->get($this->catalogo_grupos );
            if ($resultado->num_rows() > 0 )
               return $resultado->result();
            else
               return FALSE;
            $resultado->free_result();
        } 




	} 
?>