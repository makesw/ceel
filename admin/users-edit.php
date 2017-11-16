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

$userCourses = $connect->query( "SELECT c.* FROM inscripciones i JOIN cursos c ON i.id_usuario = ".$idUser." AND i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL)" );
	
	
?>
<!DOCTYPE html>
<html>
<?php include( "../includes/head.php" ); ?>
<body class="bg-2">
	<form action="users-edit.php" method="post" id="form-edit-user">
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
						<li class="active">Editar Usuario
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
					<h3 class="title-p">Editar Usuario</h3>
					<span class="text-p">Aquí puedes editar los datos del usuario.</span>
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
						<input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo $data[ 'nombres' ]; ?>">
					</div>
					<div class="form-group">
						<label for="apellidos">Apellidos</label>
						<input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $data[ 'apellidos' ]; ?>">
					</div>
					<div class="form-group">
						<label for="codigo">Código</label>
						<input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $data[ 'codigo' ]; ?>">
					</div>
					<div class="form-group">
						<label for="sede">Sede</label>
						<select class="form-control" id="sede" name="sede">
							<?php
							while ( $row = mysqli_fetch_array( $listSedes ) ) {
								$selected = ( $row[ 'id' ] == $data[ 'id_sede' ] ? 'selected="true"' : '' );
								echo "<option " . $selected . " value='" . $row[ 'id' ] . "'>" . $row[ 'sede' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Constraseña</label>
						<input type="text" id="password" name="password" class="form-control" value="<?php echo $data[ 'password' ]; ?>" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="correo">Email</label>
						<input type="text" class="form-control" id="correo" name="correo" value="<?php echo $data[ 'correo' ]; ?>">
					</div>
					<div class="form-group">
						<label for="cargo">Cargo</label>
						<select class="form-control" id="cargo" name="cargo">
							<?php
							while ( $row = mysqli_fetch_array( $listCargos ) ) {
								$selected = ( $row[ 'id' ] == $data[ 'id_cargo' ] ? 'selected="true"' : '' );
								echo "<option " . $selected . " value='" . $row[ 'id' ] . "'>" . $row[ 'cargo' ] . "</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="perfil">Tipo de usuario</label>
						<select class="form-control" id="perfil" name="perfil">
							<option <?php echo( $data[ 'perfil' ] == 'Usuario' ? 'selected="true"' : '' ); ?> value="Usuario">Usuario</option>
							<!--option <?php echo( $data[ 'perfil' ] == 'Admin' ? 'selected="true"' : '' ); ?> value="Admin">Admin</option -->
							<!--option <?php echo( $data[ 'perfil' ] == 'SuperAdmin' ? 'selected="true"' : '' ); ?> value="SuperAdmin">Super Admin</option -->
						</select>
					</div>
					<div class="form-group">
						<label for="profesion">Profesión</label>
						<input type="text" class="form-control" id="profesion" name="profesion" value="<?php echo $data[ 'profesion' ]; ?>">
					</div>
					<div class="form-group">
						<label for="perfil">Instructor</label>
						<select class="form-control" id="instructor" name="instructor">
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
							<td class="last-info-edit">Último Ingreso</td>
						</tr>
						<?php while ( $course = mysqli_fetch_array( $userCourses ) ) { 
							/**Calcular avance de curso:**/
							$avanceCurso = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM avances a JOIN slides s ON a.id_slide = s.id AND a.id_usuario = " . $idUser . " JOIN lecciones l ON s.id_leccion = l.id JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id =" . $course[ 'id' ] ) );
							/**Calcular slides de curso:**/
							$slides = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM cursos c JOIN unidades u ON c.id = u.id_curso AND c.id = ".$course['id']." JOIN lecciones l ON u.id = l.id_unidad JOIN slides s ON l.id = s.id_leccion" ) );
							/**Consultar ultimo ingreso:**/
							$lastIn = mysqli_fetch_array( $connect->query( "SELECT MAX(a.fecha) lastIn FROM avances a where a.id_curso  =".$course[ 'id' ]." AND id_usuario=".$idUser ) );
							$date_b = NULL;
							if( isset($lastIn['lastIn']) && $lastIn['lastIn']!=NULL ){
								$date_b = new DateTime($lastIn['lastIn']);
							}
							/**Calcular porcentaje:**/
							$porcentage = 0;
							if ( $slides[ 'total' ] != 0 ) {
								$porcentage = round( ( $avanceCurso[ 'total' ] / $slides[ 'total' ] ) * 100 );
							}						
						?>
							<tr>
								<td class="icon-edit"><span class="glyphicon glyphicon-record"></span>
								</td>
								<td><?php echo $course['nombre'];?></td>
								<td class="last-info-edit"><?php echo $porcentage; ?></td>
								<td class="last-info-edit"><?php if($date_b!=NULL){echo strftime("%d de %B del %Y",$date_b->getTimestamp());} ?></td>
							</tr>					
						<?php } ?>
					</table>
				</div>
			</div>
			<div class="row btn-save">
				<div id="div-msg-ok"  hidden="true" class="alert msg-ceel-ok" role="alert">
				<i class="fa fa-check" aria-hidden="true"></i>
			  <strong>Hecho!</strong> <i id="div-msg-ok-desc">Acción Realizada con Éxito</i>
			</div>
			<div id="div-msg-fail"  hidden="true"  class="alert msg-ceel-fail" role="alert">
				<i class="fa fa-times" aria-hidden="true"></i> <strong>Error!</strong>
				<i id="div-msg-fail-desc">No se Pudo Realizar la Acción</i>
			</div>
				<div class="form-group">
					<input type="hidden" name="optButton" id="optButton">
					<button id="btn_save" type="submit" class="btn btn-primary">GUARDAR</button>
					<button id="btn_save_back" type="submit" class="btn btn-primary">GUARDAR y VOLVER</button>
					<a href="javascript:go_back();">
						<button type="button" class="btn btn-primary">CANCELAR</button>
					</a>	
				</div>			
			</div>
		</div>
		<!-- Include Footer-->
		<?php include("../includes/footer.php"); ?>
		<input type="hidden" id="user_id" name="user_id" value="<?php echo $data[ 'id' ]; ?>"/>
	</form>
</body>
</html>
<script>
$( "#btn_save" ).click(function() {
	  $( "#optButton" ).val("save");
	});
	$( "#btn_save_back" ).click(function() {
	  $( "#optButton" ).val("save_back");
	});
function go_back() {
	window.history.back();
}	
</script>
<?php $connect->close(); ?>