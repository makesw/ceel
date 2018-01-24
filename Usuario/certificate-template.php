<?php ob_start();
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$idCourse = $_GET[ 'idCourse' ];
$curso = mysqli_fetch_array($connect->query("SELECT c.nombre, a.fecha, u.nombres, u.apellidos FROM cursos c JOIN aprobacion_cursos a ON c.id=a.id_curso AND c.id=".$idCourse." JOIN usuarios u ON a.id_usuario = u.id and u.id = ".$_SESSION[ 'dataSession' ]['id']));
$date_a = new DateTime($curso["fecha"]);
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CEEL - Certificados de Curso</title>
<link rel="stylesheet" href="../css/certificate.css">
<link href="../css/fonts-Roboto.css" rel="stylesheet">
<link href="../css/fonts-BreeSerift.css" rel="stylesheet">
</head>
<body style="background: #fff;">
<table style="height: 300px; width: 100%;"><tr><td>
	<div class="container-certificate">
		<div class="content-certificate">
			<div class="logo">
				<img src="../assets/img/logo-certificate.png" alt="">
			</div>
			<br/><br/>
			<div class="info-certificate">
				<div class="content">
					<span class="date"><?php echo strftime("%d de %B del %Y",$date_a->getTimestamp()); ?></span>
					<p class="name-user"><?php echo $curso['nombres'].' '.$curso['apellidos']; ?>.</p>
				</div>
			<div class="course-info">
				<span>Haz completado el curso de:</span>
				<p class="name-course">
					<?php echo $curso['nombre']; ?>
				</p>
			</div>
			<div class="legal-course">
				<p>BT LATAM, te certifica el desarrollo del curso realizado en nuestra plataforma digital, cumpliendo con todos los requisitos establecido.</p>
			</div>
			</div>
			<div class="img-course">
				<img src="../assets/img/icon-graduation.png" alt="">
			</div>
			<div class="firmas">
				<div class="firmaUno">
					<img src="../assets/img/firmaUno.png" alt="">
					<span>Ing. Francisco Alvarez</span>
					<span class="cargo">Gerente General BT</span>
				</div>
				<div class="firmaDos">
					<img src="../assets/img/firmaDos.png" alt="">
					<span>Dr. Jenny Carranza</span>
					<span class="cargo">Recursos Humanos BT</span>
				</div>
			</div>
		</div>
	</div>
</td></tr></table>
</body>
</html>
<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('latter', 'landscape');
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('certificado_'.$curso['nombre'].'.pdf');
?>