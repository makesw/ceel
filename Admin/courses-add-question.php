<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$course_id = $_GET[ 'course_id' ];
$unity_id = $_GET[ 'unity_id' ];
$lesson_id = $_GET[ 'lesson_id' ];
$course = mysqli_fetch_array( $connect->query( "SELECT nombre, subtitulo,url_foto FROM cursos WHERE id=" . $course_id ) );
$lesson = mysqli_fetch_array( $connect->query( "SELECT DISTINCT l.*, s.url_slide FROM lecciones l JOIN slides s ON l.id = s.id_leccion AND l.id=" . $lesson_id ) );
$nextNumQuestion = mysqli_fetch_array( $connect->query( "SELECT COUNT(1)+1 nextQ FROM preguntas WHERE id_leccion=" . $lesson_id ) );
$listQTypes = $connect->query( "SELECT * FROM tipos_pregunta" );
date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html>
<?php include("../includes/head.php"); ?>
<body class="bg-2">

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid">
		<!-- Inicio barra de navegación #2-->
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active"><a href="courses-list.php">Cursos</a>
					</li>
					<li class="active"><a href="courses-edit.php?idCourse=<?php echo $course_id;?>">Curso</a>
					</li>
					<li class="active"><a href="courses-edit-unity.php?course_id=<?php echo $course_id;?>&unity_id=<?php echo $unity_id;?>">Unidad</a>
					</li>
					<li class="active"><a href="courses-edit-lesson.php?course_id=<?php echo $course_id;?>&unity_id=<?php echo $unity_id;?>&lesson_id=<?php echo $lesson_id;?>">Lección</a>
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
				<h3 class="title-p">Crear Curso: Paso #4</h3>
				<span class="text-p">Gestiona las preguntas para la lessión:</span>
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
		<form id="form-create-question" method="post">
		<div class="row">
			<div class="col-md-12">
				<div class="col-xs-10 col-sm-3 col-md-3 img-p-course">
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
				<div class="col-sm-8 col-md-8 add-unity">
					<div class="desc-unity">
						<span class="number-unity">Evaluación</span>
						<span class="name-unity">Preguntas por Lección</span>
					</div>
					<hr>
					<h3>lección #<?php echo $lesson['numero']; ?> <span>// pregunta #<?php echo $nextNumQuestion['nextQ']; ?></span></h3>
					<div class="form-group input-add-course col-md-12">
						<label for="">Enunciado (Requerido *)</label>
						<textarea class="form-control" id="enunciado" name="enunciado" rows="3" placeholder="Ingrese el enunciado de la pregunta" required></textarea>
					</div>
					<div class="form-group input-add-course col-md-12">
							<label for="">Pregunta</label>
							<input class="form-control" id="pregunta" name="pregunta" required type="text" placeholder="Ingrese la pregunta" >
					</div>
					<div class="form-group input-add-course col-md-12">
						<label for="">Tipo de Pregunta</label>
						<select class="form-control" required id="tipoPregunta" name="tipoPregunta">
							<option value="">Seleccione el Tipo de Pregunta</option>
							<?php
							while ( $row = mysqli_fetch_array( $listQTypes ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'descripcion' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group input-add-course col-md-12">
						<label for="">Ingrese las opciones de respuesta y seleccione solo la(s) correcta(s):</label>
						<input type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $lesson_id;?>">
						<input type="hidden" name="numero" id="numero" value="<?php echo $nextNumQuestion['nextQ'];?>">
					</div>
					<div id="divResponses" class="form-group input-add-course col-md-12">
						<label for="">....</label>
					</div>					
				</div>
			</div>
		</div>
		<!-- Fin Formulario de creación de curso -->
		<div class="col-md-12 btn-add">			
			<div>
				<input type="hidden" name="optButton" id="optButton">
				<button id="btn_save" type="submit" class="btn btn-default">GUARDAR</button>
				<button id="btn_save_back" type="submit" class="btn btn-default">GUARDAR y VOLVER</button>
			</div><br/>
			<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">
				<i class="fa fa-check" aria-hidden="true"></i>
			  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Pregunta Creada con Éxito</i>
			</div>
			<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
				<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
				<i id="div-msg-fail-desc">No se Pudo Realizar la Acción</i>
			</div>
		</div>
		</form>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<?php $connect->close(); ?>
<script>
	$( "#btn_save" ).click(function() {
	  $( "#optButton" ).val("save");
	});
	$( "#btn_save_back" ).click(function() {
	  $( "#optButton" ).val("save_back");
	});
</script>
</html>