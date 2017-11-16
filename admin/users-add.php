<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$listCargos = $connect->query( 'SELECT id, cargo FROM cargos ORDER BY cargo ASC' );
$listSedes = $connect->query( 'SELECT id, sede FROM sedes ORDER BY sede ASC' );

header( "Content-Type: text/html;charset=utf-8" );
?>
<?php include("../includes/head.php"); ?>
<link rel="stylesheet" href="../css/styles.css">
<body class="bg-2">

	<?php include("../includes/header2.php"); ?>

	<div class="container-fluid">
		<!-- Inicio barra de navegación-->
		<div class="row header-bread">

			<div class="col-xs-6 col-sm-6 col-md-8">
				<ol class="breadcrumb">
					<li><a href="./">Dasboard</a>
					</li>
					<li><a href="users-list.php">Usuarios</a>
					</li>
					<li class="active"><a href="#">Crear Usuario</a>
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
				<span class="lnr lnr-user"></span>
			</div>
			<div class="col-xs-10 col-sm-11 col-md-11">
				<h3 class="title-p">Crear Usuario</h3>
				<span class="text-p">Aquí puedes crear usuarios.</span>
			</div>
		</div>
		<!-- Fin Titulo Página -->
		<form action="actions.php?action=createUser" method="post" accept-charset="utf-8" id="form-add-user" enctype="multipart/form-data">
			<div class="row edit-user">
			<div class="col-md-3">
					<div class="avatar-user">
						<img src="../assets/avatar/avatar-user-lg.jpg" alt="..." class="img-circle">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Nombres</label>
						<input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ingrese el nombre" required>
					</div>
					<div class="form-group">
						<label for="">Apellidos</label>
						<input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ingrese el nombre" required>
					</div>
					<div class="form-group">
						<label for="">Código</label>
						<input type="text" id="codigo" name="codigo" class="form-control" placeholder="Ingrese el codigo" maxlength="10" required>
					</div>
					<div class="form-group">
						<label for="">Sede</label>
						<select class="form-control" id="sede" name="sede" required>
							<?php
							while ( $row = mysqli_fetch_array( $listSedes ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'sede' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Constraseña</label>
						<input type="text" id="password" name="password" class="form-control" placeholder="Ingrese la constraseña" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Email</label>
						<input type="email" id="correo" name="correo" required class="form-control" placeholder="Ingrese el correo">
					</div>
					<div class="form-group">
						<label for="">Cargo</label>
						<select class="form-control" id="cargo" name="cargo">
							<?php
							while ( $row = mysqli_fetch_array( $listCargos ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'cargo' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Tipo de usuario</label>
						<select class="form-control" id="perfil" name="perfil">
							<option value="Usuario">Usuario</option>
							<option value="Admin">Admin</option>
							<!--option value="SuperAdmin">Super Admin</option -->
						</select>
					</div>
					<div class="form-group">
						<label for="">Profesión</label>
						<input type="text" id="profesion" name="profesion" class="form-control" placeholder="Ingrese la profesión">
					</div>
					<div class="form-group">
						<label for="">Instructor</label>
						<select class="form-control" id="instructor" name="instructor">
							<option value="0">NO</option>
							<option value="1">SI</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row btn-save">								
					<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">
						<i class="fa fa-check" aria-hidden="true"></i>
					  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Usuario Creado con Éxito</i>
					</div>
					<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
						<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
						<i id="div-msg-fail-desc">No se Pudo Realizar la Acción</i>
					</div>
					<div>
						<input type="hidden" name="optButton" id="optButton">
						<button id="btn_save" type="submit" class="btn btn-primary">GUARDAR</button>
						<button id="btn_save_back" type="submit" class="btn btn-primary">GUARDAR y VOLVER</button>
					</div>
			</div>
		</form>
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
<script type="text/javascript">
$( "#btn_save" ).click(function() {
  $( "#optButton" ).val("save");
});
$( "#btn_save_back" ).click(function() {
  $( "#optButton" ).val("save_back");
});
</script>
<?php $connect->close(); ?>