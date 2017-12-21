<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$listInstructores = $connect->query( 'SELECT id, nombres, apellidos FROM usuarios WHERE instructor = 1 AND estado = 1 ORDER BY nombres ASC' );
$idCourse = $_GET[ 'idCourse' ];	
$result = $connect->query( "SELECT * FROM cursos where id=".$idCourse );
$data = mysqli_fetch_array( $result );

$date_c = new DateTime($data["fecha_creacion"]);
$date_b = new DateTime($data["fecha_iniciacion"]);
$date_e = NULL;
if(!empty($data["fecha_finalizacion"])){
	$date_e = new DateTime($data["fecha_finalizacion"]);
	$date_e = $date_e->format('Y-m-d');
}

$listUnity = $connect->query( 'SELECT * FROM unidades WHERE id_curso ='.$idCourse.' ORDER BY numero ASC' );
$totalInscritos = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM inscripciones where id_curso=".$idCourse ) );
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
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active"><a href="courses-list.php">Cursos</a>
					</li>
					<li class="active">Editar Curso
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
				<h3 class="title-p">Editar Curso:</h3>
				<span class="text-p">Gestiona los campos para editar el curso:</span>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 pull-right date-courses">
				<div class="date-text">
					Fecha de creación: <?php echo $date_c->format('d/m/Y'); ?>
				</div>
				<div class="date-icon">
					<span class="lnr lnr-calendar-full"></span>
				</div>
			</div>
		</div>
		<!-- Fin Titulo Página -->

		<!-- Inicio Formulario de creación de curso -->
		<div class="row">
			<form id="form-excel" method="post" action="/toExcel.php?option=excelUsersCourse&id_course=<?php echo $idCourse; ?>"></form>	
			<form class="form-horizontal form-add-course" id="form-edit-course" method="post" enctype="multipart/form-data">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-4 control-label">Nombre del curso</label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo $data['nombre'];?>" id="nombre" name="nombre" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Subtitulo</label>
						<div class="col-sm-8">
							<input type="text" id="subtitulo" name="subtitulo" class="form-control" value="<?php echo $data['subtitulo'];?>" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Descripción</label>
						<div class="col-sm-8">
							<textarea class="form-control"id="descripcion" name="descripcion" rows="3" required><?php echo $data['descripcion'];?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha Inicio</label>
						<div class="col-sm-8">
							<input type="date"  id="finicio" name="finicio" class="form-control" value="<?php echo $date_b->format('Y-m-d'); ?>" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha Fin</label>
						<div class="col-sm-8">
							<input type="date" id="ffin" value="<?php echo $date_e ?>" name="ffin" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Asignar Instructor</label>
						<div class="col-sm-8">
							<select class="form-control" required id="instructor" name="instructor">
							<?php
							while ( $row = mysqli_fetch_array( $listInstructores ) ) {
								$selected = ( $row[ 'id' ] == $data[ 'id_instructor' ] ? 'selected="true"' : '' );
								echo "<option " . $selected . " value='" . $row[ 'id' ] . "'>" . $row[ 'nombres' ] .' '. $row[ 'apellidos' ] . "</option>";
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
							<input type="file" id="archivo" name="archivo"/>
						</div>
					</div>	
				</div>
				<div class="col-md-4">
					<img id="blah" width="418px" height="219px" src="<?php if(isset($data['url_foto'])){echo $data['url_foto'];}else{echo '../assets/img/no-image-course.png';}?>" alt="your image"/>
					<input type='file' id="imgCurso" name="imgCurso" onchange="readURL(this);"/>
				</div>
				<div class="col-md-4">
                    <br>
					<label class="control-label">USUARIOS INCRITOS: <?php echo $totalInscritos['total']; ?></label>
                    <button type="button" onClick="javascript:excelSubmit();"><i class="fa fa-download" aria-hidden="true"></i>DESCARGAR USUARIOS DE ESTE CURSO (.xls) </button>
				</div>
				<div class="col-md-12 btn-add">
				<input type="hidden" id="course_id" name="course_id" value="<?php echo $data[ 'id' ]; ?>"/>
				<div>
					<button type="submit" class="btn btn-default">ACTUALIZAR</button>
				</div>
				<br/>
				<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">
				<i class="fa fa-check" aria-hidden="true"></i>
			  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Acción Realizada con Éxito</i>
			</div>
			<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
				<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
				<i id="div-msg-fail-desc">No se Pudo Actualizar el Curso</i>
			</div>
				</div>
				</form>				
				<div class="row edit-courses">				
				<div class="col-md-11 title-edit">									
					<h4>UNIDADES DEL CURSO</h4>
					<div>
					<a href="courses-add-unity.php?course_id=<?php echo $data[ 'id' ]; ?>"><button class="btn btn-default">AGREGAR UNIDAD</button></a>
				</div><br/>
				<form id="form-remove-unity" method="post" name="form-remove-lesson">
					<table id="tbl_unitys" class="table table-responsive">
						<tr>
							<td hidden="true">Id</td>
							<td>Número</td>
							<td>Nombre</td>
							<td>Subtítulo</td>
							<td>Evaluar</td>
							<td>Requerida</td>
							<td class="last-info-edit"><div class="form-inline all-actions">
									  <select class="form-control" id="cbxActions">
										<option selected="selected">Acciones</option>
										<option value="1">Eliminar</option>
									  </select>								
									</div></td>
						</tr>
						<?php  $iter = 1;
                          while($row = mysqli_fetch_array($listUnity))  
                          {  							  
                               echo '  
                               <tr> 
							   		<td hidden="true">'.$row["id"].'</td>
                                    <td class="icon-edit">'.$iter.'</td>  
                                    <td>'.$row["nombre"].'</td>  
                                    <td>'.$row["subtitulo"].'</td>  
                                    <td>'.$row["requiere_evaluar"].'</td>
									<td>'.$row["requerida"].'</td>
									<td class="last-info-edit">
									<a href="javascript:fn_edit_unity('.$idCourse.','.$row["id"].');">Detalle | <input type="checkbox" value="'.$row["id"].'">
									</td>
                               </tr>  
                               ';  
							  $iter++;
                          }  
                          ?>
					</table>
					<input type="hidden" id="course_id" name="course_id" value="<?php echo $data[ 'id' ]; ?>"/>
					</form>
				</div>
			</div>		
		</div>
		<!-- Fin Formulario de creación de curso -->	

	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script type="text/javascript">
var arrIds=[];
$('#tbl_unitys tbody').on('change', 'input[type="checkbox"]', function(){
	if($(this).is(':checked')){
		arrIds.push(this.value);
	}else{
		arrIds.splice(arrIds.indexOf(this.value), 1);
	}
});	
$('#cbxActions').on('change', function(){
  if(this.value == 1){//Eliminar
	fn_del_unitys();  
  }
});	
function fn_del_unitys(){
	if(arrIds.length<1){
		alert('Seleccione registros!');
	}else if(confirm('Confirma Eliminar?')){	
		$.ajax({
			type: "POST",
			url: 'actions.php?action=deleteUnitys',
			data:{ array : JSON.stringify(arrIds) },
			dataType: "json",
			success: function (data) {
				if(!data.deleteAll){
					$('#div-msg-fail-desc').text( "No todas las unidades se eliminaron." );
					$('#div-msg-fail').show();
					setTimeout(function(){
						location.reload();
					},2000);
				}else{
					console.log(data);
					$('#div-msg-ok').show();
					setTimeout(function(){
						location.reload();
					},2000);
				}
			},
			error: function (data) {
				console.log(data);
			},
		});		
	}
}
function readURL( input ) {
	if ( input.files && input.files[ 0 ] ) {
		var reader = new FileReader();
		reader.onload = function ( e ) {
			$( '#blah' ).attr( 'src', e.target.result );
		}
		reader.readAsDataURL( input.files[ 0 ] );
	}
}
function excelSubmit(){
  $( "#form-excel" ).submit();
}
</script>
<?php $connect->close(); ?>
</html>