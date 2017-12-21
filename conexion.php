<?php

$connect = new mysqli( 'localhost', 'sieteint_ceel', 'sieteint_ceel', 'sieteint_ceel' );
if ( $connect->connect_errno ) {
	echo "Fallo al conectar a MySQL: (" . $connect->connect_errno . ") " . $connect->connect_error;
}
$connect->query( "SET NAMES 'utf8'" );
?>