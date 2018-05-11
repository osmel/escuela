<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="row-fluid">
	<div class="navbar navbar-default navbar-custom" role="navigation">
		<div class="container">			
	 
				<div class="navbar-brand">
					<a href="<?php echo base_url(); ?>" style="color: #ffffff;"><i class="glyphicon glyphicon-home"></i></a>
				</div>

				<div class="navbar-header">
			      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			    </div>

					<?php 
						  $perfil= $this->session->userdata('id_perfil'); 
						  $especial= $this->session->userdata('especial'); 

					 ?>	
					
			
				<div class="collapse navbar-collapse" id="main-navbar">
					<ul class="nav navbar-nav navbar-left" id="menu_opciones">
					
						<?php if  ( ($especial!=3) ) { ?>	

								<li  class="dropdown dropdown-user">
			                        <a href="javascript:;" class="dropdown-toggle color-blanco" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
			                            <span class="username username-hide-on-mobile color-blanco"> <i class="fa fa-angle-down"></i> Catalogos </span>
			                            
			                        </a>
			                        <ul class="dropdown-menu dropdown-menu-default">
			                            <li >
											<a title="Catalogo de secciones." href="<?php echo base_url(); ?>secciones" class="ttip color-blanco">Secciones</a> 
										</li> 

										<li >
											<a title="Catalogo de grupos." href="<?php echo base_url(); ?>grupos" class="color-blanco ttip">Grupos</a> 
										</li> 

			                        </ul>
			                    </li>					
								


							


							<?php } ?>					


							<?php if  ( (  $perfil == 1  ) AND ($especial!=3) ) { ?>	
								<li>
									<a href="<?php echo base_url(); ?>usuarios" class="color-blanco">usuarios</a>
								</li>

							<?php } ?>					



						<?php  if ($this->session->userdata('session')) {  ?>	 
							<li>
								<a href="<?php echo base_url(); ?>salir" class="color-blanco">Salir <i class="glyphicon glyphicon-log-out"></i></a>
							</li>
						<?php } ?>						

					</ul>
				</div>
	 
		</div>
	</div>
</div>