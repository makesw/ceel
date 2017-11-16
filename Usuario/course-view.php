<?php

session_start();

if ( !isset( $_SESSION[ 'dataSession' ] ) ) {

	header( 'Location: ../index.php' );

}



require '../conexion.php';

$idCourse = $_GET[ 'idCourse' ];

	

/**Calcular avance de curso:**/

$avanceCurso = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM avances a JOIN slides s ON a.id_slide = s.id AND a.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " JOIN lecciones l ON s.id_leccion = l.id

JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id =" . $idCourse ) );

/**Calcular slides de curso:**/

$countSlidesL = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM lecciones l JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id = " . $idCourse . " JOIN slides s ON s.id_leccion = l.id" ) );

	

/**Contar slides de unidades:**/

$countSlidesU = mysqli_fetch_array($connect->query("SELECT count(1) total FROM unidades WHERE unidades.id_curso = ".$idCourse."  AND unidades.url_archivo IS NOT NULL"));

	

//Total:

$totalSlides = $countSlidesL['total']+$countSlidesU['total'];

//Total real 	

$totalSlidesR = $countSlidesL['total'];	

	

	

/**Calcular porcentaje avance:**/

$porcentage = 0;

if ( $totalSlidesR != 0 ) {

	$porcentage = round( ( $avanceCurso[ 'total' ] / $totalSlidesR ) * 100 );

}

$course = mysqli_fetch_array( $connect->query( "SELECT c.*, u.nombres, u.apellidos from cursos c JOIN usuarios u ON u.id = c.id_instructor AND c.id =".$idCourse ) );	

$date_c = new DateTime( $course[ "fecha_creacion" ] );

	

$unidades = $connect->query( "SELECT id, nombre FROM unidades WHERE id_curso= " . $idCourse );

	

?>

<!DOCTYPE html>

<html>

<?php include( "../includes/head.php" ); ?>

<body class="bg-2">



	<?php include("../includes/header2.php"); ?>



	<div class="container-fluid">

		<!-- Inicio barra de navegación-->

		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">

				<ol class="breadcrumb">

					<li class="active"><a href="index.php">Dashboard</a>

					</li>

					<li class="active">Ver Curso

					</li>

				</ol>

			</div>



			<div class="col-xs-6 col-sm-6 col-md-4 logout">

				<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>

			</div>

		</div>

		<!-- Fin barra de navegación-->

		

		<!-- Inicio textos curso -->

		<div class="row">

			<div class="col-md-12 info-course">

				<div class="col-xs-12 col-sm-9 col-md-9">

					<div class="icon-course">

						<i class="fa fa-recycle" aria-hidden="true"></i>

					</div>

					<div class="t-course">

						<h3><?php echo $course['nombre']; ?></h3>

						<p>

							<strong>Instructor: </strong><span><?php echo $course['nombres'].' '.$course['apellidos']; ?></span><br>

							<strong> Fecha de publicación: </strong> <?php echo $date_c->format('d / m / Y'); ?>

						</p>

					</div>

				</div>

				<div class="col-xs-12 col-sm-3 col-md-3">

					<div class="progress-course">

						<div class="t-progress"><span><?php echo $porcentage; ?>%</span>

						PROGRESO</div>

					</div>

				</div>

			</div>

		</div>

		<!-- Fin textos curso -->





		<!-- Inicio Descripción del curso -->

		<div class="row desc-course">

			<div class="col-xs-12 col-sm-9 col-md-9">

				<h4>Descripción del curso</h4>

				<p><?php echo $course['descripcion']; ?></p>

			</div>

			<div class="col-xs-12 col-sm-3 col-md-3 goto-course">

				<a href="course-nav.php?id_course=<?php echo $course['id'];?>">

					<button class="btn">CONTINUAR CURSO</button>

				</a>

			</div>

		</div>

		<!-- Fin Descripción del curso -->



		<!-- Inicio Nota Importante -->

		<div class="row texts important-note">

			<div class="col-sm-1 col-md-1"></div>

			<div class="col-md-10">

				<div class="note">

					<h3><i class="fa fa-info-circle" aria-hidden="true"></i> Nota:</h3>

					<p>Si quieres descargar tu certificado completa el curso al 100% e ingresa a tus logros y da click en <strong>descargar certificado</string>.</p>

					<div class="icon-note">

						<img src="../assets/img/icon-imp.png" alt="">

					</div>

				</div>

			</div>

			<div class="col-sm-1 col-md-1"></div>

		</div>

		<!-- Fin Nota Importante -->

		<!-- Inicio Unidades Curso -->

		<div class="row unity-courses">

			<div class="col-xs-12 .col-sm-12 .col-md-12">

				<h2>Contenido del Curso</h2>

				<p>Las lecciones por unidad que has completado se marcan en verde.</p>			

			</div>			

		</div>

		<!-- Fin Unidades Curso -->

		<div class="row">

			<div class="col-md-2"></div>

			<div class="col-md-8 lessons-courses">

				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

				<?php  

				  while($row = mysqli_fetch_array($unidades))  

				  { ?> 

					<div class="panel panel-default">

						<div class="panel-heading" role="tab" id="headingOne<?php echo $row['id']; ?>">

						  <h4 class="panel-title">

							<a role="button" data-toggle="collapse" data-parent="#accordion"  href="#collapseOne<?php echo $row['id']; ?>" aria-controls="collapseOne<?php echo $row['id']; ?>">

							  <?php echo $row['nombre']; ?>

							</a>

						  </h4>

						</div>

						<div id="collapseOne<?php echo $row['id']; ?>" class="panel-collapse .collapse.in" role="tabpanel" aria-labelledby="headingOne<?php echo $row['id']; ?>">

						  <div class="panel-body" aria-expanded="false">

						  	<ul>

							<?php  

				   			  $leccionesUnidad = $connect->query( "SELECT id, nombre, numero FROM lecciones WHERE id_unidad= ".$row['id'] );	

							  while($row2 = mysqli_fetch_array($leccionesUnidad))  

							  { 

								//contar slides de la leccion:

								$totalSlides = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total FROM slides s JOIN lecciones l ON s.id_leccion= l.id AND l.id =".$row2['id'] ));	  

								//contar slides vistas de leccion:

								$viewSlides = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total FROM avances a JOIN slides s ON a.id_slide = s.id AND id_usuario = ".$_SESSION[ 'dataSession' ][ 'id' ]." JOIN lecciones l ON s.id_leccion= l.id AND l.id =".$row2['id'] ));

								

								$lessonViewOk = false;

								if( $totalSlides['total']==$viewSlides['total'] ){

									$lessonViewOk = true;

								}								  

								?>

								<?php if($lessonViewOk){ ?>

									

									<li class="complete"><span><a class="complete" href="course-nav.php?id_course=<?php echo $course['id'];?>&linkLesson=<?php echo $row2['id'];?>"><?php echo $row2['numero'];?> <?php echo $row2['nombre']; ?><i class="fa fa-check-circle" aria-hidden="true"></i></a></span>

									</li>									

								<?php }else{ ?>								   

									<li><span><?php echo $row2['numero']; ?></span> <?php echo $row2['nombre']; ?><i class="fa fa-check-circle" aria-hidden="true"></i>

									</li>

								<?php } }?>

							 </ul>

						  </div>

						</div>

				  </div>

				<?php 

				  }  

                 ?>

				</div>

			</div>

			<div class="col-md-2"></div>			

		</div>



	</div>

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>

</html>

<?php $connect->close(); ?>