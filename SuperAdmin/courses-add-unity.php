<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$course_id = $_GET[ 'course_id' ];
$course = mysqli_fetch_array( $connect->query( "SELECT nombre, subtitulo,url_foto FROM cursos WHERE id=" . $course_id ) );
$nextNumUnity = mysqli_fetch_array( $connect->query( "SELECT COUNT(1)+1 nextU FROM unidades WHERE id_curso=" . $course_id ) );
date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html>
<?php include("../includes/head.php"); ?>
<body class="bg-2">

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid">
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active"><a href="courses-list.php">Cursos</a>
					</li>
					<li class="active"><a href="courses-edit.php?idCourse=<?php echo $course_id;?>">Curso</a>
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
			<div class="col-xs-10 col-sm-7 col-md-8">
				<h3 class="title-p">Agregar Unidades: Paso #2</h3>
				<span class="text-p">Agrega la cantidad de unidades que nesecita el curso:</span>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 pull-right date-courses">
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
		<form id="form-create-unity" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-12">
					<div class="col-xs-12 col-sm-4 col-md-4 img-p-course">
						<img id="blah" width="300px" height="220px" src="<?php if(isset($course['url_foto'])){echo $course['url_foto'];}else{echo '../assets/img/no-image-course.png';}?>" alt="your image"/>
						<div class="texts-course">
							<p>
								<?php echo strtoupper($course['nombre']); ?>
							</p>
							<p class="subtitle-course">
								<?php echo strtoupper($course['subtitulo']);?>
							</p>
						</div>
					</div>
					<div class="col-xs-12 col-sm-7 col-md-7 add-unity">
						<h3>Unidad #<?php echo $nextNumUnity['nextU']; ?></h3>
						<div class="form-group input-add-course">
							<div class="col-md-4">
								<label for="">Nombre de la unidad</label>
							</div>
							<div class="col-md-8">
								<input class="form-control" type="text" placeholder="Ingrese el nombre de la unidad" id="nombre" name="nombre" required>
							</div>
						</div>
						<div class="form-group input-add-course">
							<div class="col-md-4">
								<label for="">Subtitulo unidad</label>
							</div>
							<div class="col-md-8">
								<input class="form-control" type="text" placeholder="Ingrese el subtitulo de la unidad" id="subtitulo" name="subtitulo" required>
							</div>
						</div>
						<div class="form-group input-add-course">
							<div class="col-md-4">
								<label for="">Descripción</label>
							</div>
							<div class="col-md-8">
								<textarea class="form-control" rows="4" placeholder="Ingrese la descripción de la unidad" id="descripcion" name="descripcion" required></textarea>
							</div>
						</div>
						<div class="form-group input-add-course">
							<div class="col-md-4">
								<label for="">Cargar archivo</label>
							</div>
							<div class="col-md-8">
								<input type="file" id="archivo" name="archivo" required/>
								<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id;?>">
								<input type="hidden" name="numero" id="numero" value="<?php echo $nextNumUnity['nextU'];?>">
							</div>
						</div>
						<div class="form-group input-add-course">
							<div class="col-md-4">

							</div>
							<div class="col-md-8 check-unity">
								<label class="checkbox-inline">
						   	 <input type="checkbox" id="evaluar" name="evaluar" value="0"> Evaluar unidad
						    </label>							
								<label class="checkbox-inline">
						      <input type="checkbox" id="requerida" name="requerida" value="0"> Requerido
						    </label>							
							</div>
						</div>
						<hr>
					</div>
				</div>
			</div>
			<!-- Fin Formulario de creación de curso -->
			<div class="col-md-12 btn-add">
				<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
					<i class="fa fa-check" aria-hidden="true"></i>
				  <strong>Hecho!</strong> Unidad Creada con Éxito
				</div>
				<div>
				<input type="hidden" name="optButton" id="optButton">
				<button id="btn_save" type="submit" class="btn btn-default">GUARDAR</button>
				<button id="btn_save_back" type="submit" class="btn btn-default">GUARDAR y VOLVER</button>
			</div>
			</div>
		</form>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script>
	$( "#btn_save" ).click(function() {
	  $( "#optButton" ).val("save");
	});
	$( "#btn_save_back" ).click(function() {
	  $( "#optButton" ).val("save_back");
	});
</script>
</html>