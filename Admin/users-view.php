<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}

require '../conexion.php';
$listCargos = $connect->query( 'SELECT id, cargo FROM cargos ORDER BY cargo ASC' );
$listSedes = $connect->query( 'SELECT id, sede FROM sedes ORDER BY sede ASC' );

header( "Content-Type: text/html;charset=utf-8" );
$idUser = $_GET[ 'idUser' ];
$query = "SELECT u.*, up.perfil FROM usuarios u JOIN usuario_perfil up ON u.id = up.id_usuario AND u.id='" . $idUser . "'";
$result = $connect->query( $query );
$data = mysqli_fetch_array( $result );
?>
<!DOCTYPE html>
<html>
<?php include( "../includes/head.php" ); ?>
<body class="bg-2">
	<form action="users-view.php" method="post" readonly id="form-edit-user">
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
						<li class="active"><a href="#">Editar Usuario</a>
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
					<h3 class="title-p">Ver Usuario</h3>
					<span class="text-p">Aquí puedes ver los datos del usuario.</span>
				</div>
			</div>
			<!-- Fin Titulo Página -->

			<div class="row edit-user">
				<div class="col-md-3">
					<div class="avatar-user">											
						<img src="<?php if (isset($data[ 'url_foto'])){echo $data[ 'url_foto'];}else{echo '../assets/avatar/avatar-user-lg.jpg';} ?>" alt="..." class="img-circle">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="nombres">Nombres</label>
						<input type="text" class="form-control" readonly id="nombres" name="nombres" value="<?php echo $data[ 'nombres' ]; ?>">
					</div>
					<div class="form-group">
						<label for="apellidos">Apellidos</label>
						<input type="text" class="form-control" readonly id="apellidos" name="apellidos" value="<?php echo $data[ 'apellidos' ]; ?>">
					</div>
					<div class="form-group">
						<label for="telefono">Teléfono</label>
						<input type="text" class="form-control" readonly id="telefono" name="telefono" value="<?php echo $data[ 'telefono' ]; ?>">
					</div>
					<div class="form-group">
						<label for="sede">Sede</label>
						<select class="form-control" disabled id="sede" name="sede">
							<?php
							while ( $row = mysqli_fetch_array( $listSedes ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'sede' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Constraseña</label>
						<input type="text" readonly id="password" name="password" class="form-control" value="<?php echo $data[ 'password' ]; ?>" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="correo">Email</label>
						<input type="text" class="form-control" readonly id="correo" name="correo" value="<?php echo $data[ 'correo' ]; ?>">
					</div>
					<div class="form-group">
						<label for="cargo">Cargo</label>
						<select class="form-control" disabled id="cargo" name="cargo">
							<?php
							while ( $row = mysqli_fetch_array( $listCargos ) ) {
								echo "<option value='" . $row[ 'id' ] . "'>" . $row[ 'cargo' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="perfil">Tipo de usuario</label>
						<select class="form-control" disabled id="perfil" name="perfil">
							<option value="Usuario">Usuario</option>
							<option value="Admin">Admin</option>
							<option value="Super admin">Super admin</option>
						</select>
					</div>
					<div class="form-group">
						<label for="profesion">Profesión</label>
						<input type="text" class="form-control" readonly id="profesion" name="profesion" value="<?php echo $data[ 'profesion' ]; ?>">
					</div>
					<div class="form-group">
						<label for="perfil">Instructor</label>
						<select class="form-control" disabled id="instructor" name="instructor">
							<option <?php echo( $data[ 'instructor' ] == '0' ? 'selected="true"' : '' ); ?> value="0">NO</option>
							<option <?php echo( $data[ 'instructor' ] == '1' ? 'selected="true"' : '' ); ?> value="1">SI</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row edit-courses">
				<div class="col-md-11 title-edit">
					<h4>CURSOS ASOCIADOS</h4>
					<table class="table table-responsive">
						<tr>
							<td>Estado</td>
							<td>Curso</td>
							<td class="last-info-edit">% Avance</td>
						</tr>
						<tr>
							<td class="icon-edit"><span class="glyphicon glyphicon-record"></span>
							</td>
							<td>El agua</td>
							<td class="last-info-edit">0</td>
						</tr>
						<tr>
							<td class="icon-edit"><span class="glyphicon glyphicon-record"></span>
							</td>
							<td>Medio Ambiente</td>
							<td class="last-info-edit">40</td>
						</tr>
						<tr>
							<td class="icon-edit"><span class="glyphicon glyphicon-record"></span>
							</td>
							<td>El planeta</td>
							<td class="last-info-edit">100</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row btn-save">
				<div readonly id="msg-ok-edit-user" hidden="true" style="color:green;font-size: 15px;">
					Usuario Editado Con Éxito.
				</div>
			</div>
		</div>
		<!-- Include Footer-->
		<?php include("../includes/footer.php"); ?>
		<input type="hidden" readonly id="user_id" name="user_id" value="<?php echo $data[ 'id' ]; ?>"/>
	</form>
</body>

</html>
<?php $connect->close(); ?>