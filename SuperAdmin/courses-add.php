<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$listInstructores = $connect->query( 'SELECT id, nombres, apellidos FROM usuarios WHERE instructor = 1 AND estado = 1 ORDER BY nombres ASC' );
date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html>
<?php include("../includes/head.php"); ?>
<head>
	<meta charset="UTF-8">
	<title>CEEL | Capacitación Empresarial en Línea</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/styles.css">
	<link rel="stylesheet" href="../css/responsive.css">
	<link rel="icon" type="../image/png" href="assets/img/favicon.png"/>
	<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
	<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
	<script src="../js/jquery-3.2.1.js"></script>
	<script src="http://localhost/js/bootstrap.min.js"></script>
</head>
<body class="bg-2">

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active">Crear Curso
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
				<span class="lnr lnr-book"></span>
			</div>
			<div class="col-xs-10 col-sm-8 col-md-8">
				<h3 class="title-p">Crear Curso: Paso #1</h3>
				<span class="text-p">Gestiona los campos para crear un curso:</span>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 pull-right date-courses">
				<div class="date-text">
					Fecha de creación:
					<?php echo date("d/m/Y"); ?>
				</div>
				<div class="date-icon">
					<span class="lnr lnr-calendar-full"></span>
				</div>
			</div>
		</div>
		<!-- Fin Titulo Página -->

		<!-- Inicio Formulario de creación de curso -->
		<div class="row">
			<form class="form-horizontal form-add-course" id="form-create-course" method="post" enctype="multipart/form-data">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-4 control-label">Nombre del curso</label>
						<div class="col-sm-8">
							<input type="text" required id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre del curso">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Subtitulo</label>
						<div class="col-sm-8">
							<input type="text" required id="subtitulo" name="subtitulo" class="form-control" placeholder="Ingrese el subtitulo del curso">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Descripción</label>
						<div class="col-sm-8">
							<textarea class="form-control" required id="descripcion" name="descripcion" rows="3" placeholder="Ingrese la descripción del curso"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha Inicio</label>
						<div class="col-sm-8">
							<input type="date" required id="finicio" name="finicio" class="form-control" placeholder="Ingrese la fecha inicio del curso">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha Fin</label>
						<div class="col-sm-8">
							<input type="date" id="ffin" name="ffin" class="form-control" placeholder="Ingrese la fecha fin del curso">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Asignar Instructor</label>
						<div class="col-sm-8">
							<select class="form-control" required id="instructor" name="instructor">
								<option value="">Seleccione un instructor</option><?php
							while ( $row = mysqli_fetch_array( $listInstructores ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'nombres' ] .' '. $row[ 'apellidos' ] . "</option>";
							}
							?>
								
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Cargar Icono (56x56)</label>
						<div class="col-sm-8">
							<input type="file" id="iconoCurso" name="iconoCurso"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Cargar Contenido (.zip)</label>
						<div class="col-sm-8">
							<input type="file" id="archivo" name="archivo" required/>
						</div>
					</div>					
				</div>
				<div class="col-md-4">
					<img id="blah" width="418px" height="219px" src="../assets/img/no-image-course.png" alt="your image"/>
					<input type='file' id="imgCurso" name="imgCurso" onchange="readURL(this);"/>
				</div>
				<div class="col-md-12 btn-add">
				<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
					<i class="fa fa-check" aria-hidden="true"></i>
				  <strong>Hecho!</strong> Curso Creado con Éxito
				</div>
				<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
					<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
					<i id="div-msg-fail-desc">Ocurrió un error creando el Curso</i>
				</div>
					<div id="loadingDiv"><img src="../assets/img/loadBar.gif"></div>
					<div>
						<input type="hidden" name="optButton" id="optButton">
						<button id="btn_save" type="submit" class="btn btn-default">GUARDAR</button>
						<button id="btn_save_back" type="submit" class="btn btn-default">GUARDAR y VOLVER</button>
					</div>
				</div>
			</form>
		</div>
		<!-- Fin Formulario de creación de curso -->
	
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script type="text/javascript">
	function readURL( input ) {
		if ( input.files && input.files[ 0 ] ) {
			var reader = new FileReader();
			reader.onload = function ( e ) {
				$( '#blah' ).attr( 'src', e.target.result );
			}
			reader.readAsDataURL( input.files[ 0 ] );
		}
	}
	$( "#btn_save" ).click(function() {
	  $( "#optButton" ).val("save");
	});
	$( "#btn_save_back" ).click(function() {
	  $( "#optButton" ).val("save_back");
	});
	
	$('#loadingDiv').hide();
</script>
<?php $connect->close(); ?>
</html>