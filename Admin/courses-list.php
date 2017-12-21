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
					<table id="tbl_courses" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<form id="form-list-users" method="post" action="">
						<thead>
							<tr>
								<th hidden="true">Id</th>
								<th>NOMBRE</th>
								<th>INSCRITOS</th>
								<th>NO CONFIRMADOS</th>
								<th>FECHA INICIO</th>
								<th>FECHA FIN</th>
								<th>ACCIÓN</th>
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
									</td>
                               </tr>  
                               ';  
                          }  
                          ?>
						</tbody>
						</form>
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
			lengthChange: false,
			/* buttons: [
			{
				extend: 'excel',
				text: 'Descargar .xls',
				filename: 'ListadoCursos-'+new Date().getTime()
			}
    		]*/
			"dom": '<"toolbar">frtip'
		} );
		$("div.toolbar").html('<form action="/toExcel.php?option=excelCourses" method="post"><input type="submit" value="Descargar .xls" class="btn btn-down-xls" id="btn-to-excel"></form>');
		table.buttons().container().appendTo( '#tbl_users_wrapper .col-sm-6:eq(0)' );
	} );
</script>
<?php $connect->close(); ?>
</html>