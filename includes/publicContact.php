<div class="container-fluid">
	<div class="row form-text" style="text-align: justify">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nec tempor neque. Proin libero lorem, placerat in accumsan id, imperdiet quis ex. Quisque sit amet urna quis enim ullamcorper luctus quis id nunc. </p>
	</div>
	<div>
		<div>
			<form id="formContact" method="post" class="form-contact">
				<div class="form-group">
					<label for="">ASUNTO</label>
					<input class="form-control" name="asunto" id="asunto" required type="text" placeholder="Ingresa el asunto">
				</div>
				<div class="form-group">
					<label for="">COMENTARIO</label>
					<textarea class="form-control" name="comentario" id="comentario" rows="3" required placeholder="Ingresa la duda que tienes"></textarea>
				</div>
				<div class="form-group">
					<label for="">CORREO</label>
					<input class="form-control" name="correo" id="correo" required type="email" placeholder="Ingresa un correo">
				</div>
				<div class="form-group">
					<div class="row captcha">
						<div class="col-sm-4 col-md-4">
							<input required class="form-control" id="captcha" name="captcha" type="text" placeholder="Ingrese el código">
						</div>
						<div class="col-sm-8 col-md-8">
							<p>*Código de seguridad: <span id="secCode"></span>
							</p>
						</div>
					</div>
				</div>
				<div id="div-msg-ok-cont" hidden="true" class="alert msg-ceel-ok" role="alert">
					<i class="fa fa-check" aria-hidden="true"></i>
					<strong>Hecho!</strong> <i id="div-msg-ok-desc">Información enviada con Éxito</i>
				</div>
				<div id="div-msg-fail-cont" hidden="true" class="alert msg-ceel-fail" role="alert">
					<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
					<i id="div-msg-fail-desc">No se pudo enviar la información</i>
				</div>
				<div class="form-group btn-contact">
					<button type="submit" id="btnEnviar" class="btn btn-primary btn-form-admin">Enviar Mensaje </button>
				</div>
			</form>
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script>
	$( "#secCode" ).text( Math.floor( ( Math.random() * 10000 ) + 1 ) );
	$( "#btnEnviar" ).click( function ( e ) {
		var secCode = $( "#secCode" ).text();
		var secCodeIng = $( "#captcha" ).val();
		if ( secCode != secCodeIng ) {
			alert( "El codigo no coincide." );
			e.preventDefault();
		}
	} );
</script>