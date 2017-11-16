<?php session_start();

if ( !isset($_SESSION[ 'dataSession' ]) || $_SESSION[ 'dataSession' ]['perfil'] != 'Usuario' ) {

	header( 'Location: ../index.php' );

}

require '../conexion.php'; ?>

<?php

$endCourses = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total from aprobacion_cursos where id_usuario =" . $_SESSION[ 'dataSession' ][ 'id' ] ) );

$inProgressCourses = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total from inscripciones i JOIN cursos c ON   i.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " AND i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL ) AND c.id NOT IN (select a.id_curso from aprobacion_cursos a WHERE a.id_usuario=" . $_SESSION[ 'dataSession' ][ 'id' ] . ")" ) );

$pendingCourses = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total from invitaciones i JOIN cursos c ON i.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " AND i.id_curso = c.id AND c.id NOT IN (select id_curso from inscripciones i WHERE i.id_usuario=" . $_SESSION[ 'dataSession' ][ 'id' ] . " )" ) );

$userCourses = $connect->query( "SELECT c.* FROM inscripciones i JOIN cursos c ON i.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " AND i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL) AND c.id NOT IN( SELECT id_curso from aprobacion_cursos a WHERE a.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " )" );



$userPendingCourses = $connect->query( "SELECT DISTINCT c.* FROM invitaciones i JOIN cursos c ON i.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " AND i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL) AND c.id NOT IN( SELECT id_curso from aprobacion_cursos a WHERE a.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " )" );



date_default_timezone_set('America/Bogota'); 

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

					<li class="active"><a href="index.php">Dashboard</a>

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

			<div class="col-xs-3 col-sm-2 col-md-1 icon-pt">

				<i class="fa fa-graduation-cap" aria-hidden="true"></i>

			</div>

			<div class="col-xs-9 col-sm-8 col-md-8">

				<h3 class="title-p">Continua viendo...</h3>

				<span class="text-p">Continua tu aprendizaje y descarga tu cetificado</span>

			</div>

			<!-- Inicio Contadores user-->

			<div class="col-xs-12 pull-right search-courses">

				<div class="count-user well col-xs-12 col-sm-3 col-sm-offset-2">Cursos terminados

					<span>

						<?php echo $endCourses['total']; ?>

					</span>

				</div>

				<div class="count-user col-xs-12 col-sm-3">Cursos activos

					<span>

						<?php echo $inProgressCourses['total']; ?>

					</span>

				</div>

				<div class="count-user pending col-xs-12 col-sm-3">Cursos pendientes

					<span>

						<?php echo $pendingCourses['total']; ?>

					</span>

				</div>

			</div>

			<!-- Fin Contadores user-->

		</div>

		<!-- Fin Titulo Página -->			

		<div class="row">

		<!-- Inicio Lista de cursos pednientes-->	

			<?php

			$iter = 1;

			while ( $row = mysqli_fetch_array( $userPendingCourses ) ) {

				$date_p = new DateTime( $row[ "fecha_creacion" ] );				

				?>

			<div class="col-md-12 list-courses" <?php if ($iter%2==0){echo "style='background: #ffffff!important;'"; } ?>>

				<div class="col-sm-2 col-md-2 img-course">

					<div class="">

						<img src="<?php echo $row['url_foto'];?>" alt="img-responsive">

					</div>

				</div>

				<div class="col-sm-7 col-md-7">

					<div class="text-course">

						<span>

							<?php echo $row['nombre']; ?>

						</span>

						<p class="date-course"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Fecha de publicación:<?php echo $date_p->format('d / m / Y'); ?>

						</p>

						<p>

							<?php echo $row['descripcion']; ?>

						</p>

					</div>

				</div>

				<div class="col-sm-2 col-md-2 progress-user">

					<a href="<?php echo '../confirmInscription.php?course='.$row['id'].'&user='.$_SESSION[ 'dataSession' ][ 'id' ]; ?>">

					  <button class="btn btn-list">

						  INSCRIBIRME

						</button>

					</a>							

				</div>

			</div>

			<?php 

				$iter++;

				} 

			?>

			<!-- Fin Lista de cursos pendientes-->	

			<!-- Inicio Lista de cursos inscritos-->	

			<?php

			while ( $row = mysqli_fetch_array( $userCourses ) ) {

				$date_p = new DateTime( $row[ "fecha_creacion" ] );

				$content = true;

				/**Calcular avance de curso:**/

				$avanceCurso = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM avances a JOIN slides s ON a.id_slide = s.id AND a.id_usuario = " . $_SESSION[ 'dataSession' ][ 'id' ] . " JOIN lecciones l ON s.id_leccion = l.id

				JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id =" . $row[ 'id' ] ) );

				/**Calcular slides de curso:**/

				$slides = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM cursos c JOIN unidades u ON c.id = u.id_curso AND c.id = ".$row['id']." JOIN lecciones l ON u.id = l.id_unidad JOIN slides s ON l.id = s.id_leccion" ) );

				/**Calcular porcentaje:**/

				$porcentage = 0;

				if ( $slides[ 'total' ] != 0 ) {

					$porcentage = round( ( $avanceCurso[ 'total' ] / $slides[ 'total' ] ) * 100 );

				}else{

					$content = false;

				}

				?>

			<div class="col-md-12 list-courses" <?php if ($iter%2==0){echo "style='background: #ffffff!important;'"; } ?>>

				<div class="col-sm-2 col-md-2 img-course">

					<div class="">

						<img src="<?php echo $row['url_foto'];?>" alt="img-responsive">

					</div>

				</div>

				<div class="col-sm-7 col-md-7">

					<div class="text-course">

						<span>

							<?php echo $row['nombre']; ?>

						</span>

						<p class="date-course"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Fecha de publicación:<?php echo $date_p->format('d / m / Y'); ?>

						</p>

						<p>

							<?php echo $row['descripcion']; ?>

						</p>

					</div>

				</div>

				<div class="col-sm-2 col-md-2 progress-user">

					<p class="porcentage-user">

						<?php echo $porcentage; ?>%

					</p>

					<?php if($content){?>

					<a href="course-view.php?idCourse=<?php echo $row['id']; ?>">

						<button class="btn btn-success <?php if($porcentage == 100){echo 'btn-list-complete';}else{echo 'btn-list';}?>">

						  Ir al Curso 

						</button>

					</a>

					<?php }else{ ?>	

					<span>

						<button class="btn <?php if(!$content){echo 'btn-list-disable'; } ?>" disabled>

						  Sin contenido

						</button>

					</span>

					<?php } ?>			

				</div>

			</div>

			<?php 

				$iter++;

				} 

			?>

			<!-- Fin Lista de cursos inscritos-->	

			<?php  if($iter == 1){ ?>

			<div class="row p-texts">

				<div style="text-align: center;">

						<span class="text-p">“No tienes cursos asignados, si tienes alguna duda contacta al administrador <a href="contact.php">aquí.</a>”</span>

				</div>

			</div>

			<?php } ?>

		</div>		

	</div>

	<!-- Fin Lista de cursos -->	

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>

</html>

<?php $connect->close(); ?>