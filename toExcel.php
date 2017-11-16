<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
setlocale (LC_TIME,"spanish");
date_default_timezone_set('America/Bogota');
$option = 0;
require 'conexion.php';
if(isset($_GET[ 'option' ])){
	$option = $_GET[ 'option' ];
}
if($option=='excelUsers'){
	$fecha = new DateTime();
	header('Content-Encoding: UTF-8');
	header( 'Content-Type: application/xls; charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename=ListadoUsuarios_'.date("Y-m-d H:i:s").'.xls');
	echo "\xEF\xBB\xBF";
	$usuarios = $connect->query( "SELECT u.id, u.nombres, u.apellidos, u.correo, u.codigo, up.perfil, c.cargo, s.sede, u.profesion FROM usuarios u JOIN usuario_perfil up ON u.estado = 1
	AND u.id = up.id_usuario AND up.perfil<>'SuperAdmin'  JOIN cargos c ON u.id_cargo = c.id JOIN sedes s ON u.id_sede = s.id ORDER BY nombres ASC" );
	$outPut = '
	<table class="table" border="1">
		<tr>
			<th>ID</th>
			<th>NOMBRES</th>
			<th>APELLIDOS</th>
			<th>CORREO</th>
			<th>CÓDIGO</th>
			<th>PERFIL</th>
			<th>CURSOS</th>
			<th>CARGO</th>
			<th>SEDE</th>
			<th>ROFESIÓN</th>
			<th>INSTRUCTOR</th>
		</tr>
	';	
	while($usuario = mysqli_fetch_array($usuarios)){
		$inscripciones = mysqli_fetch_array($connect->query( 'SELECT COUNT(1) total FROM inscripciones WHERE id_usuario ='.$usuario["id"]));
		$instructor = mysqli_fetch_array($connect->query( 'SELECT COUNT(1) total FROM cursos WHERE id_instructor ='.$usuario["id"]));
		$is_instructor = 'NO';
		if( isset($instructor['total']) && $instructor['total'] > 0){
			$is_instructor = 'SI';
		}
		$outPut.='
			<tr> 
				<td>'.$usuario["id"].'</td>
				<td>'.$usuario["nombres"].'</td>  
				<td>'.$usuario["apellidos"].'</td>  
				<td>'.$usuario["correo"].'</td>
				<td>'.$usuario["codigo"].'</td>
				<td>'.$usuario["perfil"].'</td>  
				<td>'.$inscripciones["total"].'</td>									
				<td>'.$usuario["cargo"].'</td>
				<td>'.$usuario["sede"].'</td>
				<td>'.$usuario["profesion"].'</td>
				<td>'.$is_instructor.'</td>
		   </tr>  
		';
	}
	$outPut.='</table>';	
	echo $outPut;
}
if($option=='excelCourses'){
	$fecha = new DateTime();
	header('Content-Encoding: UTF-8');
	header( 'Content-Type: application/xls; charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename=ListadoCursos_'.date("Y-m-d H:i:s").'.xls');
	echo "\xEF\xBB\xBF";
	$cursos = $connect->query( "SELECT * from cursos WHERE (fecha_finalizacion > NOW() OR fecha_finalizacion IS NULL) ORDER BY nombre ASC" );
	$outPut = '
	<table class="table" border="1">
		<tr>
			<th>ID</th>
			<th>NOMBRE</th>
			<th>FECHA INICIO</th>
			<th>FECHA FIN</th>
			<th>INSCRITOS</th>
			<th>NO CONFIRMADOS</th>
			<th>GRADUADOS</th>			
			<th>UNIDADES</th>
			<th>LECCIONES</th>
			<th>SLIDES</th>
			<th>EVALUACIONES</th>
			<th>PREGUNTAS</th>
			
		</tr>
	';	
	while($curso = mysqli_fetch_array($cursos)){
		$inscritos = mysqli_fetch_array($connect->query('SELECT COUNT(1) total FROM inscripciones WHERE id_curso='.$curso["id"]));
		$no_confirmados = mysqli_fetch_array($connect->query('SELECT count(distinct id_usuario) total  FROM invitaciones where id_curso = '.$curso["id"].' AND id_usuario NOT IN(SELECT distinct id_usuario from inscripciones WHERE id_curso = '.$curso["id"].')'));
		$graduados = mysqli_fetch_array($connect->query('SELECT COUNT(1) total FROM aprobaciones WHERE id_curso='.$curso["id"]));
		$unidades = mysqli_fetch_array($connect->query('SELECT COUNT(1) total FROM unidades WHERE id_curso='.$curso["id"]));
		$lecciones = mysqli_fetch_array($connect->query('SELECT COUNT(1) total FROM unidades u JOIN lecciones l ON u.id = l.id_unidad AND u.id_curso='.$curso["id"]));
		$slides = mysqli_fetch_array($connect->query("SELECT COUNT(1) total FROM unidades u JOIN lecciones l ON u.id = l.id_unidad AND u.id_curso=".$curso["id"]." JOIN slides s ON l.id = s.id_leccion"));
		$evaluaciones = 1;
		$preguntas = mysqli_fetch_array($connect->query("SELECT COUNT(1) total FROM unidades u JOIN lecciones l ON u.id = l.id_unidad AND u.id_curso=".$curso["id"]." JOIN preguntas p ON l.id=p.id_leccion"));
		$outPut.='
			<tr> 
				<td>'.$curso["id"].'</td>
				<td>'.$curso["nombre"].'</td>  
				<td>'.$curso["fecha_iniciacion"].'</td>  
				<td>'.$curso["fecha_finalizacion"].'</td>
				<td>'.$inscritos["total"].'</td>
				<td>'.$no_confirmados["total"].'</td>  
				<td>'.$graduados["total"].'</td>									
				<td>'.$unidades["total"].'</td>
				<td>'.$lecciones["total"].'</td>
				<td>'.$slides["total"].'</td>
				<td>'.$evaluaciones.'</td>
				<td>'.$preguntas["total"].'</td>
		   </tr>  
		';
	}
	$outPut.='</table>';	
	echo $outPut;
}
if($option=='excelUsersCourse'){
	$id_course = $_GET[ 'id_course' ];
	$fecha = new DateTime();
	header('Content-Encoding: UTF-8');
	header( 'Content-Type: application/xls; charset=UTF-8' );	
	//consultar usuarios matriculados en el curso:
	$usuariosInscritos = $connect->query( "SELECT u.* FROM usuarios u JOIN inscripciones i ON u.id = i.id_usuario AND i.id_curso = ".$id_course." JOIN cursos c On i.id_curso = c.id AND (c.fecha_finalizacion > NOW() OR c.fecha_finalizacion IS NULL)" );
	$curso = mysqli_fetch_array( $connect->query( "SELECT * FROM cursos where id=".$id_course));
	
	$outPut = '
	<table class="table" border="1">
		<tr>
			<th>ID</th>
			<th>NOMBRES</th>
			<th>APELLIDOS</th>
			<th>CORREO</th>
			<th>CÓDIGO</th>
			<th>AVANCE(%)</th>
			<th>FECHA INSCRIPCIÓN</th>			
			<th>ÚLTIMO INGRESO</th>
			<th>CERTIFICADO</th>			
		</tr>
	';
	header( 'Content-Disposition: attachment; filename='.$curso['nombre'].'_usuarios_'.date("Y-m-d H:i:s").'.xls');
	echo "\xEF\xBB\xBF";
	while($usuario = mysqli_fetch_array($usuariosInscritos)){
		
		/**Calcular avance de curso:**/
		$avanceCurso = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM avances a JOIN slides s ON a.id_slide = s.id AND a.id_usuario = " . $usuario['id'] . " JOIN lecciones l ON s.id_leccion = l.id  JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id =" . $id_course ) );
		/**Calcular slides de curso:**/
		$slides = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total FROM cursos c JOIN unidades u ON c.id = u.id_curso AND c.id = ".$id_course." JOIN lecciones l ON u.id = l.id_unidad JOIN slides s ON l.id = s.id_leccion" ) );
		/**Consultar ultimo ingreso:**/
		$lastIn = mysqli_fetch_array( $connect->query( "SELECT MAX(fecha) lastIn FROM ingresos where id_elemento  =".$id_course." AND id_usuario=".$usuario['id']." AND tipo='CURSO'" ) );
		$date_li = NULL;
		if( isset($lastIn['lastIn']) && $lastIn['lastIn']!=NULL ){
			$date_li = new DateTime($lastIn['lastIn']);
			$date_li = $date_li->format('d/m/Y');
		}
		/**Calcular porcentaje:**/
		$porcentage = 0;
		if ( $slides[ 'total' ] != 0 ) {
			$porcentage = round( ( $avanceCurso[ 'total' ] / $slides[ 'total' ] ) * 100 );
		}
		
		/**Consultar fecha de inscripcion**/
		$fechaInscripcion = mysqli_fetch_array( $connect->query( "SELECT fecha FROM inscripciones where id_usuario=".$usuario['id']." AND id_curso=".$id_course ) );
		$date_i = NULL;
		if( isset($fechaInscripcion['fecha']) && $fechaInscripcion['fecha']!=NULL ){
			$date_i = new DateTime($fechaInscripcion['fecha']);
			$date_i = $date_i->format('d/m/Y');
		}
		
		/**Consultar fecha de aprobacion**/
		$fechaAprobacion = mysqli_fetch_array( $connect->query( "SELECT fecha FROM aprobacion_cursos where id_usuario=".$usuario['id']." AND id_curso=".$id_course ) );
		$date_a = NULL;
		if( isset($fechaAprobacion['fecha']) && $fechaAprobacion['fecha']!=NULL ){
			$date_a = new DateTime($fechaAprobacion['fecha']);
			$date_a = $date_a->format('d/m/Y');
		}		
		$outPut.='
			<tr> 
				<td>'.$usuario["id"].'</td>
				<td>'.$usuario["nombres"].'</td>  
				<td>'.$usuario["apellidos"].'</td>  
				<td>'.$usuario["correo"].'</td>
				<td>'.$usuario["codigo"].'</td>
				<td>'.$porcentage.'</td>
				<td>'.$date_i.'</td>
				<td>'.$date_li.'</td>
				<td>'.$date_a.'</td>
		   </tr>  
		';
	}
	$outPut.='</table>';	
	echo $outPut;
}
?>