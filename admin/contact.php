<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../' );
}
?>
<!DOCTYPE html>
<html>
<?php include("../includes/head.php"); ?>
<body class="bg-2">

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">
			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li><a href="../">Home</a>
					</li>
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active">Contactar al Admin
					</li>
				</ol>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-4 logout">
				<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>
			</div>
		</div>
		<!-- Fin barra de navegación-->

		<!-- Inicio Titulo Página -->
		<div class="row p-texts">
			<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">
				<span class="lnr lnr-bubble"></span>
			</div>
			<div class="col-xs-10 col-sm-11 col-md-11">
				<h3 class="title-p">Contácta al administrador</h3>
				<span class="text-p">Si tienes dudas llena el formulario y te responderemos.</span>
			</div>
		</div>
		<!-- Fin Titulo Página -->
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<form id="formContactAdmin" method="post" class="form-contact">
					<div class="form-group">
						<label for="">ASUNTO</label>
						<input class="form-control" id="asunto" name="asunto" type="text" placeholder="Ingresa el asunto" required>
					</div>
					<div class="form-group">
						<label for="">COMENTARIO</label>
						<textarea class="form-control" rows="3" id="comentario" name="comentario" placeholder="Ingresa la duda que tienes" required></textarea>
					</div>					
					<input type="hidden" name="correo" id="correo" value="<?php echo $_SESSION[ 'dataSession' ]['correo']; ?>"> 
					<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i>
					  <strong>Hecho!</strong> Información Enviada con Éxito
					</div>
					<div id="div-msg-fail" hidden="true" class="alert msg-ceel-fail" role="alert">
						<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong> No se pudo enviar la información
					</div>
					<div class="form-group btn-contact">
						<button type="submit" class="btn btn-primary btn-form-admin">Enviar Mensaje </button>
					</div>	
				</form>
			</div>
			<div class="col-md-3"></div>
			</div>
		</div>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
</html>
<script>
	jQuery(document).on('submit','#formContactAdmin', function(event){
	 $.ajax({
        url: '/sendMail.php?action=contactar',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);
			$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();
				},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
</script>