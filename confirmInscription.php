<?php 
require './conexion.php';	
include("./constants.php");
$userId = $_GET['user'];
$courseId = $_GET['course'];
//1.validate iscription:
$inscripcion = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total from inscripciones WHERE id_usuario=" . $userId . " AND id_curso=" . $courseId ) );	
if ( $inscripcion[ 'total' ] < 1 ) {
	$query = "INSERT INTO inscripciones
		( id_usuario,id_curso,fecha
		)
		VALUES
		( '" . $userId . "', '" . $courseId . "', NOW() )";
	$result = $connect->query( $query );
	if($result = 1){
		//eliminar registro de invitaciones:
		$result = $connect->query( "DELETE FROM invitaciones WHERE id_usuario=".$userId." AND id_curso=".$courseId );
	}
}	
$usuario = mysqli_fetch_array( $connect->query("SELECT u.* FROM usuarios u WHERE u.id=".$userId) ); 
$curso = mysqli_fetch_array( $connect->query("SELECT c.* FROM cursos c WHERE c.id=".$courseId) ); 
?>
<!DOCTYPE html>
<html>
<head>
<?php include("./includes/head.php"); ?>
<?php include("./includes/header.php");?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">	
<link rel="stylesheet" href="./css/styles.css">

</head>
<body class="bg-2">
	<div class="container-fluid">
		<!-- Inicio Menu Dashboard -->
		<div class="row">
			<div class="col-sm-3 col-md-3"></div>
			<div class="col-sm-6 col-md-6 confirmation img-p">
				<br>
				<img src="./assets/img/confirmation.png" alt="img-responsive">
				<br>
				<p class="title">¡YA ESTÁS INSCRITO!</p>
				<p>Hola <?php echo $usuario['nombres'].' '.$usuario['apellidos']; ?>, haz aceptado la invitación al curso: <?php echo $curso['nombre']; ?>. <span>Culmina el curso y descarga tu certificado.</span></p>
				<a href="<?php echo $RAIZ_CEEL;?>Usuario/">
					<button class="btn-back">Ingresar Ahora</button>
				</a>
			</div>
			<div class="col-sm-3 col-md-3"></div>
		</div>
		<!-- Fin Menu Dashboard -->

	</div>
	<?php include("./includes/footer.php"); ?>
</body>
</html>