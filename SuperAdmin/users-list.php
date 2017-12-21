<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
;
$usuarios;
if( !isset($_GET[ 'filter' ]) || $_GET[ 'filter' ]=='registrados' ){
	$usuarios = $connect->query( "SELECT u.id, u.nombres, u.apellidos, u.correo, u.codigo, up.perfil, c.cargo, s.sede, u.profesion FROM usuarios u JOIN usuario_perfil up ON u.estado = 1
	AND u.id = up.id_usuario AND up.perfil<>'SuperAdmin'  JOIN cargos c ON u.id_cargo = c.id JOIN sedes s ON u.id_sede = s.id ORDER BY nombres ASC" );
}else if($_GET[ 'filter' ]=='inscritos'){
	$usuarios = $connect->query( "SELECT DISTINCT u.id, u.nombres, u.apellidos, u.correo, u.codigo, up.perfil, c.cargo, s.sede, u.profesion FROM usuarios u JOIN inscripciones i ON u.id = i.id_usuario JOIN usuario_perfil up ON u.estado = 1 AND u.id = up.id_usuario AND up.perfil<>'SuperAdmin'  JOIN cargos c ON u.id_cargo = c.id JOIN sedes s ON u.id_sede = s.id JOIN cursos cu ON i.id_curso = cu.id AND (cu.fecha_finalizacion > NOW() OR cu.fecha_finalizacion IS NULL ) ORDER BY nombres ASC");
}else if($_GET[ 'filter' ]=='sinConfirmar'){
	$usuarios = $connect->query( "SELECT DISTINCT u.id, u.nombres, u.apellidos, u.correo, u.codigo, up.perfil, c.cargo, s.sede, u.profesion FROM usuarios u JOIN usuario_perfil up ON u.estado = 1 AND u.id = up.id_usuario AND up.perfil<>'SuperAdmin' JOIN invitaciones i ON u.id = i.id_usuario JOIN cargos c ON u.id_cargo = c.id JOIN sedes s ON u.id_sede = s.id JOIN cursos cu ON i.id_curso = cu.id AND (cu.fecha_finalizacion > NOW() OR cu.fecha_finalizacion IS NULL ) ORDER BY nombres ASC" );
}
$listCargos = $connect->query( 'SELECT id, cargo FROM cargos ORDER BY cargo ASC' );
$listSedes = $connect->query( 'SELECT id, sede FROM sedes ORDER BY sede ASC' );
$listCargos = $connect->query( 'SELECT id, cargo FROM cargos ORDER BY cargo ASC' );
$totalRegistrados = mysqli_fetch_array($connect->query( "SELECT COUNT(1) total from usuarios u JOIN usuario_perfil up ON u.id = up.id_usuario AND u.estado <> 0 AND up.perfil<>'SuperAdmin'"  ));
$totalInscritos = mysqli_fetch_array($connect->query( "SELECT COUNT(DISTINCT i.id_usuario) total FROM inscripciones i JOIN cursos c ON i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL )" ));
$totalSinConfirmar = mysqli_fetch_array($connect->query( "SELECT count(DISTINCT i.id_usuario) total FROM invitaciones i JOIN cursos c ON i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL ) AND i.id_usuario NOT IN (SELECT id_usuario FROM inscripciones)  ;" ));

header( "Content-Type: text/html;charset=utf-8" );
?>
<?php include("../includes/head.php"); ?>
<style>
	.toolbar {
    float: left;
}	
</style>
<body>

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid" id="contenedor">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">
			<div class="container">
				<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li class="active"><a href="./">Dashboard</a>
					</li>
					<li class="active">Usuarios
					</li>
				</ol>
				</div>

				<div class="col-xs-6 col-sm-6 col-md-4 logout">
					<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>
				</div>
			</div>
		</div>
		<!-- Fin barra de navegación-->
		<div class="container">
			<!-- Inicio Titulo Página -->
		<div class="row p-texts">
			<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">
				<i class="fa fa-users" aria-hidden="true"></i>
			</div>
			<div class="col-xs-10 col-sm-9 col-md-9">
				<h3 class="title-p">Listado de Usuarios</h3>
				<span class="text-p">Aqui encontrarás un listado de todos los usuarios.</span>
			</div>
			<div class="col-xs-5 col-sm-2 col-md-2 add-user ">
				<a href="users-add.php"><span class="lnr lnr-plus-circle"></span> Crear Usuario</a>
			</div>
		</div>
		<!-- Fin Titulo Página -->
		<!-- Inicio Barra Usuarios -->
		<div class="row">
			<a href="users-list.php?filter=registrados">				
			<div class="col-xs-12 col-sm-4 col-md-4 ">				
				<div class="box-users">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i></span> Usuarios Registrados
					<span class="number-box-users"><?php echo $totalRegistrados['total']; ?></span>
				</div>				
			</div>
			</a>			
			<a href="users-list.php?filter=inscritos">
			<div class="col-xs-12 col-sm-4 col-md-4 ">
				<div class="box-users box-bg-2">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> Usuarios inscritos
					<span class="number-box-users"><?php echo $totalInscritos['total']; ?></span>
				</div>
			</div>
			</a>
			<a href="users-list.php?filter=sinConfirmar">
			<div class="col-xs-12 col-sm-4 col-md-4">
				<div class="box-users box-bg-3">
					<i class="fa fa-circle icon-box-users" aria-hidden="true"></i> Usuarios sin confirmar
					<span class="number-box-users"><?php echo $totalSinConfirmar['total']; ?></span>
				</div>
			</div>
			</a>
		</div>
		<!-- Fin Barra Usuarios -->
		<div class="row">
			<div class="col-md-12 table-users table-responsive">
					<div id="div-msg-ok" hidden="true" class="alert msg-ceel-ok" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i>
					  <strong>Hecho!</strong><i id="div-msg-fail-desc"> Acción Realizada con Éxito</i>
					</div>
					<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
						<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
						<i id="div-msg-fail-desc"> No todos los usuarios se eliminaron.</i>
					</div>					
					<table id="tbl_users" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						<form id="form-list-users" method="post" action="">
						<thead>
							<tr>								
								<th hidden="true">ID</th>
								<th>NOMBRES</th>
								<th>APELLIDOS</th>
								<th>CORREO</th>
								<th>CÓDIGO</th>
								<th>PERFIL</th>
								<th>CURSOS</th>
								<th hidden="true">CARGO</th>
								<th hidden="true">SEDE</th>
								<th hidden="true">PROFESIÓN</th>
								<th hidden="true">INSTRUCTOR</th>
								<th>
									<div class="form-inline all-actions">
									  <select class="form-control" id="cbxActions">
										<option selected="selected">Acciones</option>
										<option value="1">Inactivar</option>
										<option value="2">Eliminar</option>
									  </select>								
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php  
                          while($usuario = mysqli_fetch_array($usuarios))  
                          {  
							$inscripciones = mysqli_fetch_array($connect->query( 'SELECT COUNT(1) total FROM inscripciones WHERE id_usuario ='.$usuario["id"]));
							$instructor = mysqli_fetch_array($connect->query( 'SELECT COUNT(1) total FROM cursos WHERE id_instructor ='.$usuario["id"]));
							$is_instructor = 'NO';
							if( isset($instructor['total']) && $instructor['total'] > 0){
								$is_instructor = 'SI';
							}

                               echo '  
                              <tr> 								
									<td hidden="true">'.$usuario["id"].'</td>
									<td>'.$usuario["nombres"].'</td>  
									<td>'.$usuario["apellidos"].'</td>  
									<td>'.$usuario["correo"].'</td>
									<td>'.$usuario["codigo"].'</td>
									<td>'.$usuario["perfil"].'</td>  
									<td>'.$inscripciones["total"].'</td>							
									<td hidden="true">'.$usuario["cargo"].'</td>
									<td hidden="true">'.$usuario["sede"].'</td>
									<td hidden="true">'.$usuario["profesion"].'</td>
									<td hidden="true">'.$is_instructor.'</td>
									<td>
									<a href="javascript:fn_edit_user('.$usuario["id"].');">Editar </a>| <input type="checkbox" value="'.$usuario["id"].'">
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
		</div>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script>
var arrIds=[];
$('#tbl_users tbody').on('change', 'input[type="checkbox"]', function(){
	if($(this).is(':checked')){
		arrIds.push(this.value);
	}else{
		arrIds.splice(arrIds.indexOf(this.value), 1);
	}
});	
$('#cbxActions').on('change', function(){
  if(this.value == 1){//Inactivar
	fn_inactiv_users();  
  }else if(this.value == 2){//Eliminar
	 fn_del_users();
  }
});	
function fn_inactiv_users(){
	if(arrIds.length<1){
		alert('Seleccione registros!');
	}else if(confirm('Confirma Inactivar?')){	
		$.ajax({
			type: "POST",
			url: 'actions.php?action=disableUsers',
			data:{ array : JSON.stringify(arrIds) },
			dataType: "json",
			success: function (data) {
				$('#div-msg-ok').show();
				setTimeout(function(){
					location.reload();
				},2000);
			},
			error: function (data) {
				console.log(data);
			},
		});		
	}
}
function fn_del_users(){
	if(arrIds.length<1){
		alert('Seleccione registros!');
	}else if(confirm('Confirma Eliminar?')){	
		$.ajax({
			type: "POST",
			url: 'actions.php?action=deleteUsers',
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
$( document ).ready( function () {
	var date = new Date();
	var day = date.getDate();
  	var monthIndex = date.getMonth()+1;
  	var year = date.getFullYear();
	var table = $( '#tbl_users' ).DataTable( {
		"columnDefs": [ {
		"targets": 11,
		"orderable": false
		} ],
		lengthChange: false,
		language: {info: "Viendo _START_ a _END_ de _TOTAL_ Registros"},
		 buttons: [
		{
			extend: 'excel',className: 'btn-down-xls',
			text: 'Descargar .xls',
			filename: 'ListadoUsuarios_'+day+'-'+monthIndex+'-'+year
		}
		],order: [[ 1, 'asc' ]]
	} );
	table.buttons().container()
		.appendTo( '#tbl_users_wrapper .col-sm-6:eq(0)' );
	
} );
</script>
<?php $connect->close(); ?>