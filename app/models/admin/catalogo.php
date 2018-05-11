<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class catalogo extends CI_Model{
		
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


public function buscador_cat_secciones($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.seccion';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.seccion');

          $this->db->from($this->catalogo_secciones.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.seccion LIKE  "%'.$cadena.'%")
                        
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
                                      1=>$row->seccion,
                                      2=>0, //self::secciones_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
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


   public function lista_secciones($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.seccion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->catalogo_secciones.' As c', 'p.id_seccion = c.id','LEFT');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );

            $this->db->where('p.id_color', $data['val_color']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


          public function lista_secciones_existente($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.seccion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
            $this->db->join($this->catalogo_secciones.' As c', 'p.id_seccion = c.id');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('m.id_almacen',$data['id_almacen']);
            $this->db->where('m.id_factura',$data['id_factura']);

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   



 //-----------secciones------------------

        public function total_secciones(){
           $this->db->from($this->catalogo_secciones);
           $secciones = $this->db->get();            
           return $secciones->num_rows();
        }

        public function listado_secciones($limit=-1, $offset=-1){

          $this->db->select('c.id, c.seccion');
          $this->db->from($this->catalogo_secciones.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();


            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = 0; //self::secciones_en_uso($row->id);
                 }                 
               return $result->result();
            }             
            else
               return False;
            $result->free_result();
        }        



      public function buscador_secciones($data){
            $this->db->select( 'id' );
            $this->db->select("seccion", FALSE);  
            $this->db->from($this->catalogo_secciones);
            $this->db->like("seccion" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->seccion,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el seccion ya existe
    public function check_existente_seccion($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->catalogo_secciones);
            $this->db->where('seccion',$data['seccion']);  
            
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_seccion( $data ){
              
            $this->db->select("c.id, c.seccion");         
            $this->db->from($this->catalogo_secciones.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_seccion( $data ){
          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'seccion', $data['seccion'] );  

            $this->db->insert($this->catalogo_secciones );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_seccion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'seccion', $data['seccion'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->catalogo_secciones );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar seccion
        public function eliminar_seccion( $data ){
            $this->db->delete( $this->catalogo_secciones, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     


        public function buscar_seccion($data){
            $this->db->distinct();
            $this->db->select("p.id_seccion", FALSE);  
            $this->db->select("co.seccion", FALSE);  
            
            $this->db->from($this->productos.' as p');
            $this->db->join($this->catalogo_secciones.' As co', 'p.id_seccion = co.id','LEFT');
            $this->db->like("p.descripcion" ,$data['producto'],FALSE);
            $this->db->like("p.id_color" ,$data['color'],FALSE);
            $this->db->order_by('co.seccion', 'asc'); 

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "id_seccion"=>$row->id_seccion,
                                      "seccion"=>$row->seccion
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }       



public function buscador_cat_grupos($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.grupo';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.grupo');

          $this->db->from($this->catalogo_grupos.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.grupo LIKE  "%'.$cadena.'%")
                        
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
                                      1=>$row->grupo,
                                      2=>0, //self::grupos_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
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


   public function lista_grupos($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.grupo nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->catalogo_grupos.' As c', 'p.id_grupo = c.id','LEFT');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );

            $this->db->where('p.id_color', $data['val_color']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   


          public function lista_grupos_existente($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.grupo nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
            $this->db->join($this->catalogo_grupos.' As c', 'p.id_grupo = c.id');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('m.id_almacen',$data['id_almacen']);
            $this->db->where('m.id_factura',$data['id_factura']);

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   



 //-----------grupos------------------

        public function total_grupos(){
           $this->db->from($this->catalogo_grupos);
           $grupos = $this->db->get();            
           return $grupos->num_rows();
        }

        public function listado_grupos($limit=-1, $offset=-1){

          $this->db->select('c.id, c.grupo');
          $this->db->from($this->catalogo_grupos.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();


            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = 0; //self::grupos_en_uso($row->id);
                 }                 
               return $result->result();
            }             
            else
               return False;
            $result->free_result();
        }        



      public function buscador_grupos($data){
            $this->db->select( 'id' );
            $this->db->select("grupo", FALSE);  
            $this->db->from($this->catalogo_grupos);
            $this->db->like("grupo" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->grupo,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el grupo ya existe
    public function check_existente_grupo($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->catalogo_grupos);
            $this->db->where('grupo',$data['grupo']);  
            
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_grupo( $data ){
              
            $this->db->select("c.id, c.grupo");         
            $this->db->from($this->catalogo_grupos.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_grupo( $data ){
          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'grupo', $data['grupo'] );  

            $this->db->insert($this->catalogo_grupos );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_grupo( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'grupo', $data['grupo'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->catalogo_grupos );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar grupo
        public function eliminar_grupo( $data ){
            $this->db->delete( $this->catalogo_grupos, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     


        public function buscar_grupo($data){
            $this->db->distinct();
            $this->db->select("p.id_grupo", FALSE);  
            $this->db->select("co.grupo", FALSE);  
            
            $this->db->from($this->productos.' as p');
            $this->db->join($this->catalogo_grupos.' As co', 'p.id_grupo = co.id','LEFT');
            $this->db->like("p.descripcion" ,$data['producto'],FALSE);
            $this->db->like("p.id_color" ,$data['color'],FALSE);
            $this->db->order_by('co.grupo', 'asc'); 

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "id_grupo"=>$row->id_grupo,
                                      "grupo"=>$row->grupo
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }       



	} 
?>