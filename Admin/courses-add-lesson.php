<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$course_id = $_GET[ 'course_id' ];
$unity_id = $_GET[ 'unity_id' ];
$course = mysqli_fetch_array( $connect->query( "SELECT nombre, subtitulo,url_foto FROM cursos WHERE id=" . $course_id ) );
$unity = mysqli_fetch_array( $connect->query( "SELECT * FROM unidades where id=".$unity_id ) );
$nextNumLesson = mysqli_fetch_array( $connect->query( "SELECT COUNT(1)+1 nextL FROM lecciones WHERE id_unidad=" . $unity_id ));
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
				<h3 class="title-p">Agregar Lecciones: Paso #3</h3>
				<span class="text-p">Agrega la cantidad de lecciones que nesecita la unidad:</span>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3 pull-right date-courses">
				<div class="date-text">
					Fecha de creación: <?php echo date("d/m/Y"); ?>
				</div>
				<div class="date-icon">
					<span class="lnr lnr-calendar-full"></span>
				</div>
			</div>
		</div>
		<!-- Fin Titulo Página -->

		<!-- Inicio Formulario de creación de curso -->
		<form id="form-create-lesson" method="post">
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
						<span class="number-unity">Unidad #<?php echo $unity['numero']; ?></span>
						<span class="name-unity"><?php echo $unity['nombre']; ?></span>
						<p><?php echo $unity['descripcion']; ?></p>
					</div>
					<hr>
					<h3>lección #<?php echo $nextNumLesson['nextL']; ?></h3>
					<div class="form-group input-add-course col-md-12">
						<div class="col-md-4">
							<label for="">Nombre de la lección</label>
						</div>
						<div class="col-md-8">
							<input class="form-control" type="text" placeholder="Ingrese el nombre de la lección" id="nombre" name="nombre" required>
						</div>
					</div>
					<div class="form-group input-add-course col-md-12">
						<div class="col-md-4">
							<label for="">Subtitulo lección</label>
						</div>
						<div class="col-md-8">
							<input class="form-control" type="text" placeholder="Ingrese el subtitulo de la lección" id="subtitulo" name="subtitulo" required>
						</div>
					</div>
					<div class="form-group input-add-course col-md-12">
						<div class="col-md-4">
							<label for="">Descripción</label>
						</div>
						<div class="col-md-8">
							<textarea class="form-control" rows="3" placeholder="Ingrese la descripción de la lección" id="descripcion" name="descripcion" required></textarea>
						</div>
					</div>
					<div class="form-group input-add-course col-md-12">
						<div class="col-md-4">
							<label for="">CARGAR ARCHIVOS DE SLIDE (.zip)</label>
						</div>
						<div class="col-md-8">
							<input type="file" id="archivo" name="archivo" required/>
							<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id;?>">
							<input type="hidden" name="unity_id" id="unity_id" value="<?php echo $unity_id;?>">
							<input type="hidden" name="numeroL" id="numeroL" value="<?php echo $nextNumLesson['nextL'];?>">
							<input type="hidden" name="numeroU" id="numeroU" value="<?php echo $unity['numero'];?>">
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
			  <strong>Hecho!</strong> Lección Creada con Éxito
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