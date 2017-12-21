<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$idCurso = $_GET[ 'idCurso' ];
$cursoAprobado = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM aprobacion_cursos WHERE id_curso =".$idCurso." AND id_usuario=".$_SESSION[ 'dataSession' ]['id'] ));

?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="/css/styles-test.css">
<link rel="stylesheet" href="../css/evaluation.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<link rel="stylesheet" href="/css/font-awesome-css.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="text-final">
					<i class="fa fa-graduation-cap" aria-hidden="true"></i>
					<span>¡FELICITACIONES!</span>
					<p>Haz completado el curso de:<br><span>Manejo de recursos y residuos BT</span><br>dale clic al boton y descarga tu certificado</p>
					<button onclick="location.href = '/Usuario/profile.php'" class="btn btn-primary">DESCARGAR CERTIFICADO</button>
				</div>
			</div>
		</div>
	</div>
</body>
<?php 
	if($cursoAprobado['total'] < 1){
		//INSERTAR EN APROBACION CURSOS	 
		$query = "INSERT INTO aprobacion_cursos ( id_usuario,id_curso,calificacion,fecha )
		VALUES
		(   '" . $_SESSION[ 'dataSession' ]['id'] . "', '".$idCurso."', NULL ,  now() 
		)";
		$result = $connect->query( $query );	
	}
?>
</html>
<?php $connect->close(); ?>