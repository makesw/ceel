<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
require('../sendMail.php');
$idCurso = $_GET[ 'id_curso' ];
$idUnidad = $_GET[ 'idUnidad' ];
$idUsuario = $_GET[ 'idUsuario' ];
$aprobo = false;
//preguntas estudiante evaluacion / unidad :
$preguntas = $connect->query("SELECT DISTINCT id_pregunta id FROM evaluaciones e WHERE e.id_usuario = ".$idUsuario." AND e.id_curso = ".$idCurso." AND e.id_unidad =".$idUnidad);
//consultar curso:
$curso = mysqli_fetch_array( $connect->query( "SELECT * FROM cursos c WHERE c.id =".$idCurso ));
//consultar unidad:
$unidad = mysqli_fetch_array( $connect->query( "SELECT * FROM unidades u WHERE u.id =".$idUnidad ));
//consultar usuario:
$usuario = mysqli_fetch_array( $connect->query( "SELECT * FROM usuarios u WHERE u.id =".$idUsuario ));

$totalPreguntas = 0;
$totalPreguntasOK = 0;
$arrayLossLssons = array();
while ( $preg = mysqli_fetch_array( $preguntas ) ) {
	$totalPreguntas++;
	//contar respuestas correctas de pregunta:
	$okPregunta = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM respuestas WHERE id_pregunta = ".$preg['id']." and es_correcta" ) );
	//contar en evalauciòn cuantas correctas hay de la pregunta / usuario / unidad :
	$okPreguntaEval = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM evaluaciones e JOIN respuestas r ON e.id_respuesta = r.id AND e.id_usuario = ".$idUsuario." AND e.id_unidad = ".$idUnidad." AND e.id_pregunta = ".$preg['id']." AND r.es_correcta" ) );
	if( $okPregunta['total'] == $okPreguntaEval['total']){
		$totalPreguntasOK++;
	}else{
		//consultar leccion de la pregunta:
		$lossLesson = mysqli_fetch_array( $connect->query( "SELECT l.nombre FROM preguntas p JOIN lecciones l ON p.id = ".$preg['id']." AND p.id_leccion = l.id LIMIT 1" ) );
		$arrayLossLssons[]=$lossLesson['nombre'];
	}
}
/***CALCULAR PORCENTAJE:***/
$porcentaje = 0;
$decAprobo='No Aprobada';
if( $totalPreguntas != 0 ){
	$porcentaje = ($totalPreguntasOK/$totalPreguntas)*100;
}
$porcentaje = round($porcentaje);
if( $porcentaje >= 80 ){
	$aprobo = true;
	$decAprobo='Aprobada';
}
date_default_timezone_set('America/Bogota');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Resultado Evaluación</title>
</head>	
<body style="background-color: #12122e; align: center; font-family: Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif;">
<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e;margin-top: 20px">
  <tbody>       
    <tr style="background-color:#FFFFFF">
      <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div style="display:inline-block; background-color:#12122e; color:#e9a400; border-radius:100px; border:rgba(163,163,163,1.00) 6px solid; float:right"><h1 style="font-size:60px; line-height:35px; margin:30px 20px 10px;"><!--PORCENTAJE DE PREGUNTAS CONTESTADAS CORRECTAMENTE--><?php echo $porcentaje; ?><label style="font-size:15px; display:block; text-align:center; color:#D9D9D9; margin:0; padding:0; font-weight:200">NOTA</label></h1></div>
        <h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">RESULTADO EVALUACIÓN</h3>
        <h5 style="color: #0a0a2b; font-weight: 600; font-size: 1.5em; margin:-5px 0px 0px"><!--nombre del curso--><?php echo $curso['nombre'] ?></h5>
        <p style="margin:0;"><?php echo date("d/m/Y"); ?></p>
        <p>Haz presentado la evaluación de la unidad <strong style="color: #5a5ab4; "><!--nombre dela unidad--><?php echo $unidad['nombre'] ?> </strong>y estos son los resultados:</p>
        <h2><!--RESULTADO--><?php echo $decAprobo; ?></h2>
      </td>
    </tr>
    <?php 
	if(!$aprobo && $unidad['requerida']){ 	
	//Consyar slides de unidad 	
	$leccines = $connect->query( "SELECT COUNT(1) total FROM slides l WHERE l.id_unidad =".$idUnidad );	
		
	?>
    <tr>
      <td>
          <h5 style="color: #5a5ab4; margin-bottom:5px">TEMAS A REPASAR:</h5>
          <ol style="list-style-type: none; margin-left: 0px; text-indent: -30px;"><!--lecciones de las que falló la pregunta-->
             <?php  
				//iterar lecciones:
				$iter = 1;
				$arrayLossLssons = array_unique($arrayLossLssons);
				foreach($arrayLossLssons as $valor){	
			  ?>
              <li style="padding:5px 0"><strong style="background-color:#5a5ab4; color:white; padding:1px 6px; border-radius:10px"><?php echo $iter; ?></strong> <?php echo $valor; ?></li>	             
              <?php $iter++; } ?>
          </ol>
		</td>
    </tr>
     <tr>
      	<td>
      		<a href="../Usuario/course-nav.php?idUnidadLoss=<?php echo $idUnidad;?>&id_course=<?php echo $idCurso;?>" target="_parent">
				<button class="btn btn-list">VOLVER</button>
			</a>
		</td>
    </tr> 
    <tr>
      	<td>&nbsp;  			
		</td>
    </tr>    
    <?php } ?>
    <?php if($aprobo || !$unidad['requerida'] ){?>
	<tr>
	<td>
		<a href="javascript:reload(<?php echo $idCurso; ?>);">
			<button class="btn btn-list">CONTINUAR</button>
		</a>
	</td>
    </tr> 
     <tr>
      	<td>&nbsp;  			
		</td>
    </tr> 
    <?php } ?>
  </tbody>
</table>
</td>
</tr>
</tbody>
</table>
<?php 
	if($aprobo || !$unidad['requerida']){
		//INSERTAR EN APROBACION UNIDADES	 
		$query = "INSERT INTO aprobacion_unidades ( id_usuario,id_curso,id_unidad,calificacion,fecha )
		VALUES
		(   '" . $_SESSION[ 'dataSession' ]['id'] . "', '".$idCurso."','".$idUnidad."', NULL ,  now() 
		)";
		$result = $connect->query( $query );	
	}
	if(!$aprobo && $unidad['requerida']){ //si no aprobó y la unidad es requerida, eliminar avances de unidad
		$result = $connect->query( "DELETE FROM avances WHERE id_usuario = ". $_SESSION[ 'dataSession' ]['id'] ." AND id_curso = ".$idCurso." AND id_slide IN (SELECT s.id FROM slides s JOIN lecciones l ON s.id_leccion = l.id JOIN unidades u ON l.id_unidad = u.id AND u.id = ".$idUnidad.")" );	
	}
	//enviar correo con el resultado de evaluacion:
	$enviar = enviarCorreoResultadoExamen($usuario, $curso, $unidad, $porcentaje, $decAprobo, $arrayLossLssons);	

?>
</body>
</html>
<script>
function go_back() {
		window.history.back();
}
function reload( idCourse ) {
 window.parent.location.href='/Usuario/course-nav.php?id_course='+idCourse;
}	
<?php if($aprobo){ ?>
	//window.parent.document.getElementById('sig').style.display = 'block';
<?php } ?>	
</script>
<?php $connect->close(); ?>