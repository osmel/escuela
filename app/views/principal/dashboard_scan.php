<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?php echo base_url(); ?>/js/scan/bootstrap.min.css">

<title>QR Code Scanner Example</title>
</head>
<body>
	
	<div class="container" style="text-align: center">
		<h1> QR Code scanner Example </h1>
		<br><br>

		<div id="qr" style="display: inline-block; width: 600px; height: 450px; border: 1px solid silver"></div>
		<br><br>

		<div class="row">
			<button id="scan" class="btn btn-success btn-sm">start scaning</button>
			<button id="stop" class="btn btn-warning btn-sm disabled">stop scanning</button>
			<button id="change" class="btn btn-warning btn-sm disabled">change camera</button>
		</div>
		<br><br>
		<div class="row">
			<div class="col-md-12">
				<code>Start Scanning</code> <span class="feedback"></span>
			</div>
		</div>
	</div>


</body>

<script type="text/javascript" src="<?php echo base_url(); ?>js/scan/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/scan/jsqrcode-combined.js"></script>
			<script type="text/javascript" src="<?php echo base_url(); ?>js/scan/html5-qrcode.js"></script>	
<script>
jQuery(document).ready(function($) {


	jQuery(document).ready(function() {
		jQuery("#scan").on('click', function() {
			jQuery("code").html('scanning');
			jQuery('#qr').html5_qrcode(function(data){
			         // do something when code is read
			         jQuery(".feedback").html('code scanned as: ' +data);
			    },
			    function(error){
			        //show read errors 
			        jQuery(".feedback").html('Unable to scan the code! Error: ' +error)
			    }, function(videoError){
			        //the video stream could be opened
			        jQuery(".feedback").html('Video error');
			    }
			);

			jQuery("#scan").addClass('disabled');
			jQuery("#stop").removeClass('disabled');
			jQuery("#change").removeClass('disabled');
		});
		jQuery("#stop").on('click', function() {
			jQuery("#qr").html5_qrcode_stop();
			jQuery("code").html('Start Scanning');

			jQuery("#scan").removeClass('disabled');
			jQuery("#stop").addClass('disabled');
			jQuery("#change").addClass('disabled');
		});
		jQuery("#change").on('click', function() {
			jQuery("#qr").html5_qrcode_changeCamera();

			jQuery("#scan").addClass('disabled');
			jQuery("#stop").removeClass('disabled');
		});
	});


});
	
</script>
</html>