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

$unity = mysqli_fetch_array( $connect->query( "SELECT * FROM unidades where id=".$unity_id ) );

$lesson = mysqli_fetch_array( $connect->query( "SELECT l.* FROM lecciones l WHERE l.id=".$lesson_id ));

$listQuestion = $connect->query( 'SELECT p.*, tp.descripcion FROM preguntas p JOIN tipos_pregunta tp ON p.id_tipo_pregunta = tp.id AND p.id_leccion ='.$lesson_id.' ORDER BY numero ASC' );	

$listSlides = $connect->query( 'SELECT * FROM slides WHERE id_leccion ='.$lesson_id.' ORDER BY numero ASC' );	

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

					<li class="active">Lección

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

				<h3 class="title-p">Detalle de Lección:</h3>

			</div>

			<div class="col-xs-12 col-sm-4 col-md-3 pull-right date-courses">

				<div class="date-text">

					Fecha de creación: <?php $date = new DateTime($lesson['fecha_creacion']);echo $date->format('d/m/Y'); ; ?>

				</div>

				<div class="date-icon">

					<span class="lnr lnr-calendar-full"></span>

				</div>

			</div>

		</div>

		<!-- Fin Titulo Página -->



		<!-- Inicio Formulario de creación de curso -->

		<form id="form-edit-lesson" method="post" name="form-edit-lesson">

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

					<h3>lección #<?php echo $lesson['numero']; ?></h3>

					<div class="form-group input-add-course col-md-12">

						<div class="col-md-4">

							<label for="">Nombre de la lección</label>

						</div>

						<div class="col-md-8">

							<input class="form-control" disabled type="text" placeholder="Ingrese el nombre de la lección" id="nombre" name="nombre" value="<?php echo $lesson['nombre'];?>" >

						</div>

					</div>

					<div class="form-group input-add-course col-md-12">

						<div class="col-md-4">

							<label for="">Subtitulo lección</label>

						</div>

						<div class="col-md-8">

							<input class="form-control" disabled type="text" placeholder="Ingrese el subtitulo de la lección" id="subtitulo" name="subtitulo" value="<?php echo $lesson['subtitulo'];?>" >

						</div>

					</div>

					<div class="form-group input-add-course col-md-12">

						<div class="col-md-4">

							<label for="">Descripción</label>

						</div>

						<div class="col-md-8">

							<textarea  disabled class="form-control" rows="3" placeholder="Ingrese la descripción de la lección" id="descripcion" name="descripcion" ><?php echo $lesson['descripcion'];?></textarea>

						</div>

					</div>

					<!--<div class="form-group input-add-course col-md-12">

						<div class="col-md-4">

							<label for="">CARGAR ARCHIVOS DE SLIDE (.zip)</label>

						</div>

						<div class="col-md-8">

							<input disabled type="file" id="archivo" name="archivo" required/>

							<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id;?>">

							<input type="hidden" name="unity_id" id="unity_id" value="<?php echo $unity_id;?>">

							<input type="hidden" name="lesson_id" id="lesson_id" value="<?php echo $lesson_id;?>">

							<input type="hidden" name="numero" id="numero" value="<?php echo $lesson['numero'];?>">

						</div>

					</div>		-->								

					<hr>					

				</div>

			</div>			

		</div>

		<!-- Fin Formulario de creación de curso -->

		<div class="col-md-12 btn-add">				

			<!--div>

				<button type="submit" class="btn btn-default">ACTUALIZAR</button>

			</div -->

			<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">

				<i class="fa fa-check" aria-hidden="true"></i>

			  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Leción Actualizada con Éxito</i>

			</div>

			<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">

				<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>

				<i id="div-msg-fail-desc">No se Pudo Realizar la Acción</i>

			</div>

		</div>

		</form>

		<div class="row edit-courses">				

				<div class="col-md-11 title-edit">									

					<h4>PREGUNTAS DE LA LECCIÓN</h4>

					<!--div>

					<a href="courses-add-question.php?course_id=<?php echo $course_id; ?>&unity_id=<?php echo $unity_id; ?>&lesson_id=<?php echo $lesson_id; ?>"><button class="btn btn-default">AGREGAR PREGUNTA</button></a>

				</div -->

				<form id="form-remove-question" method="post" name="form-remove-question">

					<table class="table table-responsive" id="tbl_questions">

					<thead>

						<tr>

							<td hidden="true">Id</td>

							<td>Número</td>

							<td>Tipo de Pregunta</td>

							<td>Enunciado</td>

							<td class="last-info-edit">Acciones</td>

						</tr>

					</thead>

					<tbody>

						<?php  $iter = 1;

                          while($row = mysqli_fetch_array($listQuestion))  

                          {  							  

							  echo '  

                               <tr> 

							   		<td hidden="true">'.$row["id"].'</td>

                                    <td class="icon-edit">'.$iter.'</td>  

                                    <td>'.$row["descripcion"].'</td>  

                                    <td>'.$row["enunciado"].'</td>

									<td class="last-info-edit">										

									<a href="javascript:fn_edit_question('.$row["id"].','.$lesson_id.','.$course_id.','.$unity_id.');">Detalle</a>

									</td>

                               </tr>  

                               ';

							  $iter++;

                          } 

                          ?>

                       </tbody>

					</table>

					</form>

				</div>

			</div>

			<div class="row edit-courses">				

				<div class="col-md-11 title-edit">									

					<h4>SLIDES DE LA LECCIÓN</h4>

					<table id="tbl_slides" class="table table-responsive">

						<thead>

						<tr>

							<td>NÚMERO</td>

							<td>URL</td>

						</tr>

						</thead>

						<tbody>

						<?php  

                          while($row = mysqli_fetch_array($listSlides))  

                          {  							  

                               echo '  

                               <tr> 							   		  

                                    <td>'.$row["numero"].'</td>  

                                    <td>'.$row["url_slide"].'</td>									

                               </tr>  

                               ';  

                          }  

                          ?>

                          </tbody>

					</table>

				</div>

			</div>	

	</div>

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>

<script>

	$( document ).ready( function () {

		var table = $( '#tbl_slides' ).DataTable( {

			lengthChange: false,

			searching: false

		} );



		table.buttons().container()

			.appendTo( '#tbl_slides_wrapper .col-sm-6:eq(0)' );

		

	} );

</script>

<?php $connect->close(); ?>

</html>