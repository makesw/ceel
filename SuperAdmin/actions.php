<?php
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: /index.php' );
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
	 UPDATE usuarios 
	 SET correo = '" . $_POST[ "prof-mail" ] . "', 
	 codigo = '" . $_POST[ "prof-codigo" ] . "', 
	 id_cargo = '" . $_POST[ "prof-cargo" ] . "', 
	 id_sede = '" . $_POST[ "prof-sede" ] . "'";
	if( $targetPathAvt != NULL  ){
		$query .= ", url_foto = '" .$targetPathAvt. "'";
	}	
	$query .=" WHERE id =" . $_SESSION[ 'dataSession' ][ 'id' ];	

	$result = $connect->query( $query );

	if( $result == 1 ){
		//refresh data session:
		$cargo = mysqli_fetch_array( $connect->query( "SELECT cargo from cargos where id=". $_POST[ "prof-cargo" ] ) );	
		$sede = mysqli_fetch_array( $connect->query( "SELECT sede from sedes where id=". $_POST[ "prof-sede" ] ) );	
		$_SESSION[ 'dataSession' ][ 'correo' ]=$_POST[ "prof-mail" ];
		$_SESSION[ 'dataSession' ][ 'codigo' ]=$_POST[ "prof-codigo" ];
		$_SESSION[ 'dataSession' ][ 'cargo' ]=$cargo[ "cargo" ];
		$_SESSION[ 'dataSession' ][ 'sede' ]=$sede[ "sede" ];
		if( $targetPathAvt!=NULL ){
		$_SESSION[ 'dataSession' ][ 'url_foto' ]=$targetPathAvt;
		}
	}	
	echo json_encode( $_POST );
}
if ( $action == 'createUser' ) {
	//Validate mail user:	
	$mailUser = mysqli_fetch_array( $connect->query( "SELECT correo FROM usuarios WHERE correo='".$_POST[ "correo" ]. "'" ) );
	if( $mailUser != NULL && $mailUser['correo'] != NULL){
		echo json_encode(array('error'=>true,'description'=>'No se puede crear el usuario, el correo ya existe'));
	}else{	
		$query = "INSERT INTO usuarios
		( nombres,apellidos,codigo,id_cargo,id_sede,profesion,
			correo, password, fecha_creacion, usuario_creador, url_foto, estado, instructor
		)
		VALUES
		(   '" . $_POST[ "nombres" ] . "', '" . $_POST[ "apellidos" ] . "', '" . $_POST[ "codigo" ] . "',
		'" . $_POST[ "cargo" ] . "', '" . $_POST[ "sede" ] . "', '" . $_POST[ "profesion" ] . "',
			'" . $_POST[ "correo" ] . "', '" . $_POST[ "password" ] . "', NOW(),  '" . $_SESSION[ 'dataSession' ][ 'id' ] . "','" . '../assets/avatar/avatar-user-lg.jpg' . "','" . '1' . "','" . $_POST[ "instructor" ] . "'
		)";
		$result = $connect->query( $query );
		//create profile:
		$usuario = mysqli_fetch_array($connect->query( "SELECT * FROM usuarios WHERE correo = '" . $_POST[ "correo" ] . "'" ));
		$query = "INSERT INTO usuario_perfil (id_usuario,perfil)
		VALUES ( '" . $usuario[ "id" ] . "', '" . $_POST[ "perfil" ] . "')";
		$result = $connect->query( $query );
		
		if($result==1){//send mail
			require('../sendMail.php');
			enviarCorreoRegistro($usuario);
		}
		
		echo json_encode( $_POST );
	}
}
if ( $action == 'editUser' ) {

	$idUser = $_POST[ 'user_id' ];
	//Update user:
	$query = "UPDATE usuarios SET  
	nombres='" . $_POST[ "nombres" ] . "', 
	apellidos='" . $_POST[ "apellidos" ] . "', 
	codigo='" . $_POST[ "codigo" ] . "', 
	id_sede='" . $_POST[ "sede" ] . "', 
	id_cargo='" . $_POST[ "cargo" ] . "', 
	correo='" . $_POST[ "correo" ] . "', 
	profesion='" . $_POST[ "profesion" ] . "', 
	instructor='" . $_POST[ "instructor" ] . "'
	 WHERE id='" . $idUser . "'";
	$result = $connect->query( $query );
	if( $result == 1){
		//Update perfil:
		$query = "UPDATE usuario_perfil SET perfil='" . $_POST[ "perfil" ] . "' WHERE id_usuario='" . $idUser . "'";
		$result = $connect->query( $query );
		echo json_encode(array('error'=>false,'description'=>'Usuario actualizado con éxito','optButton'=>$_POST[ "optButton" ]));
	}else{
		echo json_encode(array('error'=>true,'description'=>'No se pudo actualziar el usuario','optButton'=>$_POST[ "optButton" ]));
	}
}

if ( $action == 'disableUsers' ) {
	
	//disable users:
	$array = json_decode($_POST['array']);
	$sql = "UPDATE usuarios SET estado = 0 WHERE id IN "."('" . implode( "','", $array ) . "')";	
	$result = $connect->query( $sql );
	echo json_encode(array('error'=>false,'description'=>'DeleteOk'));
}

if ( $action == 'deleteUsers' ) {
	$deleteAll = false;
	//delete users:
	$array = json_decode($_POST['array']);
	$delElements = deleteElementsByColum('usuarios','id',$array);
	if( count($array) == $delElements){
		$deleteAll = true;
	}	
	echo json_encode(array('deleteAll'=>$deleteAll));
}

if ( $action == 'deleteCourses' ) {
	$deleteAll = false;
	//delete courses:
	$array = json_decode($_POST['array']);
	$deleteElements = 0;
	foreach ($array as &$valor) {			
		$sentencia = "DELETE FROM cursos WHERE id=".$valor;
		$result = $connect->query($sentencia); 
		if($result){
			$deleteElements++;
			//delete content course:
			$dir = "../cursos/curso-".$valor;
			if(file_exists($dir)){
				delTree($dir);
			}
			//delete imgs course:
			$dir = "../imagenes/cursos/".$valor;
			if(file_exists($dir)){
				delTree($dir);
			}	
		}
	}
	if( count($array) == $deleteElements){
		$deleteAll = true;
	}	
	echo json_encode(array('deleteAll'=>$deleteAll));
}

if ( $action == 'createCourse' ) {
	
	$ffin = 'NULL';
	if( isset($_POST[ "ffin" ]) && !empty($_POST[ "ffin" ]) ){
		$ffin = "'" .$_POST[ "ffin" ]. "'";
	}
	//1. Create course info:
	$query = "INSERT INTO cursos
    ( nombre,descripcion,subtitulo,fecha_creacion,fecha_iniciacion,
        id_creador, fecha_finalizacion, id_instructor
    )
    VALUES
    (   '" . $_POST[ "nombre" ] . "', '" . $_POST[ "descripcion" ] . "', '" . $_POST[ "subtitulo" ] . "', NOW(),  '" . $_POST[ "finicio" ] . "',
        '" . $_SESSION[ 'dataSession' ][ 'id' ] . "'," .$ffin.", '" . $_POST[ "instructor" ] . "' )";
	$result = $connect->query( $query );
	
	//2. Get Course ID create:
	$course = mysqli_fetch_array( $connect->query( "SELECT id FROM cursos WHERE nombre='".$_POST[ "nombre" ]. "'" ) );
	
	//3. Upload files:
	mkdir("../imagenes/cursos/".$course['id']);
	mkdir("../imagenes/cursos/".$course['id']."/iconos/");
	$sourcePathIcon = $_FILES['iconoCurso']['tmp_name']; 
	$targetPathIcon = "../imagenes/cursos/".$course['id']."/iconos/curso".$course['id'].".png"; 
	move_uploaded_file($sourcePathIcon,$targetPathIcon) ; 
	
	$sourcePathImg = $_FILES['imgCurso']['tmp_name']; 
	$targetPathImg = "../imagenes/cursos/".$course['id']."/img-curso".$course['id']."-".date("YmdHms").".png";
	$targetPathImgDB = $targetPathImg;
	move_uploaded_file($sourcePathImg,$targetPathImg) ; 
	
	//unzip and load files:
	$sourcePathCont = $_FILES['archivo']['tmp_name']; 
	$targetPathCont = "../cursos/curso-".$course['id'].".zip"; 
	$dir = "../cursos/curso-".$course['id'];
	move_uploaded_file($sourcePathCont,$targetPathCont) ;
	
	//Extract content course (.zip):		
	$zip = new ZipArchive;
	if ($zip->open($targetPathCont) === TRUE) {
		$zip->extractTo($dir);
		$zip->close();
		unlink($targetPathCont);
	} 
	
	if($_FILES['iconoCurso']['name']==NULL){
		$targetPathIcon = NULL;
	}
	if($_FILES['imgCurso']['name']==NULL){
		$targetPathImgDB = "../assets/img/no-image-course.png";
	}
	
	//3. Update urls course:
	$query = "UPDATE cursos SET url_foto='".$targetPathImgDB."', url_icono='".$targetPathIcon."' WHERE id=".$course['id'];
		
	$result = $connect->query( $query );
		
	echo json_encode( $_POST );

}

if ( $action == 'editCourse' ) {
	$course_id = 0;
	$newTargetPathImg = NULL;
	if( isset($_POST[ 'course_id' ]) ){
		$course_id = $_POST[ 'course_id' ];
	}
	if( isset($_FILES['iconoCurso']['name']) && !empty($_FILES['iconoCurso']['name']) ){
		$sourcePathIcon = $_FILES['iconoCurso']['tmp_name']; 
		$targetPathIcon = "../imagenes/cursos/".$course_id."/iconos/curso".$course_id.".png"; 
		move_uploaded_file($sourcePathIcon,$targetPathIcon) ;
	}
	
	if( isset($_FILES['imgCurso']['name']) && !empty($_FILES['imgCurso']['name']) ){
		$sourcePathImg = $_FILES['imgCurso']['tmp_name']; 
		$newTargetPathImg = "../imagenes/cursos/".$course_id."/img-curso".$course_id."-".date("YmdHms").".png";
		move_uploaded_file($sourcePathImg,$newTargetPathImg) ;
		//changeImageCourse:
		$cousrseData = NULL;
		$newUrlFoto = NULL;		
		$cousrseData = mysqli_fetch_array( $connect->query( "SELECT nombre, url_foto FROM cursos WHERE id=".$course_id ) );				
		//delete old image on server:		
		unlink($cousrseData['url_foto']);
	}
	
	if( isset($_FILES['archivo']['name']) && !empty($_FILES['archivo']['name']) ){
		
		//unzip and load files:
		$sourcePathCont = $_FILES['archivo']['tmp_name']; 
		$targetPathCont = "../cursos/curso-".$course_id.".zip"; 
		$dir = "../cursos/curso-".$course_id;		
		//createDirCourse no exist
		if(!file_exists ( $dir )){
			mkdir($dir, 0755);
		}		
		delContentCourse($dir) ;
		move_uploaded_file($sourcePathCont,$targetPathCont) ;		
		$dir = "../cursos/curso-".$course_id;		
		//Extract content course (.zip):		
		$zip = new ZipArchive;
		if ($zip->open($targetPathCont) === TRUE) {
			$zip->extractTo($dir);
			$zip->close();
			unlink($targetPathCont);
		} 		
	}
	
	//Update course:
	$query = "UPDATE cursos SET  
	nombre='" . $_POST[ "nombre" ] . "', 
	subtitulo='" . $_POST[ "subtitulo" ] . "', 
	descripcion='" . $_POST[ "descripcion" ] . "', 
	fecha_iniciacion='" . $_POST[ "finicio" ] . "',  
	id_instructor='" . $_POST[ "instructor" ] . "'";
	if( !empty($_POST[ "ffin" ]) ){
		$query = $query. ",fecha_finalizacion='". $_POST[ "ffin" ] . "'";
	}else{
		$query = $query. ",fecha_finalizacion=NULL";
	}
	if($newTargetPathImg!=NULL){
		$query = $query. ",url_foto='". $newTargetPathImg . "'";
	}	
	if($_FILES['iconoCurso']['name']!=NULL){
		$query = $query. ",url_icono='". $targetPathIcon . "'";
	}	
	$query = $query.' WHERE id='. $course_id;	
	
	$result = $connect->query( $query );
		
	echo json_encode( $_POST );
}
if ( $action == 'createUnity' ) {
	$dir = "../cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numero" ];
	$dirNet = "/cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numero" ];
	//createDirUnity
	if(!file_exists ( $dir )){
		mkdir($dir, 0755);
	}
	
	//load file:
	$sourcePathFile = $_FILES['archivo']['tmp_name']; 
	$targetPathFile = $dir."/".$_FILES['archivo']['name'];
	$path_parts = pathinfo($targetPathFile);	
	$targetPathFile = $dir."/unidad-".$_POST[ "numero" ].'.'.$path_parts['extension'];	
	$urlFile = $targetPathFile;
	move_uploaded_file($sourcePathFile,$targetPathFile) ;	
		
	$requerida = 0;
	$evaluar = 0;
	if(isset($_POST[ "requerida" ])){$requerida=1;}
	if(isset($_POST[ "evaluar" ])){$evaluar=1;}
		
	$query = "INSERT INTO unidades
    ( numero, nombre,subtitulo,descripcion,url_archivo,requiere_evaluar,requerida,fecha_creacion,usuario_creador, id_curso
    )
    VALUES
    (   '" . $_POST[ "numero" ] . "','" . $_POST[ "nombre" ] . "', '" . $_POST[ "subtitulo" ] . "', '" . $_POST[ "descripcion" ] . "',
	'"  . $dirNet."/unidad-".$_POST[ "numero" ].'.'.$path_parts['extension']. "', '" . $evaluar . "',
        '" . $requerida . "', NOW(),  '" . $_SESSION[ 'dataSession' ][ 'id' ]. "', '" . $_POST[ "course_id" ]. "'
    )";
	
	$result = $connect->query( $query );
	echo json_encode( $_POST );

}
if ( $action == 'editUnity' ) {
	
	if($_FILES['archivo']['name']!=NULL){
		$dir = "../cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numero" ];
		$dirNet = "/cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numero" ];
		
		//createDirUnity not exist
		if(!file_exists ( $dir )){
			mkdir($dir, 0755);
		}
		
		//load new file:	
		$sourcePathFile = $_FILES['archivo']['tmp_name']; 
		$targetPathFile = $dir."/".$_FILES['archivo']['name'];
		$path_parts = pathinfo($targetPathFile);	
		$targetPathFile = $dir."/unidad-".$_POST[ "numero" ].'.'.$path_parts['extension'];	
		$urlFile = $targetPathFile;
		move_uploaded_file($sourcePathFile,$targetPathFile) ;
	}
		
	$unity_id = $_POST[ 'unity_id' ];
	$requerida = 0;
	$evaluar = 0;
	if(isset($_POST[ "requerida" ])){$requerida=1;}
	if(isset($_POST[ "evaluar" ])){$evaluar=1;}
	
	//Update unity:
	$query = "UPDATE unidades SET  
	nombre='" . $_POST[ "nombre" ] . "', 
	subtitulo='" . $_POST[ "subtitulo" ] . "', 
	descripcion='" . $_POST[ "descripcion" ] . "', 
	url_archivo='" . $dirNet."/unidad-".$_POST[ "numero" ].'.'.$path_parts['extension'] . "', 
	requiere_evaluar='" . $evaluar . "', 
	requerida='" . $requerida . "', 
	fecha_modificacion=NOW(), 
	usuario_modificador='" . $_SESSION[ 'dataSession' ][ 'id' ]. "' WHERE id='" . $unity_id . "'";
		
	$result = $connect->query( $query );	
	echo json_encode( $_POST );
}
if ( $action == 'createLesson' ) {
	
	$dir = "../cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numeroU" ]."/leccion-".$_POST[ "numeroL" ];
	
	$dirNet = "/cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numeroU" ]."/leccion-".$_POST[ "numeroL" ];
	
	//createDirLesson
	if(!file_exists ( $dir )){
		mkdir($dir, 0755);
	}
	
	//unzip and load files:
	$sourcePathCont = $_FILES['archivo']['tmp_name']; 
	$targetPathCont = $dir."/leccion-".$_POST[ "numeroL" ].".zip"; 
	move_uploaded_file($sourcePathCont,$targetPathCont) ;
	//Extract content lesson (.zip):		
	$zip = new ZipArchive;
	if ($zip->open($targetPathCont) === TRUE) {
		$zip->extractTo($dir);
		$zip->close();
		unlink($targetPathCont);
	} 
	
	//Create Lessson:
	$query = "INSERT INTO lecciones
    ( numero, nombre,subtitulo,descripcion,fecha_creacion,usuario_creador, id_unidad
    )
    VALUES
    (   '" . $_POST[ "numeroL" ] . "','" . $_POST[ "nombre" ] . "', '" . $_POST[ "subtitulo" ] . "', '" . $_POST[ "descripcion" ] ."', NOW(),  '" . $_SESSION[ 'dataSession' ][ 'id' ]. "', '" . $_POST[ "unity_id" ]. "'
    )";
	
	$result = $connect->query( $query );
	
	//get ID Lesson:
	$lesson = mysqli_fetch_array( $connect->query( "SELECT id FROM lecciones WHERE numero=".$_POST[ "numeroL" ]." AND nombre='".$_POST[ "nombre" ]."' AND id_unidad=".$_POST[ "unity_id" ] ) );
	
	//Configure Slides on DB:
	//$archivos  = scandir($dir,SCANDIR_SORT_NONE);
	$archivos = array_diff(scandir($dir), array('.','..'));
	$iter = 1;
	foreach ($archivos as &$valor) {
		$query = "INSERT INTO slides( url_slide, id_leccion, numero)
		VALUES
		(   '" . $dirNet."/".$valor . "','" . $lesson[ "id" ]. "','" . $iter . "' )";
		$result = $connect->query( $query );
		$iter ++;
	}
		
	echo json_encode( $_POST );

}
if ( $action == 'editLesson' ) {
try {		
	$lesson_id = $_POST[ 'lesson_id' ];	
	//Update info lesson:
	$query = "UPDATE lecciones SET  
	nombre='" . $_POST[ "nombre" ] . "', 
	subtitulo='" . $_POST[ "subtitulo" ] . "', 
	descripcion='" . $_POST[ "descripcion" ] . "',
	fecha_modificacion=NOW(), 
	usuario_modificador='". $_SESSION[ 'dataSession' ][ 'id' ]. "' WHERE id='" . $lesson_id . "'";	
	$result = $connect->query( $query );	
	if( isset($_FILES['archivo']['name']) && !empty($_FILES['archivo']['name']) ){
		//delete slides of lesson on db:
		$query = "DELETE FROM slides WHERE id_leccion=".$lesson_id;
		$result = $connect->query( $query );
		if( $result == 1 ){	
			$dir = "../cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numeroU" ]."/leccion-".$_POST[ "numeroL" ];
			$dirNet = "/cursos/curso-".$_POST[ "course_id" ]."/unidad-".$_POST[ "numeroU" ]."/leccion-".$_POST[ "numeroL" ];
			//createDirLesson not exist
			if(!file_exists ( $dir )){
				mkdir($dir, 0755);
			}
			//delete slides of lesson on server:
			array_map('unlink', glob($dir."/*"));
			//unzip and load files:
			$sourcePathCont = $_FILES['archivo']['tmp_name']; 
			$targetPathCont = $dir."/leccion-".$_POST[ "numeroL" ].".zip"; 
			move_uploaded_file($sourcePathCont,$targetPathCont) ;
			//Extract content course (.zip):		
			$zip = new ZipArchive;
			if ($zip->open($targetPathCont) === TRUE) {
				$zip->extractTo($dir);
				$zip->close();
				unlink($targetPathCont);
			} 
			//Configure Slides on DB:
			//$archivos  = scandir($dir,SCANDIR_SORT_NONE);
			$archivos = array_diff(scandir($dir), array('.','..'));
			$iter = 1;
			foreach ($archivos as &$valor) {
				$query = "INSERT INTO slides( url_slide, id_leccion, numero)
				VALUES
				(   '" . $dirNet ."/".$valor . "','" . $lesson_id. "','" . $iter . "' )";
				$result = $connect->query( $query );
				$iter ++;
			}
		}
	}	
	echo json_encode( $_POST );	
}catch(Exception $e) {
  echo json_encode(array('error'=>true,'description'=>'Edit False'));
}
	
}
if ( $action == 'createQuestion' ) {
	
	//validate max questions per lesson:
	$countQuestions = mysqli_fetch_array( $connect->query( "SELECT count(1) total FROM preguntas WHERE id_leccion=".$_POST[ "lesson_id"] ) );
	if( $countQuestions['total'] > 9 ){
		echo json_encode(array('error'=>true,'description'=>'No se puede crear más preguntas pra esta lección'));
	}else{
	
		//Create Question:
		$query = "INSERT INTO preguntas
		( numero, pregunta ,id_tipo_pregunta, enunciado, id_leccion )
		VALUES
		(   '" . $_POST[ "numero" ] ."','" . $_POST[ "pregunta" ] ."','" . $_POST[ "tipoPregunta" ] . "','" . $_POST[ "enunciado" ] . "', '" . $_POST[ "lesson_id"] . "' )";
		$result = $connect->query( $query );
		//get ID Question:
		$questionId = mysqli_fetch_array( $connect->query( "SELECT id FROM preguntas WHERE numero=".$_POST[ "numero" ]." AND id_leccion=".$_POST[ "lesson_id"] ) );
		//insert the responses type unique selection
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='1' ){
			$iter = 1;
			$var = 'respRadioText'.$iter;
			$esCorrecta = $_POST[ "respRadio" ];
			while( isset($_POST[$var]) && $_POST[$var] != NULL){
				$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . $iter ."','" . $_POST[ $var ] ."','" . '0' . "','" . $questionId['id'] . "' )";
				$result = $connect->query( $query );
				$iter++;
				$var = 'respRadioText'.$iter;
			}
			//set response OK:
			$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $esCorrecta . "' AND id_pregunta=". $questionId['id'];
			$result = $connect->query( $query );
		}

		//insert the responses type multiple selection
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='2' ){
			$iter = 1;
			$var = 'respCheckText'.$iter;
			while( isset($_POST[$var]) && $_POST[$var] != NULL){
				$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . $iter ."','" . $_POST[ $var ] ."','" . '0' . "','" . $questionId['id'] . "' )";
				$result = $connect->query( $query );
				$iter++;
				$var = 'respCheckText'.$iter;
			}
			//set responses OK:
			if( isset($_POST[ "checkbox"]) ){
			foreach (array_values( $_POST[ 'checkbox' ] ) as $valor) {
				$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $valor . "' AND id_pregunta=". $questionId['id'];
				$result = $connect->query( $query );
			}
			}		
		}
		//insert the responses type true or false
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='3' ){
			$esCorrecta = $_POST[ "respRadioTf" ];
			$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . '1' ."','" . 'FALSO' ."','" . '0' . "','" . $questionId['id'] . "' )";
			$result = $connect->query( $query );

			$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . '2' ."','" . 'VERDADERO' ."','" . '0' . "','" . $questionId['id'] . "' )";
			$result = $connect->query( $query );

			//set response OK:
			$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $esCorrecta . "' AND id_pregunta=". $questionId['id'];
			$result = $connect->query( $query );
		}

		$result = $connect->query( $query );
		echo json_encode( $_POST );
	}

}
if ( $action == 'editQuestion' ) {		
	//Actualizar informacion de pregunta:
	$query = "UPDATE preguntas SET  
	enunciado='" . $_POST[ "enunciado" ] . "', 
	pregunta='" . $_POST[ "pregunta" ] ."', 
	id_tipo_pregunta='" . $_POST[ "tipoPregunta" ] ."' WHERE id=".$_POST['question_id'];
	$result = $connect->query( $query );
	//eliminar respuestas de pregunta:
	$query = "DELETE FROM respuestas WHERE id_pregunta=".$_POST[ "question_id"];
	$result = $connect->query( $query );
	if( $result > 0 ){ //si se eliminan las respuestas se puede actualizar
		//insertar respuestas tipo seleccion unica
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='1' ){
			$iter = 1;
			$var = 'respRadioText'.$iter;
			$esCorrecta = $_POST[ "respRadio" ];
			while( isset($_POST[$var]) && $_POST[$var] != NULL){
				$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . $iter ."','" . $_POST[ $var ] ."','" . '0' . "','" . $_POST['question_id'] . "' )";
				$result = $connect->query( $query );
				$iter++;
				$var = 'respRadioText'.$iter;
			}
			//establecer respuestas correctas:
			$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $esCorrecta . "' AND id_pregunta=". $_POST['question_id'];
			$result = $connect->query( $query );
		}
		//insertar respuestas tipo selecciòn multiple
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='2' ){
			$iter = 1;
			$var = 'respCheckText'.$iter;
			while( isset($_POST[$var]) && $_POST[$var] != NULL){
				$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . $iter ."','" . $_POST[ $var ] ."','" . '0' . "','" . $_POST['question_id'] . "' )";
				$result = $connect->query( $query );
				$iter++;
				$var = 'respCheckText'.$iter;
			}
			//establecer respuestas correctas:
			if( isset($_POST[ "checkbox"]) ){
			foreach (array_values( $_POST[ 'checkbox' ] ) as $valor) {
				$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $valor . "' AND id_pregunta=". $_POST['question_id'];
				$result = $connect->query( $query );
			}
			}		
		}
		//insertar respuestas tipo verdadero / falso
		if( isset($_POST[ "tipoPregunta"]) && $_POST[ "tipoPregunta"]=='3' ){
			$esCorrecta = $_POST[ "respRadioTf" ];
			$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . '1' ."','" . 'FALSO' ."','" . '0' . "','" . $_POST['question_id'] . "' )";
			$result = $connect->query( $query );

			$query = "INSERT INTO respuestas
				( numero, descripcion, es_correcta,id_pregunta )
				VALUES
				( '" . '2' ."','" . 'VERDADERO' ."','" . '0' . "','" . $_POST['question_id'] . "' )";
			$result = $connect->query( $query );

			//establecer respuesta correcta:
			$query = "UPDATE respuestas SET es_correcta='1' WHERE numero='" . $esCorrecta . "' AND id_pregunta=". $_POST['question_id'];
			$result = $connect->query( $query );
		}
		echo json_encode(array('error'=>false,'description'=>'Pregunta Actualizada con éxito'));
	}else{ //no se puede actualizar la pregunta
		echo json_encode(array('error'=>true,'description'=>'No se puede actualizar las respuestas'));
	}			

}

if ( $action == 'inviteUsers' ) {
	require('../sendMail.php');
	$array = json_decode($_POST['array']);
	enviarCorreoInvitacion($_GET[ 'courseId' ],$array);	
    echo json_encode(array('error'=>false,'courseId'=>$_GET[ 'courseId' ]));
}
if ( $action == 'deleteQuestions' ) {

	$deleteAll = false;
	//delete lessons:
	$array = json_decode($_POST['array']);
	$deleteElements = 0;
	foreach ($array as &$valor) {		
		$sentencia = "DELETE FROM preguntas WHERE id=".$valor;
		$result = $connect->query($sentencia); 
		if($result){
			$deleteElements++;
		}
	}
	if( count($array) == $deleteElements){
		$deleteAll = true;
	}	
	echo json_encode(array('deleteAll'=>$deleteAll));
	
}
if ( $action == 'deleteLessons' ) {

	$deleteAll = false;
	//delete lessons:
	$array = json_decode($_POST['array']);
	$deleteElements = 0;
	foreach ($array as &$valor) {
		$data = mysqli_fetch_array($connect->query( "SELECT c.id id_curso, u.numero numeroU, l.numero numeroL FROM lecciones l JOIN unidades u ON l.id_unidad = u.id AND l.id = ".$valor." JOIN cursos c ON u.id_curso = c.id"));		
		$sentencia = "DELETE FROM lecciones WHERE id=".$valor;
		$result = $connect->query($sentencia); 
		if($result){
			$deleteElements++;
			//delete content lessons:
			$dir = "../cursos/curso-".$data[ "id_curso" ]."/unidad-".$data[ "numeroU" ]."/leccion-".$data['numeroL'];
			if(file_exists($dir)){
				delTree($dir);
			}
		}
	}
	if( count($array) == $deleteElements){
		$deleteAll = true;
	}	
	echo json_encode(array('deleteAll'=>$deleteAll));
	
}
if ( $action == 'deleteUnitys' ) {	
	$deleteAll = false;
	//delete unitys:
	$array = json_decode($_POST['array']);
	$deleteElements = 0;
	foreach ($array as &$valor) {
		$unidad = mysqli_fetch_array($connect->query( "SELECT u.id,u.numero,c.id id_curso FROM unidades u JOIN cursos c ON u.id_curso = c.id AND u.id=".$valor));		
		$sentencia = "DELETE FROM unidades WHERE id=".$valor;
		$result = $connect->query($sentencia); 
		if($result){
			$deleteElements++;
			//delete content unitys:
			$dir = "../cursos/curso-".$unidad[ "id_curso" ]."/unidad-".$unidad[ "numero" ];
			if(file_exists($dir)){
				delTree($dir);
			}
		}
	}
	if( count($array) == $deleteElements){
		$deleteAll = true;
	}	
	echo json_encode(array('deleteAll'=>$deleteAll));
}

function delContentCourse($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
		$url = $dir.'/'.$file;
		if( !strpos($url, 'unidad') ){
			delTree($url);
		}
    } 
}

function delTree($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
}

if ( $action == 'relodInvitationList' ) {
	$courseId = $_GET[ 'curso' ];
	echo json_encode(array('error'=>false,'cursoId'=>$courseId));
}

function deleteElementsByColum($tableName, $columName, $arrayIds){
	require '../conexion.php';
	$deleteElements = 0;
	foreach ($arrayIds as &$valor) {
		$sentencia = "DELETE FROM ".$tableName." WHERE ".$columName."=".$valor;
		$result = $connect->query($sentencia); 
		if($result){
			$deleteElements++;
		}
	}	
    return $deleteElements;	
}

$connect->close();
?>