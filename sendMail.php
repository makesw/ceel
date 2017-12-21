<?php
//header( 'Content-Type: application/json' );
$action = '';
if( isset($_GET['action']) ){
	$action = $_GET['action'];
}
if( $action!= null && $action=='recordar' ){
	require 'conexion.php';	
	require 'constants.php';	
	$usuario = mysqli_fetch_array( $connect->query("SELECT u.correo, u.password, u.nombres, u.apellidos FROM usuarios u WHERE u.correo = '".$_POST['correo']."'") );
	if($usuario != NULL && isset($usuario['correo'])){
		require 'constants.php';
		$subject = 'Recordar Contraseña - CEEL';
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: ".$correoCeel." \r\n";
		$message = '
			<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<title>RECORDAR CONTRASEÑA CEEL</title>
</head>

<body style="background-color: #12122e; align: center; margin: 0px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">
<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e">
  <tbody>   
    <tr>
        <td style="padding:20px 0px"><table width="640" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="200" align="right"><a href=""><img src="http://ceel.sieteinteractivo.co/assets/img/logo-p.png" alt=""></a></td>
              <td align="left"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-titulo.png" alt=""></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td height="90" style="background-image:url(http://ceel.sieteinteractivo.co/imagenes/headerMail.png)"><table width="640" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td width="60"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-ico-user.png" alt=""></td>
      <td width="400" align="left" style="color: #FFFFFF; font-size: 24px;">Hola, @'.$usuario['nombres'].' '.$usuario['apellidos'].'</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr style="background-color:#FFFFFF">
      <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">RECORDAR CONTRASEÑA USUARIOS</h3>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><p>Estimado usuario, su contraseña de ingreso a CEEL es: <strong style="font-weight:900; font-size:1.2em">'. $usuario['password'] .'</strong></p>
				<p><strong style="font-weight:900; font-size:1.2em">Cordialmente</strong><br>	
				CEEL (Centro de Capacitación en Línea)</p></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>   
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr>
      <td align="center" style="background-color: #D0D0D0; padding:10px"><p style="font-size:12px; font-weight:200">CEEL Dev siete interactivo 2017 <br>
				<strong><a href="'.$RAIZ_CEEL.'" style="color:#0275d8; text-decoration:none; font-size:14px">¿Tienes Preguntas? Contacta al administrador</a></strong></p></td>
    </tr>
  </tbody>
</table>
</body>
</html>
';
			
	if(mail('edwin.chia86@gmail.com',$subject,$message,$headers)){
		echo json_encode(array('error'=>false,'description'=>'OK'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'FAIL'));
	}	
	}else{			
		echo json_encode(array('error'=>true,'recordar'=>'FAIL','noData'=>'NO_DATA'));
	}
$connect->close();
}
if( $action!= null && $action=='contactar' ){
	require 'constants.php';
	$subject = $_POST['asunto'];
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: " . $_POST['correo'] ."\r\n";
	$message = '
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<title>NUEVO MENSAJE DE USUARIO CEEL</title>
</head>

<body style="background-color: #12122e; align: center; margin: 0px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">
<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e">
  <tbody>
    <tr>
        <td style="padding:20px 0px"><table width="640" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="200" align="right"><a href=""><img src="http://ceel.sieteinteractivo.co/assets/img/logo-p.png" alt=""></a></td>
              <td align="left"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-titulo.png" alt=""></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td height="90" style="background-image:url(http://ceel.sieteinteractivo.co/imagenes/headerMail.png)"><table width="640" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td width="60"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-ico-user.png" alt=""></td>
      <td width="400" align="left" style="color: #FFFFFF; font-size: 24px;">Hola, @Admin</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr style="background-color:#FFFFFF">
      <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">TIENES UN NUEVO MENSAJE</h3>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><p>'. $_POST['comentario'] .'</p>
				<p><strong style="font-weight:900; font-size:1.2em">Cordialmente</strong><br>	
				CEEL (Centro de Capacitación en Línea)</p></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>   
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr>
      <td align="center" style="background-color: #D0D0D0; padding:10px"><p style="font-size:12px; font-weight:200">CEEL Dev siete interactivo 2017 <br>
				<strong><a href="'.$RAIZ_CEEL.'" style="color:#0275d8; text-decoration:none; font-size:14px">¿Tienes Preguntas? Contacta al administrador</a></strong></p></td>
    </tr>
  </tbody>
</table>
</body>
</html>
';
		
	if(mail($correoCeel,$subject,$message,$headers)){
		echo json_encode(array('error'=>false,'description'=>'OK'));
	}else{
		echo json_encode(array('error'=>true,'description'=>'FAIL'));
	}
$connect->close();	
}

function enviarCorreoInvitacion($courseId, $arrayUsuarios)
{	
require 'conexion.php';
require 'constants.php';
$subject = "INVITACIÓN A CURSO";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: ".$correoCeel. "\r\n";

$course = mysqli_fetch_array( $connect->query("SELECT id, nombre FROM cursos WHERE id=".$courseId ));	

foreach (array_values( $arrayUsuarios ) as $valor) {
	//get user:
	$user = mysqli_fetch_array( $connect->query("SELECT id, nombres, apellidos, correo FROM usuarios WHERE id=".$valor ));			

	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html;">
	<title>MENSAJE DE INFORMACIÓN IMPORTANTE CEEL</title>
	</head>
	<body style="background-color: #12122e; align: center; margin: 0px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">
	<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e">
	  <tbody>
		<tr>
			<td style="padding:20px 0px"><table width="640" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
				<tr>
				  <td width="200" align="right"><a href=""><img src="'.$RAIZ_CEEL.'assets/img/logo-p.png" alt=""></a></td>
				  <td align="left"><img src="'.$RAIZ_CEEL.'imagenes/mailing-titulo.png" alt=""></td>
				</tr>
			  </tbody>
			</table>
		  </td>
		</tr>
		<tr>
		  <td height="90" style="background-image:url('.$RAIZ_CEEL.'imagenes/headerMail.png)"><table width="640" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr>
		  <td>&nbsp;</td>
		  <td width="60"><img src="src="'.$RAIZ_CEEL.'imagenes/mailing-ico-user.png" alt=""></td>
		  <td width="400" align="left" style="color: #FFFFFF; font-size: 24px;">Hola, @'.$user['nombres'].' '.$user['apellidos'].'</td>
		</tr>
	  </tbody>
	</table>
	</td>
		</tr>
		<tr style="background-color:#FFFFFF">
		  <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>
			<h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">INVITACIÓN DE USUARIOS</h3>
			<h5 style="color: #0a0a2b; font-weight: 600; font-size: 1.5em; margin:-5px 0px 0px">Por favor confirma tu inscripción</h5>
		  </td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td><p>Este mail es una invitación a realizar el curso de <strong style="color:#5a5ab4">'.$course['nombre'].'</strong>, haz sido agregado por parte del administrador para que completes el curso y descargues tu certificado.</p>
					<p>Para más información sobre el curso, puedes escribirnos al siguiente correo: <a href="mailto:info@ceel.com" style="color:#5a5ab4">info@ceel.com</a></p>
					<p><strong style="font-weight:900; font-size:1.2em">Gracias</strong><br>	
					CEEL (Centro de Capacitación en Línea)</p></td>
		</tr>
		<tr>
		  <td align="center">&nbsp;</td>
		</tr>
		<tr>
		  <td align="center"><a href="'.$RAIZ_CEEL.'confirmInscription.php?course='.$course['id'].'&user='.$user['id'].'"><button type="button" style="background: #21e9de; color: #0a0a2b; border: none; cursor: pointer; font-size: 24px; font-weight: 600; display: inline-block; width: auto; padding-top: 10px; padding-left: 25px; padding-right: 25px; padding-bottom: 10px; border-radius: 5px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">ACEPTAR INVITACIÓN</button></a></td>
		</tr>
		<tr>
		  <td align="center"><p style="color:#A3A3A3; font-size:14px; margin-top:2px">Haga click en el boton o copie este link en su navegador:<br>
	'.$RAIZ_CEEL.'confirmInscription.php?course='.$course['id'].'&user='.$user['id'].'</p></td>
		</tr>
		<tr>
		  <td align="center">&nbsp;</td>
		</tr>
	  </tbody>
	</table>
	</td>
		</tr>
		<tr>
		  <td align="center" style="background-color: #D0D0D0; padding:10px"><p style="font-size:12px; font-weight:200">CEEL Dev siete interactivo 2017 <br>
					<strong><a href="'.$RAIZ_CEEL.'" style="color:#0275d8; text-decoration:none; font-size:14px">¿Tienes Preguntas? Contacta al administrador</a></strong></p></td>
		</tr>
	  </tbody>
	</table>
	</body>
	</html>';
		
	if(mail($user['correo'],$subject,$message,$headers)){
		//insert Invitation
		$query = "INSERT INTO invitaciones (id_usuario, id_curso, fecha) VALUES (". $user['id'] .",". $courseId .", NOW() )";
		$result = $connect->query( $query );	
	}
}
$connect->close();
}
function enviarCorreoRegistro($usuario)
{	
	require 'conexion.php';
	require 'constants.php';
	$subject = "REGISTRO DE USUARIO";
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: ".$correoCeel. "\r\n";
	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html;>
	<title>MENSAJE DE INFORMACIÓN IMPORTANTE CEEL</title>
	</head>
	<body style="background-color: #12122e; align: center; margin: 0px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">
	<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e">
	  <tbody>
		<tr>
			<td style="padding:20px 0px"><table width="640" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
				<tr>
				  <td width="200" align="right"><a href=""><img src="http://ceel.sieteinteractivo.co/assets/img/logo-p.png" alt=""></a></td>
				  <td align="left"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-titulo.png" alt=""></td>
				</tr>
			  </tbody>
			</table>
		  </td>
		</tr>
		<tr>
		  <td height="90" style="background-image:url(http://ceel.sieteinteractivo.co/imagenes/headerMail.png)"><table width="640" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr>
		  <td>&nbsp;</td>
		  <td width="60"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-ico-user.png" alt=""></td>
		  <td width="400" align="left" style="color: #FFFFFF; font-size: 24px;">Hola, @'.$usuario['nombres'].'</td>
		</tr>
	  </tbody>
	</table>
	</td>
		</tr>
		<tr style="background-color:#FFFFFF">
		  <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
	  <tbody>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>
			<h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">REGISTRO DE USUARIO</h3>
			<h5 style="color: #0a0a2b; font-weight: 600; font-size: 1.5em; margin:-5px 0px 0px">Bienvenido a nuestra herramienta</h5>
		  </td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td><p>Este mail es una confirmación de registro a nuestra herramienta <strong style="color:#5a5ab4">FEL BT</strong>, haz sido agregado por el administrador. Por correo recibirás invitaciones para unirte a los diferentes cursos disponibles para ti.</p>
		  <p> estos son tus datos de ingreso:</p>
		  <h3><strong style="color:#5a5ab4">Usuario(tu correo):</strong> '.$usuario['correo'].'<br></h3>
		  <h3><strong style="color:#5a5ab4">Contraseña(IDE)*:</strong> '.$usuario['password'].'</h3>
		  <h5 style="color:#5a5ab4; padding:15px; background-color:rgba(218,218,218,1.00)">* La contraseña es provisional, debes cambiarla ingresando a la herramienta en la sección de perfil</h5>

					<p>Para más información sobre el curso, puedes escribirnos al siguiente correo: <a href="mailto:info@ceel.com" style="color:#5a5ab4">info@ceel.com</a></p>
					<p><strong style="font-weight:900; font-size:1.2em">Gracias</strong><br>	
					FEL-BT (Formación Empresarial en Línea BT)</p></td>
		</tr>
		<tr>
		  <td align="center">&nbsp;</td>
		</tr>
		<tr>
		  <td align="center"><a href="'.$RAIZ_CEEL.'"><button type="button" style="background: #21e9de; color: #0a0a2b; border: none; cursor: pointer; font-size: 24px; font-weight: 600; display: inline-block; width: auto; padding-top: 10px; padding-left: 25px; padding-right: 25px; padding-bottom: 10px; border-radius: 5px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">Ir la herramienta</button></a></td>
		</tr>
		<tr>
		  <td align="center"><p style="color:#A3A3A3; font-size:14px; margin-top:2px">Haga click en el boton o copia este link en su navegador:<br>
	'.$RAIZ_CEEL.'</p></td>
		</tr>
		<tr>
		  <td align="center">&nbsp;</td>
		</tr>
	  </tbody>
	</table>
	</td>
		</tr>
		<tr>
		  <td align="center" style="background-color: #D0D0D0; padding:10px"><p style="font-size:12px; font-weight:200">CEEL Dev siete interactivo 2017 <br>
					<strong><a href="#" style="color:#0275d8; text-decoration:none; font-size:14px">¿Tienes Preguntas? Contacta al administrador</a></strong></p></td>
		</tr>
	  </tbody>
	</table>
	</body>
	</html>
	';
		
	mail($usuario['correo'],$subject,$message,$headers);
	$connect->close();
}

function enviarCorreoResultadoExamen($usuario, $curso, $unidad, $nota, $descr, $arrayLossLssons)
{	
	require 'conexion.php';
	require 'constants.php';
	$subject = "RESULTADO EVALUACIÓN";
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: ".$correoCeel. "\r\n";
	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MENSAJE DE INFORMACIÓN IMPORTANTE CEEL</title>
</head>

<body style="background-color: #12122e; align: center; margin: 0px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">
<table border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; align-content:center; background-color:#12122e">
  <tbody>
    <tr>
        <td style="padding:20px 0px"><table width="640" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td width="200" align="right"><a href=""><img src="http://ceel.sieteinteractivo.co/assets/img/logo-p.png" alt=""></a></td>
              <td align="left"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-titulo.png" alt=""></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td height="90" style="background-image:url(http://ceel.sieteinteractivo.co/imagenes/headerMail.png)"><table width="640" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td width="60"><img src="http://ceel.sieteinteractivo.co/imagenes/mailing-ico-user.png" alt=""></td>
      <td width="400" align="left" style="color: #FFFFFF; font-size: 24px;">Hola, @'.$usuario['nombres'].'</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr style="background-color:#FFFFFF">
      <td align="center"><table width="560" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div style="display:inline-block; background-color:#12122e; color:#e9a400; border-radius:100px; border:rgba(163,163,163,1.00) 6px solid; float:right"><h1 style="font-size:60px; line-height:35px; margin:30px 20px 10px;"><!--PORCENTAJE DE PREGUNTAS CONTESTADAS CORRECTAMENTE-->'.$nota.'% <label style="font-size:15px; display:block; text-align:center; color:#D9D9D9; margin:0; padding:0; font-weight:200">NOTA</label></h1></div>
        <h3 style="color: #5a5ab4; font-weight: 600; font-size: 1.5em; margin:0px">Resultado Evaluación de Curso</h3>
        <h5 style="color: #0a0a2b; font-weight: 600; font-size: 1.5em; margin:-5px 0px 0px"><!--nombre del curso-->'.$curso['nombre'].'</h5>
        <p style="margin:0;">'.date("d/m/Y").' </p>
        <p>Haz presentado la evaluación de la unidad <strong style="color: #5a5ab4; "><!--nombre dela unidad-->'.$unidad['nombre'].' </strong>y estos son los resultados:</p>
        <h2><!--RESULTADO-->'.$descr.'</h2>
      </td>
    </tr>
	
	<tr>
      <td>
          <h5 style="color: #5a5ab4; margin-bottom:5px">TEMAS A REPASAR:</h5>
          <ol style="list-style-type: none; margin-left: 0px; text-indent: -30px;">
	
	';
	//iterar lecciones:
	$iter = 1;
	foreach($arrayLossLssons as $valor){	
		$message.='<li style="padding:5px 0"><strong style="background-color:#5a5ab4; color:white; padding:1px 6px; border-radius:10px">'.$iter.'</strong>'.$valor.'</li>';	             
    	$iter++;            	  
	}
	
    $message.='
	</ol>
		</td>
    </tr>
	
	<tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><button type="button" style="background: #21e9de; color: #0a0a2b; border: none; cursor: pointer; font-size: 24px; font-weight: 600; display: inline-block; width: auto; padding-top: 10px; padding-left: 25px; padding-right: 25px; padding-bottom: 10px; border-radius: 5px; font-family: Segoe, Segoe UI, DejaVu Sans, Trebuchet MS, Verdana, sans-serif;">Ir la herramienta</button></td>
    </tr>
    <tr>
      <td align="center"><p style="color:#A3A3A3; font-size:14px; margin-top:2px">Haga click en el boton o copia este link en su navegador:<br>
http://ceel.sieteinteractivo.co/</p></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>
</td>
    </tr>
    <tr>
      <td align="center" style="background-color: #D0D0D0; padding:10px"><p style="font-size:12px; font-weight:200">CEEL Dev siete interactivo 2017 <br>
				<strong><a href="#" style="color:#0275d8; text-decoration:none; font-size:14px">¿Tienes Preguntas? Contacta al administrador</a></strong></p></td>
    </tr>
  </tbody>
</table>
</body>
</html>';
		
	mail($usuario['correo'],$subject,$message,$headers);
	$connect->close();
	
	return(true);
}
?>