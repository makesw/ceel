<?php

session_start();

if ( !isset( $_SESSION[ 'dataSession' ] ) ) {

	header( 'Location: ../index.php' );

}

require '../conexion.php';

$course_id = $_GET[ 'course_id' ];

$unity_id = $_GET[ 'unity_id' ];

$unity = mysqli_fetch_array( $connect->query( "SELECT * FROM unidades where id=".$unity_id ) );

$course = mysqli_fetch_array( $connect->query( "SELECT nombre, subtitulo,url_foto FROM cursos WHERE id=" . $course_id ) );

$listLesson = $connect->query( 'SELECT * FROM lecciones WHERE id_unidad ='.$unity_id.' ORDER BY numero ASC' );

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

					<li class="active"><a href="courses-edit.php?idCourse=<?php echo $course_id; ?>">Curso</a>

					</li>

					<li class="active">Unidad

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

				<h3 class="title-p">Ver Unidad del Curso</h3>

				<span class="text-p">Aquí puedes ver las unidades del curso:</span>

			</div>

			<div class="col-xs-12 col-sm-4 col-md-3 pull-right date-courses">

				<div class="date-text">

					Fecha de creación:

					<?php $date = new DateTime($unity['fecha_creacion']);echo $date->format('d/m/Y'); ; ?>

				</div>

				<div class="date-icon">

					<span class="lnr lnr-calendar-full"></span>

				</div>

			</div>

		</div>

		<!-- Fin Titulo Página -->



		<!-- Inicio Formulario de creación de curso -->

		<form class="form-horizontal form-add-course" id="form-edit-unity" method="post">

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

						<h3>Unidad #<?php echo $unity['numero']; ?></h3>

						<div class="form-group input-add-course">

							<div class="col-md-4">

								<label for="">Nombre de la unidad</label>

							</div>

							<div class="col-md-8">

								<input class="form-control" disabled type="text" placeholder="Ingrese el nombre de la unidad" id="nombre" name="nombre" required value="<?php echo $unity['nombre'];?>">

							</div>

						</div>

						<div class="form-group input-add-course">

							<div class="col-md-4">

								<label for="">Subtitulo unidad</label>

							</div>

							<div class="col-md-8">

								<input class="form-control" disabled type="text" placeholder="Ingrese el subtitulo de la unidad" id="subtitulo" name="subtitulo" required value="<?php echo $unity['subtitulo'];?>">

							</div>

						</div>

						<div class="form-group input-add-course">

							<div class="col-md-4">

								<label for="">Descripción</label>

							</div>

							<div class="col-md-8">

								<textarea disabled class="form-control" rows="4" placeholder="Ingrese la descripción de la unidad" id="descripcion" name="descripcion" required><?php echo $unity['descripcion'];?></textarea>

							</div>

						</div>

						<!--<div class="form-group input-add-course">

							<div class="col-md-4">

								<label for="">Adjuntar archivo</label>

							</div>

							<div class="col-md-8">

								<input disabled type="file" id="archivo" name="archivo"/>

								<input type="hidden" name="unity_id" id="unity_id" value="<?php echo $unity_id;?>">

								<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id;?>">

								<input type="hidden" name="numero" id="numero" value="<?php echo $unity['numero'];?>">

							</div>

						</div>-->

						<div class="form-group input-add-course">

							<div class="col-md-4">



							</div>

							<div class="col-md-8 check-unity">

								<label class="checkbox-inline">

						   	 <input type="checkbox" disabled id="evaluar" name="evaluar" <?php echo $unity['requiere_evaluar']?'checked':'';?>>Evaluar unidad

						    </label>							

								<label class="checkbox-inline">

						      <input type="checkbox" disabled id="requerida" name="requerida" <?php echo $unity['requerida']?'checked':'';?>> Requerido

						    </label>							

							</div>

						</div>

						<hr>

					</div>

				</div>

			</div>

			<!-- Fin Formulario de creación de curso -->

			<!--<div class="col-md-12 btn-add">				

				<div>

					<button type="submit" class="btn btn-default">ACTUALIZAR</button>

				</div>

				<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">

				<i class="fa fa-check" aria-hidden="true"></i>

			  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Acción Realizada con Éxito</i>

			</div>
-->
			<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">

				<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>

				<i id="div-msg-fail-desc">No se Pudo Realizar la Acción</i>

			</div>

			</div>

		</form>		

		<div class="row edit-courses">				

				<div class="col-md-11 title-edit">									

					<h4>LECCIONES DE LA UNIDAD</h4>

					<!--div>

					<a href="courses-add-lesson.php?course_id=<?php echo $course_id; ?>&unity_id=<?php echo $unity_id; ?>"><button class="btn btn-default">AGREGAR LECCIÓN</button></a>

				</div -->

				<form id="form-remove-lesson" method="post" name="form-remove-lesson">

					<table class="table table-responsive">

						<tr>

							<td hidden="true">Id</td>

							<td>Número</td>

							<td>Nombre</td>

							<td>Subtítulo</td>

							<td>Número de Preguntas</td>

							<td class="last-info-edit">Acciones</td>

						</tr>

						<?php  $iter = 1;

                          while($row = mysqli_fetch_array($listLesson))  

                          {   							  

                              $countQuestions = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) numQ  FROM preguntas WHERE id_leccion=".$row["id"] )); 

							  

							  echo '  

                               <tr> 

							   		<td hidden="true">'.$row["id"].'</td>

                                    <td class="icon-edit">'.$iter.'</td>  

                                    <td>'.$row["nombre"].'</td>  

                                    <td>'.$row["subtitulo"].'</td>

									<td>'.$countQuestions["numQ"].'</td>

									<td class="last-info-edit">									

									<a href="javascript:fn_edit_lesson('.$course_id.','.$unity_id.','.$row["id"].');">Detalle</a>

									</td>

                               </tr>  

                               '; 

						   		$iter++;

                          }  

                          ?>

					</table>

					<input type="hidden" name="unity_id" id="unity_id" value="<?php echo $unity_id;?>">

					<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id;?>">

					<input type="hidden" name="numero" id="numero" value="<?php echo $unity['numero'];?>">

					</form>

				</div>

			</div>	

	</div>

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>



</html>