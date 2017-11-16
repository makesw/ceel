<?php 

session_start();

if ( !isset( $_SESSION[ 'dataSession' ] ) ) {

	header( 'Location: ../index.php' );

}

require '../conexion.php';

?>

<?php

$linkLesson=0;

$idUnidadLoss=0;

$positionLossUnity=0;

$positionLinkLesson=0;

$positionLastSlideView=0;

$lastslide = 0;

if( isset($_GET[ 'linkLesson' ]) ){

	$linkLesson = $_GET[ 'linkLesson' ];

}

if( isset($_GET[ 'idUnidadLoss' ]) ){

	$idUnidadLoss = $_GET[ 'idUnidadLoss' ];

}

$id_course=$_GET[ 'id_course' ];

//consultar curso:

$curso = mysqli_fetch_array( $connect->query( "SELECT * FROM cursos c WHERE c.id =".$id_course ));

//Verificar si hay ultimo avance

$lastSlideView = mysqli_fetch_array( $connect->query( "SELECT a.id_slide lastSlide FROM avances a WHERE a.id = (SELECT MAX(id) FROM avances WHERE id_usuario = ".$_SESSION[ 'dataSession' ]['id']." AND id_curso = ".$id_course.")" ) );

//make array slides

$unidades = $connect->query("SELECT id,url_archivo,numero, nombre nombreU, '' nombreL, requerida, requiere_evaluar, id idUnidad, '' idSlide, '' idLeccion FROM unidades WHERE unidades.id_curso =".$id_course);

$arraySlides = array();

while ( $row = mysqli_fetch_array( $unidades ) ){

	//asiga intro de unidad

	$arraySlides[] = $row;

	if($idUnidadLoss==$row['id'] && $positionLossUnity == 0){

		$positionLossUnity = count($arraySlides);		

	}

	//consultar slides de unidad:

	$slides = $connect->query("SELECT DISTINCT s.id, s.url_slide url_archivo, s.numero,u.nombre nombreU,l.nombre nombreL, s.id_leccion, u.requerida, u.requiere_evaluar, u.id idUnidad,s.id idSlide, l.id idLeccion FROM unidades u JOIN lecciones l ON u.id = l.id_unidad AND u.id = ".$row['id']." JOIN slides s ON l.id = s.id_leccion");		

	while ( $row2 = mysqli_fetch_array( $slides ) ){

		$arraySlides[] = $row2;	

		if($lastSlideView['lastSlide'] == $row2['id'] && $positionLastSlideView == 0){

			$positionLastSlideView =  count($arraySlides); //set last slide

		}

		if($linkLesson==$row2['idLeccion'] && $positionLinkLesson == 0){

			$positionLinkLesson = count($arraySlides);//set link lesson			

		}

	}

	if( $row['requiere_evaluar'] ){//si la unidad requerie ser evaluada

		$aprobacion = mysqli_fetch_array( $connect->query( "SELECT COUNT(1) aprobado FROM aprobacion_unidades a WHERE id_usuario = ". $_SESSION[ 'dataSession' ]['id']." AND id_curso =".$id_course." AND id_unidad=".$row['id'] ) );

		if($aprobacion['aprobado']<1){//anexamos examen si no se ha presentado o aprobado

			//$rowEexamen = array( 'examen' =>true, 'idUnidad' =>$row['idUnidad'] );

			$rowEexamen = array("examen", $row['idUnidad']);

			array_push ($arraySlides,$rowEexamen);

		}		

	}	

}

//set the last Slide:

if($positionLastSlideView>0){

	$lastslide = $positionLastSlideView;

}

if($positionLinkLesson>0){

	$lastslide = $positionLinkLesson-1;

}

if($positionLossUnity>0){

	$lastslide = $positionLossUnity-1;

}

$totalSlides = count($arraySlides);

//Eliminar ultimo ingreso a curso:

$connect->query( "DELETE FROM ingresos WHERE id_usuario=".$_SESSION[ 'dataSession' ]['id']." AND id_elemento=".$id_course. " AND tipo='CURSO'");

//Insertar nuevo ingreos al curso:

$connect->query( "INSERT INTO ingresos (id_usuario,id_elemento,fecha,tipo) VALUES (".$_SESSION[ 'dataSession' ]['id'].",".$id_course.", NOW(), 'CURSO')");

?>

<!DOCTYPE html>

<html>

<?php include( "../includes/head.php" ); ?>

<body class="bg-2">

	<header class="h-back2">

		<div class="container-fluid">

			<div class="row">

				<div class="col-xs-12 col-sm-10 col-md-9 logo"><a href="#"><img src="../assets/img/logo-p.png"></a><span>|</span><?php echo $curso['nombre'];?>

				<span id="spanNameU"></span>

				</div>



				<div class="col-sx-12 col-sm-2 col-md-3 icon-h3">

					<a href="javascript:back_course();">

						<p><i class="fa fa-sign-out" aria-hidden="true"></i>SALIR</p>

					</a>

				</div>

			</div>

		</div>

	</header>

	<div class="container-fluid">

		<!-- Inicio textos curso -->

		<div class="row">

			<iframe id="ifcontent" src="">			

			</iframe>

		

		</div>

		<!-- Fin textos curso -->

		<!-- Inicio Navegación curso -->

		<div class="row nav-course">

			<div class="col-xs-12 col-sm-3 col-md-3 col-nav">

				<a href="javascript:fn_load_slide(-1);" id="ant">

					<div class="before-nav">

						<i class="fa fa-chevron-left" aria-hidden="true"></i> anterior

					</div>

				</a>

			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 col-nav" id="divPag">

				<div class="pagination-user">

					<?php if($totalSlides>0){ ?>

						| Slide <span id="divIter">1</span> de <?php echo($totalSlides); ?> |

					<?php } ?><span id="spanNameL" class="tLec"></span>	

				</div>

			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 col-nav" id="divExa" hidden="true">

				<div class="pagination-user">

					TEST DE UNIDAD

				</div>

			</div>

			<div class="col-xs-12 col-sm-3 col-md-3 col-nav">

				<a href="javascript:fn_load_slide(1);" id="sig">

					<div class="after-nav">

						siguiente <i class="fa fa-chevron-right" aria-hidden="true"></i>

					</div>

				</a>

			</div>

		</div>

		<!-- Inicio Navegación curso -->

	</div>

	<!-- Include Footer-->

	<?php include("../includes/footer.php"); ?>

</body>

<?php $connect->close(); ?>

</html>

<script>

	var iterador = 0;

	var totalSl = <?php echo $totalSlides; ?>;

	var lastslide = <?php echo $lastslide; ?>;

	var id_course = <?php echo($id_course);?>;

	var idUnidad = 0;

	var final = false;

	//Si no hay slides ocultar botones de navegacion

	if(totalSl == 0){

		$('#ant').hide();

		$('#sig').hide();

	}	

	if(lastslide==0 ){ //Si no hay ultima slide se cargue la primera

		fn_load_slide(0);		

	}else{

		fn_load_slide(lastslide);		

	}

	function fn_load_slide( position ) {

		final = false;

		iterador = iterador+position;

		if( (iterador-1) < 0){

			$('#ant').hide();

		}else if( (iterador+1) > totalSl){

			final = true;

			$('#sig').hide();

			$('#divPag').hide();

			//Cargar pàgina final de curso

			$('#ifcontent').attr('src','/cursos/course-final.php?idCurso='+id_course);

		}else{

			$('#ant').show();

			$('#sig').show();

			$('#divPag').show();

		}		

		var arrayFromPHP = <?php echo json_encode($arraySlides); ?>;

		if(!final){			

			if(arrayFromPHP[iterador][0]>0){

				fn_check_slide( arrayFromPHP[iterador][0], '<?php echo $_SESSION['dataSession']['id'];?>', id_course );

			}			

			if(arrayFromPHP!= null && arrayFromPHP[iterador][0] =='examen'){

				fn_load_test(arrayFromPHP[iterador][1], id_course);

			}else{

				$('#ifcontent').attr('src',arrayFromPHP[iterador][1]);

				$('#spanNameU').text('/ '+arrayFromPHP[iterador][3]);

				$('#spanNameL').text(arrayFromPHP[iterador][4]);

			}

			$('#divIter').text(iterador+1);

			$('#divExa').hide();

			$('#divPag').show();		

		}

	}

	function fn_check_slide( id_slide, id_usuario, id_course ) {

		$.ajax({

			url: 'actions.php?action=checkViewSlide&id_usuario='+id_usuario+'&id_slide='+id_slide+'&id_course='+id_course,

			type: 'POST',

			data: $(this).serialize(),

			success: function (data) {		

				//console.log(data);

			},

			error: function (data) {

				//console.log(data);    

			},

			cache: false,

			contentType: false,

			//processData: false

		});		

		//return false;

	}

	function fn_load_test( idUnidad,id_course ) {

		$.ajax({

			url: 'actions.php?action=loadTest&idUnidad='+idUnidad+'&idCurso='+id_course,

			type: 'POST',

			data: $(this).serialize(),

			success: function (data) {		

				//console.log(data);

				$('#ifcontent').attr('src',data.urlContent);

				$('#divExa').show();

				$('#divPag').hide();

				//$('#spanNameU').text('/ Test de Unidad');

				$('#sig').hide();

				$('#ant').hide();

			},

			error: function (data) {

				//console.log(data);    

			},

			cache: false,

			contentType: false,

			//processData: false

		});		

		//return false;

	}	

	function back_course( ) {

		//window.history.back();

		location.href="/Usuario/course-view.php?idCourse="+id_course;

	}

</script>
