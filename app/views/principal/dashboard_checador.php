<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<?php $this->load->view( 'navbar' ); ?>


<div class="container intro" >

			<div class="row">								
				<div class="col-md-6">
					

					<div id="qr" style="display: inline-block; width: 500px; height: 350px; border: 1px solid silver"></div>
					<br><br>


					

				</div>


				<div class="col-md-6">
					<div class="row">
						<button id="scan" class="btn btn-success btn-sm">comenzar scan</button>
						<button id="stop" class="btn btn-warning btn-sm disabled">parar scan</button>
						<button id="change" class="btn btn-warning btn-sm disabled">cambiar camera</button>
					</div>
					
					<div class="col-md-12">
							<code>Feedback</code> <span class="feedback"></span>
						</div>
					</div>
				</div>

			</div>				  
</div>


<?php $this->load->view( 'footer' ); ?>


	
