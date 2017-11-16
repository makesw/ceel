jQuery(document).on('submit','#formLogin', function(event){
	event.preventDefault();	
	jQuery.ajax({
		url: 'login.php',
		type:'POST',
		dataType: 'json',
		data: $(this).serialize(),
		beforeSend: function(){
			$('#btnIngresar').val('Validando...');
		}		
	})
	.done(function(respuesta){	  
		if( !respuesta.error ){
			if( respuesta.perfil == 'SuperAdmin' ){
				location.href= './SuperAdmin/';
			}else if( respuesta.perfil == 'Admin' ){
				location.href= './Admin/';
			}else if( respuesta.perfil == 'Usuario' ){
				location.href= './Usuario/';
			}
		}else{
			$('#div-msg-fail').show();
			setTimeout(function(){
				$('#div-msg-fail').hide();
			},3000);
		}
	})
	.fail(function(resp){
		console.log(resp.responseText);	  
		$('#div-msg-fail').show();
		setTimeout(function(){
			$('#div-msg-fail').hide();
		},3000);
	})
	.always(function(respuesta){ 
		console.log(respuesta);	
	})
});