<!DOCTYPE html>

<html lang="en">

<?php include("./includes/head.php"); ?>

<script src="./js/jquery-3.2.1.js"></script>

<script src="./js/login.js"></script>



<body>

	<?php include("./includes/header.php");?>



	<div class="container-fluid b-login">

		<div class="row">



			<!-- Inicio Columna Vacia-->

			<div class="col-sm-12 col-md-4"></div>

			<!-- Fin Columna Vacia-->



			<!-- Inicio Columna Formulario-->

			<div class="col-sm-6 col-md-4">

				<div class="">

					<form class="form-login" id="formLogin" method="post">

						<h3>Ingresa tus datos</h3>

						<hr/>

						<div class="form-group">

							<label for="correo">Correo</label>

							<input class="form-control" name="correo" id="correo" type="email" required placeholder="Ingresa tu Correo">

						</div>

						<div class="form-group">

							<label for="password">Contraseña</label>

							<input class="form-control" name="password" id="password" type="password" required placeholder="Ingresa la contraseña">

						</div>

						<div class="form-group">

							<a href="#" data-toggle="modal" data-target="#recordPassModal"><h4>¿Olvidó su contraseña?</h4></a>

						</div>

						<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">

							<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>

							<i id="div-msg-fail-desc">No se pudo ingresar.</i>

						</div>

						<div class="form-group">

							<button id="btnIngresar" type="submit" class="btn btn-warning">Ingresar</button>

						</div>



					</form>

					<div class="contact-admin">					

					<a href="#" data-toggle="modal" data-target="#contactModal">Contactar al Admin <img src="assets/img/icon-right.png" alt=""></a>

					</div>

					<!-- Modal Recordar contrasena -->

					<div class="modal fade" id="recordPassModal" tabindex="-1" role="dialog" aria-labelledby="recordPassModal">

						<div class="modal-dialog" role="document">

							<div class="modal-content">

								<div class="modal-header">

									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

									<h3 style="color: #5d5d5d;">Recordar Contraseña</h3>

								</div>

								<form action="" class="form-login" id="formRecord" method="post">

									<div class="form-group">

										<label for="correo">Correo</label>

										<input class="form-control" name="correo" id="correo" type="email" required placeholder="Ingresa tu Correo">

									</div>

									<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" 	role="alert">

											<i class="fa fa-check" aria-hidden="true"></i>

										  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Contraseña enviada con Éxito</i>

									</div>

									<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">

											<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>

											<i id="div-msg-fail-desc">No se pudo enviar la Contraseña</i>

									</div>

									<div class="form-group">

										<button id="btnIngresar" type="submit" class="btn btn-warning">RECORDAR</button>

									</div>

								</form>

							</div>

						</div>

					</div>

					<!-- Fin Modal Recordar contrasena -->

					<!-- Modal Contacto -->

					<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModal">

						<div class="modal-dialog" role="document">

							<div class="modal-content">



								<div class="modal-header">

									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

								</div>

								<div style="padding-left: 5px;" class="row p-texts">

									<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">

										<span class="lnr lnr-bubble"></span></span>

									</div>

									<div class="col-xs-10 col-sm-11 col-md-11">

										<h3 class="title-p">Contácta al administrador</h3>

										<span class="text-p">Si tienes dudas llena el formulario y te responderemos.</span>									

									</div>

								</div>

								<!-- Include gen-contact page-->

								<?php include("./includes/publicContact.php"); ?>

							</div>

						</div>

					

					</div>

					<!-- Fin Modal Contacto -->

				</div>

			</div>

			<!-- Fin Columna Formulario-->



			<!-- Inicio Columna Contenido-->

			<div class="col-sm-6 col-md-4">

				<div class="content-login">

					<h3>APRENDE ONLINE Y CERTIFÍCATE</h3>

					<img src="./assets/img/img-line-login.png" class="img-responsive" alt="Responsive image">

					<p>Fácil desde cualquier lugar haz seguimiento del avance en tu formación, ingresa ya!!</p>

					<ul class="list-unstyled list-login">

						<li>* Desde tu dispositivo móvil, tablet o portatil disponible.</li>

						<li>* Sigue tus avances, Descarga tus certificados.</li>

						<li>* Facíl y rápido</li>

					</ul>

				</div>

			</div>

			<!-- Fin Columna Contenido-->

		</div>

	</div>

	<!-- Include Footer-->

	<?php include("./includes/footer.php"); ?>

</body>

</html>

<script>

	$( '#contactModal' ).on( 'show.bs.modal', function ( e ) {

		$( 'body' ).css( 'padding-right', '0' );

	} );

	$( '#recordPassModal' ).on( 'show.bs.modal', function ( e ) {

		$( 'body' ).css( 'padding-right', '0' );

	} );

</script>

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