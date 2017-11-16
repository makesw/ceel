<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$connect->query( "SET NAMES 'utf8'" );
header( 'Content-Type: application/json' );

$action = $_GET[ 'action' ];
 
if ( $action == 'editProfile' ) {
	$targetPathAvt = NULL;
	if( !empty ($_FILES['avatarFiele']['name']) ){
		//Upload avatar:
		$sourcePathAvt = $_FILES['avatarFiele']['tmp_name'];
		if( $_SESSION['dataSession']['url_foto'] != '../assets/avatar/avatar-user-lg.jpg'){
			unlink($_SESSION['dataSession'][ 'url_foto']);
		}
		$targetPathAvt = "../assets/avatar/".$_SESSION[ 'dataSession' ][ 'id' ].date("YmdHms").".png"; 
		move_uploaded_file($sourcePathAvt,$targetPathAvt) ; 
	}	
	
	$query = "
	 UPDATE usuarios SET correo = '" . $_POST[ "prof-mail" ] . "'"; 	
	$query .=" WHERE id =" . $_SESSION[ 'dataSession' ][ 'id' ];	
	$result = $connect->query( $query );

	if( $result == 1 ){
		//refresh data session:	
		$_SESSION[ 'dataSession' ][ 'correo' ]=$_POST[ "prof-mail" ];
		//$_SESSION[ 'dataSession' ][ 'codigo' ]=$_POST[ "prof-codigo" ];
		if( $targetPathAvt!=NULL ){
		$_SESSION[ 'dataSession' ][ 'url_foto' ]=$targetPathAvt;
		}
	}	
	echo json_encode( $_POST );
}
if( $action == 'loadSlide'){
	$position = $_GET[ 'position' ];
	$arraySlides = $_POST['array'];
	$totalSlides = 0;	
	$nombreUnidad ='';
	$nombreLeccion ='';
	$requiere_evaluar = '';
	$requerida = '';
	$idUnidad = '';
	$idSlide = '';
	$urlContent = isset($arraySlides[$position]['url_archivo'])?$arraySlides[$position]['url_archivo']:'';
	$idSlide = isset($arraySlides[$position]['id'])?$arraySlides[$position]['id']:'';
	$nombreUnidad = isset($arraySlides[$position]['nombreU'])?$arraySlides[$position]['nombreU']:'';
	$nombreLeccion = isset($arraySlides[$position]['nombreL'])?$arraySlides[$position]['nombreL']:'';
	$requiere_evaluar = isset($arraySlides[$position]['requiere_evaluar'])?$arraySlides[$position]['requiere_evaluar']:'';
	$requerida = isset($arraySlides[$position]['requerida'])?$arraySlides[$position]['requerida']:'';
	$idUnidad = isset($arraySlides[$position]['idUnidad'])?$arraySlides[$position]['idUnidad']:'';
	$idSlide = isset($arraySlides[$position]['idSlide'])?$arraySlides[$position]['idSlide']:'';
	$examen = isset($arraySlides[$position]['examen'])?$arraySlides[$position]['examen']:'';
	
	echo json_encode(array('idSlide'=>$idSlide,'examen'=>$examen,'idUnidad'=>$idUnidad,'requiere_evaluar'=>$requiere_evaluar,'requerida'=>$requerida,'urlContent'=>$urlContent,'nombreUnidad'=>$nombreUnidad,'nombreLeccion'=>$nombreLeccion));
	
}
if( $action == 'checkViewSlide'){
	$id_usuario=$_GET[ 'id_usuario' ];
	$id_slide=$_GET[ 'id_slide' ];
	$id_course=$_GET[ 'id_course' ];
	//validar registro de marca:
	$checkViewSlide = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) total from avances where id_usuario=".$id_usuario." AND id_slide =".$id_slide ) );
	if( $checkViewSlide['total'] == 0 ){
		//si no existe se marca como vista:
		$query = "INSERT INTO avances ( id_usuario, id_slide, id_curso, fecha )
		VALUES
		(   '" . $id_usuario . "', '" . $id_slide . "', '" . $id_course . "',  now() 
		)";
		$result = $connect->query( $query );
		echo json_encode(array('checkViewSlide'=>$result));
	}	
}
if( $action == 'saveTest'){
	$id_usuario=$_GET[ 'id_usuario' ];
	$id_curso=$_GET[ 'idCurso' ];
	$idUnidad=$_GET[ 'idUnidad' ];
	
	//eliminar evaluacion previa si la hay:
	$query = "DELETE FROM evaluaciones WHERE id_usuario=".$id_usuario." AND id_curso=".$id_curso." AND id_unidad=".$idUnidad;
	$result = $connect->query( $query ); 
	//consulatar preguntas de la unidad:
	$preguntas =  $connect->query( "SELECT p.* FROM preguntas p JOIN lecciones l ON p.id_leccion = l.id JOIN unidades u ON l.id_unidad = u.id JOIN cursos c ON u.id_curso = c.id AND c.id =".$id_curso);
	while($preg = mysqli_fetch_array($preguntas)){
		if( $preg['id_tipo_pregunta'] == 1 || $preg['id_tipo_pregunta'] == 3 ){
			//save responses selected:
			$nameResponse = 'respRadio'.$preg['id'];
			if(isset($_POST[ $nameResponse ])){
				$selectResponse = $_POST[ $nameResponse ];
				$query = "INSERT INTO evaluaciones
					( id_pregunta, id_respuesta, id_usuario,id_curso, id_unidad ) VALUES
					( '" . $preg['id'] ."','" . $selectResponse ."','" . $id_usuario . "','" . 	$id_curso . "','" . 	$idUnidad . "' )";
				$result = $connect->query( $query );
			}
		}else if($preg['id_tipo_pregunta'] == 2 ){
			$nameResponses = 'checkbox'.$preg['id'];
			if(isset($_POST[ $nameResponses])){
				if( isset($_POST[ $nameResponses]) ){
					foreach (array_values( $_POST[ $nameResponses ] ) as $valor) {
						$query = "INSERT INTO evaluaciones
						( id_pregunta, id_respuesta, id_usuario,id_curso,id_unidad ) VALUES
						( '" . $preg['id'] ."','" . $valor ."','" . $id_usuario . "','" . $id_curso . "','" . $idUnidad . "' )";
						$result = $connect->query( $query );
					}
				}
			}		
		}
	}	
	echo json_encode(array('error'=>false,'description'=>'Examen Guardado con Éxito'));
}
if( $action == 'loadTest'){
	$idCurso=$_GET[ 'idCurso' ];	
	$idUnidad=$_GET[ 'idUnidad' ];	
	$urlContent = '/cursos/course-test.php?idUnidad='.$idUnidad.'&idCurso='.$idCurso;
	echo json_encode(array('urlContent'=>$urlContent));
}
$connect->close();
?>