<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
header( "Content-Type: text/html;charset=utf-8" );
$courseId = 0;
if( isset($_GET[ 'curso' ]) ){
	$courseId = $_GET[ 'curso' ];
}
$totalPendientes = mysqli_fetch_array( $connect->query( "SELECT COUNT(DISTINCT id_usuario) total FROM invitaciones i WHERE i.id_curso=".$courseId  ) );
$totalConfirmados = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM inscripciones i JOIN cursos c ON i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL ) AND c.id =".$courseId ) );
$listActivCourses = $connect->query( "SELECT id, nombre FROM cursos where fecha_finalizacion >= NOW() OR fecha_finalizacion IS NULL" );
$listInvitaciones = NULL;
$qyery = "SELECT u.id,u.nombres, u.apellidos, u.correo, s.sede
FROM usuarios u JOIN sedes s ON u.id_sede = s.id JOIN usuario_perfil up ON u.id = up.id_usuario AND up.perfil='Usuario' AND u.instructor = 0 AND u.id NOT IN(SELECT i.id_usuario FROM inscripciones i WHERE i.id_curso =".$courseId.")";
if($courseId!=0){
	$listInvitaciones = $connect->query($qyery);
}
date_default_timezone_set('America/Bogota');
?>
<!DOCTYPE html>
<html>
<?php 
$arrayInvites = array();	
include("../includes/head.php"); ?>
<body>

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid" id="contenedor">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active">Invitación de Usuarios
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
				<i class="fa fa-inbox" aria-hidden="true"></i>
			</div>
			<div class="col-xs-10 col-sm-9 col-md-9">
				<h3 class="title-p">INVITACIÓN DE USUARIOS</h3>
				<span class="text-p">Estos son los usuario que han sido invitados:</span>
			</div>
			<!--div class="col-xs-5 col-sm-2 col-md-2 add-user ">
				<a href="#"><span class="lnr lnr-plus-circle"></span> Crear Invitación</a>
			</div -->
		</div>
		<!-- Fin Titulo Página -->

		<!-- Inicio Barra Usuarios -->
		<form id="form-list-invi" method="post" action="">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-4 ">
				<div class="select-invitation">
					<select class="form-control" id="curso" name="curso">						
						<option value="0">Seleccione un curso activo</option>
						<?php
						while ( $row = mysqli_fetch_array( $listActivCourses ) ) {
							$selected = ( $row[ 'id' ] == $courseId ? 'selected="true"' : '' );
							echo "<option  " . $selected . "  value='" . $row[ 'id' ] . "'>" . $row[ 'nombre' ] . "</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 ">
				<div class="box-users">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> Usuarios confirmados
					<span class="number-box-users">
						<?php echo $totalConfirmados['total']; ?>
					</span>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<div class="box-users box-bg-3">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> Usuarios Pendientes
					<span class="number-box-users">
						<?php echo $totalPendientes['total']; ?>
					</span>
				</div>
			</div>
		</div>
		<!-- Fin Barra Usuarios -->

		<!-- Inicio Tabla Usuarios-->
		<div class="row">
			<div class="col-md-12 table-users table-responsive">				
					<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i>
					  <strong>Hecho!</strong> Usuarios Invitados con Éxito
					</div>
					<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
						<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
						<i id="div-msg-fail-desc">Seleccione Usuarios</i>
					</div>
					<table id="tbl_inv" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>								
								<th>USUARIO</th>
								<th>CORREO ELECTRÓNICO </th>
								<th>SEDE</th>
								<th># INVITACIONES</th>
								<th>ULTIMA INVITACIÓN</th>
								<th>
									<div class="row">
										<div class="col-xs-12 col-sm-9 col-md-10">
											<div class="form-group form-inline all-actions">
												<button type="submit" class="btn btn-default btn-aply">INVITAR</button>
											</div>

										</div>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php  
							  if( $listInvitaciones != NULL){	
								  while($row = mysqli_fetch_array($listInvitaciones))  
								  {  
									   $count_inv = mysqli_fetch_array($connect->query( "SELECT COUNT(1) as cant FROM invitaciones WHERE id_usuario =".$row["id"]." AND id_curso=".$courseId));					
									  
									  $date_lInv = mysqli_fetch_array($connect->query( "SELECT MAX(DISTINCT DATE_FORMAT(fecha,'%d/%m/%Y')) as fech FROM invitaciones WHERE id_usuario =".$row["id"]." AND id_curso=".$courseId)); 
									  echo '  
									   <tr> 											
											<td>'.$row["nombres"].' '.$row["apellidos"].'</td>                                 
											<td>'.$row["correo"].'</td>  
											<td>'.$row["sede"].'</td>								<td>'.$count_inv["cant"].'</td>
											<td>'.$date_lInv['fech'].'</td>
											<td>
											<input type="checkbox" name="id[]" value="'.$row["id"].'">
											</td>
									   </tr>  
									   ';  
								  }  
							  }
                          ?>
						</tbody>
					</table>				
			</div>
		</div>
			<input type="hidden" id="courseId" name="courseId" value="<?php echo $courseId; ?>"/>
		</form>
		<!-- Fin Tabla Usuarios-->
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>

</html>
<script>
	var arrSelectedInv=[];
	var table;
	 $('#tbl_inv tbody').on('change', 'input[type="checkbox"]', function(){
		if($(this).is(':checked')){
			arrSelectedInv.push(this.value);
		}else{
			arrSelectedInv.splice(arrSelectedInv.indexOf(this.value), 1);
		}
	});	
	
	$( document ).ready( function () {
		table = $( '#tbl_inv' ).DataTable( {
			lengthChange: false,
			"columnDefs": [ {
			"targets": 5,
			"orderable": false
			} ],
			buttons: [
			{
				extend: 'excel',
				text: 'Descargar .xls',
				className: 'btn-down-xls',
				filename: 'ListadoInvitaciones-'+new Date().getTime()
			}
    		]
		} );

		table.buttons().container()
			.appendTo( '#tbl_inv_wrapper .col-sm-6:eq(0)' );
	} );
	
	$( "#curso" ).change(function(event) {
	   $.ajax({
        url: 'actions.php?action=relodInvitationList&curso='+this.value,
        type: 'POST',
        data: $(this).serialize(),
        success: function (data) {
            console.log(data);
			location.href='invitation-list.php?curso='+data.cursoId;
        },
		error: function (data) {
            console.log(data);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;	
	});	
	
	jQuery(document).on('submit','#form-list-invi', function(event){ 
	if( arrSelectedInv.length > 0){  
		$.ajax({
			type: "POST",
			url: "actions.php?action=inviteUsers&courseId="+<?php echo $courseId; ?>,
			data:{ array : JSON.stringify(arrSelectedInv) },
			dataType: "json",
			success: function (data) {
				console.log(data);
				$('#div-msg-ok').show();
				setTimeout(function(){
					$('#div-msg-ok').hide();
					location.href= 'invitation-list.php?curso='+data.courseId;
					// Get all rows with search applied
					//var rows = table.rows({ 'search': 'applied' }).nodes();
					// Check/uncheck checkboxes for all rows in the table
					//$('input[type="checkbox"]', rows).prop('checked', false);
					arrSelectedInv=[];
				},2000);
			},
			error: function (data) {
				console.log(data);
				$('#div-msg-fail-desc').text('Ocurrio un error enviando invitaciones');
				$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();					
				},2000);
			},
		});		
	}else{
		alert('Seleccione registros.!');			
	}
    return false;
});
</script>
<?php $connect->close(); ?>