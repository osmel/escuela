jQuery(document).ready(function($) {


	jQuery(document).ready(function() {
		jQuery("#scan").on('click', function() {
			jQuery("code").html('scanning');
			jQuery('#qr').html5_qrcode(function(datoScanner){
			         // hacer algo cuando se lee el código
			         jQuery(".feedback").html(': ' +datoScanner);

			         jQuery.ajax({
						url : '/escuela/confirmar_asistencia',
				        data:{
				        	datoScanner:datoScanner
				        },
				        type : 'POST',
				        dataType : 'json',
				        success : function(midata) {
				        	console.log(midata);
				        }
				     });   	


			    },
			    function(error){
			        //mostrar lectura de errores
			        jQuery(".feedback").html('¡No se puede escanear el código! Error: ' +error)
			    }, function(videoError){
			        //El flujo(stream) de video no pudo ser abierto
			        jQuery(".feedback").html('error de Video');
			    }
			);

			jQuery("#scan").addClass('disabled');
			jQuery("#stop").removeClass('disabled');
			jQuery("#change").removeClass('disabled');
		});
		jQuery("#stop").on('click', function() {
			jQuery("#qr").html5_qrcode_stop();
			jQuery("code").html('Comienzo de scanner');

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