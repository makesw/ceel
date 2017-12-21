<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$totalCursos = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total from cursos" ));
$totalCursosActivos = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total from cursos WHERE (fecha_finalizacion > NOW() OR fecha_finalizacion IS NULL)"  ));	
$totalCursosInactivos = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total from cursos WHERE cursos.fecha_finalizacion < now()" ));
$result = NULL;
if( isset($_GET[ 'filter' ]) && $_GET[ 'filter' ]=='inactivos' ){
	$result = $connect->query( "SELECT * from cursos WHERE fecha_finalizacion < NOW() ORDER BY nombre ASC" );
}else{
	$result = $connect->query( "SELECT * from cursos WHERE (fecha_finalizacion > NOW() OR fecha_finalizacion IS NULL) ORDER BY nombre ASC" );
}

?>
<!DOCTYPE html>
<html>
<?php include("../includes/head.php"); ?>
<body>

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid" id="contenedor">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active">Cursos
					</li>
				</ol>
			</div>

			<div class="col-xs-6 col-sm-6 col-md-4 logout">
				<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>
			</div>

		</div>
		<!-- Fin barra de navegación-->
		<div class="container">
		<!-- Inicio Titulo Página -->
		<div class="row p-texts">
			<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">
				<i class="fa fa-book" aria-hidden="true"></i>
			</div>
			<div class="col-xs-10 col-sm-8 col-md-8">
				<h3 class="title-p">LISTADO DE CURSOS</h3>
				<span class="text-p">Estos son todos los cursos creados:</span>
			</div>
		</div>
		<!-- Fin Titulo Página -->
		<!-- Inicio Barra Usuarios -->
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-4 ">
				<div class="box-users">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> CURSOS EN PLATAFORMA
					<span class="number-box-users"><?php echo $totalCursos['total']; ?></span>
				</div>
			</div>
			<a href="courses-list.php">
			<div class="col-xs-12 col-sm-4 col-md-4 ">
				<div class="box-users box-bg-2">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> CURSOS ACTIVOS
					<span class="number-box-users"><?php echo $totalCursosActivos['total']; ?></span>
				</div>
			</div>
			</a>
			<a href="courses-list.php?filter=inactivos">
			<div class="col-xs-12 col-sm-4 col-md-4">
				<div class="box-users box-bg-3">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> CURSOS INACTIVOS
					<span class="number-box-users"><?php echo $totalCursosInactivos['total']; ?></span>
				</div>
			</div>
			</a>
		</div>
		<!-- Fin Barra Usuarios -->
		<!-- Inicio Tabla Usuarios-->
		<div class="row">
			<div class="col-md-12 table-users table-responsive">				
					<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i>
					  <strong>Hecho!</strong><i id="div-msg-fail-desc"> Acción Realizada con Éxito</i>
					</div>
					<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
						<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
						<i id="div-msg-fail-desc"> No todos los cursos se eliminaron.</i>
					</div>	
					<table id="tbl_courses" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th hidden="true">Id</th>
								<th>NOMBRE</th>
								<th>INSCRITOS</th>
								<th>NO CONFIRMADOS</th>
								<th>FECHA INICIO</th>
								<th>FECHA FIN</th>
								<th>
									<div class="form-inline all-actions">
									  <select class="form-control" id="cbxActions">
										<option selected="selected">Acciones</option>
										<option value="1">Eliminar</option>
									  </select>								
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
						<?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
							  $result_count = $connect->query( 'SELECT COUNT(1) as inscritos FROM inscripciones WHERE id_curso ='.$row["id"]);
							  $inscritos = mysqli_fetch_array($result_count);
							  
							  $result_count = $connect->query( 'SELECT count(distinct id_usuario) no_inscritos FROM invitaciones where id_curso = '.$row["id"].' AND id_usuario NOT IN(SELECT distinct id_usuario from inscripciones WHERE id_curso = '.$row["id"].')');
							  $no_inscritos = mysqli_fetch_array($result_count);
							  $date_b = new DateTime($row["fecha_iniciacion"]);
							  $date_e_print = NULL;
							  if(!empty($row["fecha_finalizacion"])){
								  $date_e = new DateTime($row["fecha_finalizacion"]);
								  $date_e_print = $date_e->format('d/m/Y');						  
							  }
                               echo '  
                               <tr> 
							   		<td hidden="true">'.$row["id"].'</td>
                                    <td>'.$row["nombre"].'</td>    
                                    <td>'.$inscritos["inscritos"].'</td>  
                                    <td>'.$no_inscritos["no_inscritos"].'</td>
									<td>'.$date_b->format('d/m/Y').'</td>
									<td>'.$date_e_print.'</td>
									<td>
									<a href="javascript:fn_edit_curse('.$row["id"].');">Detalle</a>
									 | <input type="checkbox" value="'.$row["id"].'">
									</td>
                               </tr>  
                               ';  
                          }  
                          ?>
						</tbody>
					</table>				
			</div>
		</div>
		<!-- Fin Tabla Usuarios-->
		</div>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script>
$( document ).ready( function () {
	var table = $( '#tbl_courses' ).DataTable( {
		"columnDefs": [ {
		"targets": 6,
		"orderable": false
		} ],
		lengthChange: false,
		language: {info: "Viendo _START_ a _END_ de _TOTAL_ Registros"},
		"dom": '<"toolbar">frtip'
	} );
	$("div.toolbar").html('<form action="/toExcel.php?option=excelCourses" method="post"><input type="submit" value="Descargar .xls" class="btn btn-down-xls" id="btn-to-excel"></form>');
	table.buttons().container() .appendTo( '#tbl_courses_wrapper .col-sm-6:eq(0)' );
} );
var arrIds=[];
$('#tbl_courses tbody').on('change', 'input[type="checkbox"]', function(){
	if($(this).is(':checked')){
		arrIds.push(this.value);
	}else{
		arrIds.splice(arrIds.indexOf(this.value), 1);
	}
});	
$('#cbxActions').on('change', function(){
  if(this.value == 1){//Eliminar
	fn_del_courses();  
  }
});	
function fn_del_courses(){
	if(arrIds.length<1){
		alert('Seleccione registros!');
	}else if(confirm('Confirma Eliminar?')){	
		$.ajax({
			type: "POST",
			url: 'actions.php?action=deleteCourses',
			data:{ array : JSON.stringify(arrIds) },
			dataType: "json",
			success: function (data) {
				if(!data.deleteAll){
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
</script>
<?php $connect->close(); ?>
</html>