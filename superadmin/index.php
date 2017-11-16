<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$inscritos;
$cursos;
$invitaciones;

$query = $connect->query( "SELECT COUNT(distinct i.id_usuario) as quantity FROM inscripciones i JOIN usuarios u ON i.id_usuario = u.id JOIN cursos c ON i.id_curso = c.id" );
if ( $query->num_rows == 1 ) {
	$inscritos = $query->fetch_assoc();
}
$query = $connect->query( "SELECT COUNT(c.id) as quantity FROM cursos c" );
if ( $query->num_rows == 1 ) {
	$cursos = $query->fetch_assoc();
}
$query = $connect->query( "SELECT COUNT(i.id) as quantity FROM invitaciones i" );
if ( $query->num_rows == 1 ) {
	$invitaciones = $query->fetch_assoc();
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
					<li class="active"><a href="index.php">Dashboard</a>
					</li>
				</ol>
			</div>

			<div class="col-xs-6 col-sm-6 col-md-4 logout">
				<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>
			</div>

		</div>
		<!-- Fin barra de navegación-->

		<!-- Inicio Titulo Dashboard -->
		<div class="row p-texts">
			<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">
				<i class="fa fa-cog" aria-hidden="true"></i>
			</div>
			<div class="col-xs-10 col-sm-11 col-md-11">
				<h3 class="title-p">DASHBOARD</h3>
				<span class="text-p">Aquí encontrarás los menu de navegación</span>
			</div>
		</div>
		<!-- Fin Titulo Dashboard -->

		<!-- Inicio Menu Dashboard -->
		<div class="row menu-dash">
			<a href="profile.php">
				<div class="col-xs-5 col-sm-3 col-md-2 item-menu-dash">
					<div class="icon-menu-dash">
						<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					</div>
					<div class="text-menu-d">Perfil usuario</div>
				</div>
			</a>

			<a href="users-list.php">
				<div class="col-xs-5 col-sm-3 col-md-2 item-menu-dash">

					<div class="icon-menu-dash">
						<i class="fa fa-users" aria-hidden="true"></i>
					</div>
					<div class="text-menu-d">Usuarios</div>
				</div>
			</a>

			<a href="courses-list.php">
				<div class="col-xs-5 col-sm-3 col-md-2 item-menu-dash">
					<div class="icon-menu-dash">
						<i class="fa fa-book" aria-hidden="true"></i>
					</div>
					<div class="text-menu-d">Cursos</div>
				</div>
			</a>

			<a href="invitation-list.php">
				<div class="col-xs-5 col-sm-3 col-md-2 item-menu-dash">
					<div class="icon-menu-dash">
						<i class="fa fa-inbox" aria-hidden="true"></i>
					</div>
					<div class="text-menu-d">Invitaciones</div>
				</div>
			</a>

			<a href="courses-add.php">
				<div class="col-xs-5 col-sm-3 col-md-2 item-menu-dash2">
					<div class="icon-menu-dash">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
					</div>
					<div class="text-menu-d">crear curso</div>
				</div>
			</a>

		</div>
		<!-- Fin Menu Dashboard -->

		<!-- Inicio Cifras Dashboard -->
		<div class="row section-dash">
			<div class="col-xs-12 col-sm-4 col-md-4 table-dash-p">
				<div class="table-d">
					<div class="header-dash-table">
						<p>Usuarios</p>
						<a href="users-list.php">
							<p class="enlace-table-dash">Ver lista <i class="fa fa-chevron-right" aria-hidden="true"></i>
							</p>
						</a>
					</div>
					<div class="table-dash-text">
						<span class="table-number">
							<?php echo $inscritos['quantity']; ?>
						</span>
						<span class="table-number-text">Usuarios Inscritos</span>
					</div>
					<div class="table-dash-icon"><i class="fa fa-users" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 table-dash-p">
				<div class="table-d">
					<div class="header-dash-table back-table2">
						<p>Cursos</p>
						<a href="courses-list.php">
							<p class="enlace-table-dash">Ver lista <i class="fa fa-chevron-right" aria-hidden="true"></i>
							</p>
						</a>
					</div>
					<div class="table-dash-text">
						<span class="table-number">
							<?php echo $cursos['quantity']; ?>
						</span>
						<span class="table-number-text">Cursos creados</span>
					</div>
					<div class="table-dash-icon"><i class="fa fa-book" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 table-dash-p">
				<div class="table-d">
					<div class="header-dash-table back-table3">
						<p>Invitaciones</p>
						<a href="invitation-list.php">
							<p class="enlace-table-dash">Ver lista <i class="fa fa-chevron-right" aria-hidden="true"></i>
							</p>
						</a>
					</div>
					<div class="table-dash-text">
						<span class="table-number">
							<?php echo $invitaciones['quantity']; ?>
						</span>
						<span class="table-number-text">Invitaciones</span>
					</div>
					<div class="table-dash-icon"><i class="fa fa-envelope" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
		<!-- Fin Cifras Dashboard -->
	</div>
	<!-- Include Footer-->
	<?php include("../includes/footer.php"); ?>
</body>
</div>
<?php $connect->close(); ?>
</html>