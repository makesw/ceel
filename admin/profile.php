<?php

session_start();

if ( !isset( $_SESSION[ 'dataSession' ] ) ) {

	header( 'Location: ../index.php' );

}

require '../conexion.php';

$listCargos = $connect->query( 'SELECT id, cargo FROM cargos ORDER BY cargo ASC' );

$listSedes = $connect->query( 'SELECT id, sede FROM sedes ORDER BY sede ASC' );

$listCursos = $connect->query( 'SELECT c.fecha_iniciacion, c.nombre, u.nombres, u.apellidos, c.url_icono 

FROM cursos c JOIN usuarios u ON c.id_creador = u.id AND c.fecha_finalizacion > NOW()' );

setlocale (LC_TIME,"spanish");

date_default_timezone_set('America/Bogota');

?>

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

					<li class="active">Perfil

					</li>

				</ol>

			</div>



			<div class="col-xs-6 col-sm-6 col-md-4 logout">

				<a href="../salir.php"><span class="lnr lnr-exit"></span> Cerrar sesión</a>

			</div>



		</div>

		<!-- Fin barra de navegación-->



		<div class="row">

			<form action="" id="formProfile" method="post" enctype="multipart/form-data">

			<!-- Inicio Sidebar Perfil-->

			<aside class="col-xs-12 col-sm-3 col-md-3 profile">

				<div class="avatar-profile">

					<img id="avtUser" width="150px" height="150px" src="<?php if (isset($_SESSION['dataSession'][ 'url_foto'])){echo $_SESSION['dataSession'][ 'url_foto'];}else{echo '../assets/avatar/avatar-user-lg.jpg';} ?>" alt="..." class="img-circle">

					<input type="file" class="upload-img" id="avatarFiele" name="avatarFiele" disabled onchange="readURL(this);" sty/>

					<p>

						<?php echo($_SESSION['dataSession']['nombres'].' '.$_SESSION['dataSession']['apellidos']); ?>

						<span>

							<?php echo($_SESSION['dataSession']['cargo']); ?>

						</span>

					</p>					

				</div>

				<div class="form-profile">					

						<div class="form-group">

							<div class="row">

								<div class="col-xs-2 col-sm-3 col-md-3 icon-profile">

									<i class="fa fa-envelope" aria-hidden="true"></i>

								</div>

								<div class="col-xs-10 col-sm-9 col-md-9 input-profile">

									<input class="form-control" id="prof-mail" name="prof-mail" type="text" value="<?php echo($_SESSION['dataSession']['correo']); ?>" disabled="true">	

								</div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-xs-2 col-sm-3 col-md-3 icon-profile">

									<i class="fa fa-phone" aria-hidden="true"></i>

								</div>

								<div class="col-xs-10 col-sm-9 col-md-9 input-profile">

									<input class="form-control" id="prof-codigo" name="prof-codigo" type="text" value="<?php echo($_SESSION['dataSession']['codigo']); ?>" disabled="true">

								</div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-xs-2 col-sm-3 col-md-3 icon-profile">

								<i class="fa fa-address-book" aria-hidden="true"></i>

							</div>

							<div class="col-xs-10 col-sm-9 col-md-9 input-profile">

								<select id="prof-cargo" name="prof-cargo" disabled="true" class="form-control">

									<?php

									while ( $row = mysqli_fetch_array( $listCargos ) ) {

										$selected = ( $row[ 'cargo' ] == $_SESSION[ 'dataSession' ][ 'cargo' ] ? 'selected="true"' : '' );

										echo "<option " . $selected . " value='" . $row[ 'id' ] . "'>" . $row[ 'cargo' ] . "</option>";

									}

									?>

								</select>	

							</div>

							</div>

						</div>

						<div class="form-group">

							<div class="row">

								<div class="col-xs-2 col-sm-3 col-md-3 icon-profile">

								<i class="fa fa-building" aria-hidden="true"></i>

							</div>

							<div class="col-xs-10 col-sm-9 col-md-9 input-profile">

								<select id="prof-sede" name="prof-sede" disabled="true" class="form-control">

									<?php

									while ( $row = mysqli_fetch_array( $listSedes ) ) {

										$selected = ( $row[ 'sede' ] == $_SESSION[ 'dataSession' ][ 'sede' ] ? 'selected="true"' : '' );

										echo "<option " . $selected . " value='" . $row[ 'id' ] . "'>" . $row[ 'sede' ] . "</option>";

									}

									?>

								</select>

							</div>

							</div>

						</div>

						<div class="form-group logout-profile" id="div-editprof">

							<span onclick="profileEditAdmin();" style="cursor: pointer;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar Perfil</span>

						</div>

						<div class="form-group logout-profile" hidden="true" id="div-saveprof">

							<button id="btnGuardar" type="submit" class="btn btn-primary btn-aply">Guardar</button>

						</div>					

				</div>				

			</aside>

			<!-- Fin Sidebar Perfil-->



			<!-- Inicio contenido derecho-->

			<div class="col-xs-12 col-sm-9 col-md-9">

				<div class="row p-texts">

					<div class="col-xs-2 col-sm-1 col-md-1 icon-pt">

						<i class="fa fa-book" aria-hidden="true"></i>

					</div>

					<div class="col-xs-10 col-sm-11 col-md-11">

						<h3 class="title-p">Cursos Disponibles</h3>

						<span class="text-p">Hola, <?php echo($_SESSION['dataSession']['nombres'].' '.$_SESSION['dataSession']['apellidos']); ?> estos son los cursos que están disponibles para ti.

					</div>

				</div>

				<div class="row">

					<?php  

				  while($row = mysqli_fetch_array($listCursos))  

				  {  

					  $date_b = new DateTime($row["fecha_iniciacion"]);

					  echo '  

					  <div class="col-xs-11 col-sm-5 col-md-5 list-course-profile">

						<div class="fecha-course">'.strftime("%d de %B del %Y",$date_b->getTimestamp()).'</div>

						<div class="content-list-profile">

							'.$row['nombre'].' <br><span>Instructor: '.$row['nombres'].' '.$row['apellidos'].'</span>

						</div>

						<div class="icon-list-profile"><span"><img src="'.$row['url_icono'].'"/></span></div>

					</div>

					   ';  

				  }  

				?>

				</div>

			</div>

			<!-- Fin contenido derecho-->

			</form>

		</div>

	</div>

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>

<script type="text/javascript">

	function readURL( input ) {

		if ( input.files && input.files[ 0 ] ) {

			var typeFile = input.files[ 0 ].type;

			if( typeFile=="image/jpeg" || typeFile=="image/jpg" || typeFile=="image/png" ){

				var reader = new FileReader();

				reader.onload = function ( e ) {

					$( '#avtUser' ).attr( 'src', e.target.result );

				}

				reader.readAsDataURL( input.files[ 0 ] );

			}else{

				alert("Tipo de imagen inválido");

			}

		}

	}

</script>

<?php $connect->close(); ?>