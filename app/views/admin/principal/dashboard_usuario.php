<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'admin/header' ); ?>
<?php $this->load->view( 'navbar' ); ?>




<div class="container intro" style="padding-top:50px; padding-bottom:50px; margin-top:50px; margin-bottom:50px; background-color: #00427a !important;">

			<div class="row">								
				<div class="col-md-6">
					<img src="<?php echo $this->session->userdata('c4'); ?>" class="img-responsive">
					<h1 style="color:#ffffff">Bienvenido a <?php echo $this->session->userdata('c2'); ?></h1>
					<p style="color:#ffffff">Selecciona una de las opciones en el menù de arriba.</p>
				</div>
			</div>				  
		</div>


<?php $this->load->view( 'admin/footer' ); ?>