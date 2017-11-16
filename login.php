<?php 
session_start();
require 'conexion.php';
$usuario = $connect->query("SELECT u.id id, u.nombres as nombres, u.apellidos as apellidos,  u.correo as correo, u.password as password, u.codigo codigo, u.telefono telefono, u.url_foto, s.sede, up.perfil perfil, c.cargo as cargo FROM usuarios u
JOIN usuario_perfil up ON u.id = up.id_usuario and u.estado=1 and u.correo = '".$_POST['correo']."' AND u.password = '".$_POST['password']."' JOIN cargos c ON u.id_cargo = c.id JOIN sedes s ON u.id_sede = s.id");

if($usuario->num_rows == 1){
	$datos  =$usuario->fetch_assoc();
	$_SESSION['dataSession'] = $datos;
	echo json_encode(array('error'=>false,'correo'=>$datos['correo'],'password'=>$datos['password']
					,'perfil'=>$datos['perfil']));	
}else{	
	echo json_encode(array('error'=>true));
}
$connect->close();

?>