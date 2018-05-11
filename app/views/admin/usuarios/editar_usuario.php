<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'admin/header' ); ?>
<?php $this->load->view( 'navbar' ); ?>
<?php 
	if (!isset($retorno)) {
      	$retorno ="/escuela/usuarios";
    }
  $hidden = array('id_p'=>$id);
  $attr = array('class' => 'form-horizontal', 'id'=>'form_usuarios','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
  echo form_open('validacion_edicion_usuario', $attr,$hidden);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8 col-md-8"><h4>Edición de Usuario</h4></div>
	</div>
	<br>
	<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del Usuario</div>
			<div class="panel-body">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label for="matricula" class="col-sm-3 col-md-2 control-label">Matricula</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->matricula)) 
								 {	$nomb_nom = $usuario->matricula;}
							?>
							<input value="<?php echo  set_value('matricula',$nomb_nom); ?>" type="text" class="form-control" name="matricula" placeholder="Matricula">
						</div>
					</div>


					<div class="form-group">
						<label for="nombre" class="col-sm-3 col-md-2 control-label">Nombre</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->nombre)) 
								 {	$nomb_nom = $usuario->nombre;}
							?>
							<input value="<?php echo  set_value('nombre',$nomb_nom); ?>" type="text" class="form-control" name="nombre" placeholder="Nombre">
						</div>
					</div>
					<div class="form-group">
						<label for="apellidos" class="col-sm-3 col-md-2 control-label">Apellido(s)</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->apellidos)) 
								 {	$nomb_nom = $usuario->apellidos;}
							?>
							<input value="<?php echo  set_value('apellidos',$nomb_nom); ?>" type="text" class="form-control" name="apellidos" placeholder="Apellido (s)">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 col-md-2 control-label">Email</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->email)) 
								 {	$nomb_nom = $usuario->email;}
							?>
							<input value="<?php echo  set_value('email',$nomb_nom); ?>" type="text" class="form-control" name="email" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="telefono" class="col-sm-3 col-md-2 control-label">Número Teléfono</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->telefono)) 
								 {	$nomb_nom = $usuario->telefono;}
							?>
							<input value="<?php echo  set_value('telefono',$nomb_nom); ?>" type="text" class="form-control" name="telefono" placeholder="Número Teléfono">
						</div>
					</div>

					<div class="form-group">
						<label for="direccion" class="col-sm-3 col-md-2 control-label">Dirección</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->direccion)) 
								 {	$nomb_nom = $usuario->direccion;}
							?>
							<input value="<?php echo  set_value('direccion',$nomb_nom); ?>" type="text" class="form-control" name="direccion" placeholder="Dirección">
						</div>
					</div>


				</div>
				<div class="col-sm-6 col-md-6">

					<div class="form-group">
						<label for="fecha_nac" class="col-sm-3 col-md-2 control-label">Fec. Nac:</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->fecha_nac)) 
								 {	$nomb_nom = $usuario->fecha_nac;}
							?>

						  <input value="<?php echo  set_value('fecha_nac',$nomb_nom); ?>" type="hidden" id="fecha_nac"  class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="pass_1" class="col-sm-3 col-md-2 control-label">Contraseña</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->contrasena)) 
								 {	$nomb_nom = $usuario->contrasena;}
							?>
							<input value="<?php echo  set_value('pass_1',$nomb_nom); ?>" type="password" class="form-control" name="pass_1" placeholder="Contraseña">
						</div>
					</div>
					<div class="form-group">
						<label for="pass_2" class="col-sm-3 col-md-2 control-label">Confirmar Contraseña</label>
						<div class="col-sm-9 col-md-10">
							<?php 
								$nomb_nom='';
								if (isset($usuario->contrasena)) 
								 {	$nomb_nom = $usuario->contrasena;}
							?>
							<input value="<?php echo  set_value('pass_2',$nomb_nom); ?>" type="password" class="form-control" name="pass_2" placeholder="Contraseña">
							
						</div>
					</div>

					<div class="form-group">
						<label for="id_perfil" class="col-sm-3 col-md-2 control-label">Rol de usuario</label>
						<div class="col-sm-9 col-md-10">
						<?php  if ( $this->session->userdata( 'id_perfil' ) != 1 ){ ?>											
							<fieldset disabled>
								<select name="id_perfil" id="id_perfil" class="form-control">
									<?php foreach ( $perfiles as $perfil ){ ?>
										<?php 
										   if  ($perfil->id_perfil==$usuario->id_perfil)
											 {$seleccionado='selected';} else {$seleccionado='';}
										?>
										<option value="<?php echo $perfil->id_perfil; ?>" <?php echo $seleccionado; ?> ><?php echo $perfil->perfil; ?></option>
									<?php } ?>
								</select>
							</fieldset>		
					    <?php } elseif ( $this->session->userdata( 'id_perfil' ) == 1 ){ ?>											
								<select name="id_perfil" id="id_perfil" class="form-control">
									<?php foreach ( $perfiles as $perfil ){ ?>
										<?php 
										   if  ($perfil->id_perfil==$usuario->id_perfil)
											 {$seleccionado='selected';} else {$seleccionado='';}
										?>
										<option value="<?php echo $perfil->id_perfil; ?>" <?php echo $seleccionado; ?> ><?php echo $perfil->perfil; ?></option>
									<?php } ?>
								</select>
					    <?php } ?>									    
						</div>
					</div>

				<div class="form-group">
						<label for="id_perfil" class="col-sm-3 col-md-2 control-label">Sección</label>
						<div class="col-sm-9 col-md-10">
								<select name="id_seccion" id="id_seccion" class="form-control">

										<?php foreach ( $secciones as $seccion ){ ?>
												<?php 
												   if  ($seccion->id==$usuario->id_seccion)
												   {$seleccionado='selected';} else {$seleccionado='';}
												?>
												
												<option value="<?php echo $seccion->id; ?>" <?php echo $seleccionado; ?>><?php echo $seccion->nombre; ?></option>
												
										<?php } ?>
								</select>
								 
							
						</div>
					</div>	

				<div class="form-group">
						<label for="id_perfil" class="col-sm-3 col-md-2 control-label">Grupo</label>
						<div class="col-sm-9 col-md-10">
								<select name="id_grupo" id="id_grupo" class="form-control">
										<?php foreach ( $grupos as $grupo ){ ?>
												<?php 
												   if  ($grupo->id==$usuario->id_grupo)
												   {$seleccionado='selected';} else {$seleccionado='';}
												?>
												<option value="<?php echo $grupo->id; ?>"  <?php echo $seleccionado; ?>><?php echo $grupo->nombre; ?></option>
												
										<?php } ?>
								</select>
								 
							
						</div>
					</div>	


				</div>
			</div>
		</div>



		<br>

		<div class="row">
			<div class="col-sm-4 col-md-4"></div>
			<div class="col-sm-4 col-md-4">
				<a href="/<?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Cancelar</a>
			</div>
			<div class="col-sm-4 col-md-4">
				<input style="padding:8px;" type="submit" class="btn btn-success btn-block" value="Guardar"/>
			</div>
		</div>
		
	</div>
</div>
  <?php echo form_close(); ?>
<?php $this->load->view('admin/footer'); ?>