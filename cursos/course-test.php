<?php 
session_start();
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: ../index.php' );
}
require '../conexion.php';
$idUnidad = $_GET[ 'idUnidad' ];
$idCurso = $_GET[ 'idCurso' ];
//Conultar preguntas de unidad:
$preguntas = $connect->query( "SELECT DISTINCT p.* FROM unidades u JOIN lecciones l ON u.id = ".$idUnidad." AND u.id = l.id_unidad JOIN preguntas p ON p.id_leccion = l.id ORDER BY RAND() LIMIT 10");
header( "Content-Type: text/html;charset=utf-8");	
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="/css/styles-test.css">
<link rel="stylesheet" href="../css/evaluation.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<body class="content">
<?php 
	$arrayPreguntas = array();
?>	
<form id="form-test" method="post">
	<div class="row content-r">	
	<div class="container-fluid content-p">
		<div class="row">
			<div class="col-sm-12 col-md-12 text">
				<div class="header-test">
					<h5>Test de Unidad</h5>
					<h1>Ponte a prueba</h1>
				</div>
				<?php  
				  $contPreguntas = 0;	
				  while($preg = mysqli_fetch_array($preguntas))  
				  { 
					$arrayPreguntas[] = $preg['id'];	
					?>
					<div class="cuestion">
						<p><span><?php echo $contPreguntas+1; ?>. </span><strong><?php echo $preg['enunciado'];?></strong><br>
						</p>
						<p><?php echo $preg['pregunta'];?><br>
						</p>
					
					<?php
						//consultar respuestas de pregunta:
						$respuestas =  $connect->query( "SELECT DISTINCT * FROM respuestas WHERE id_pregunta =".$preg['id']);				   		
				   		$iter = 0;	
						while($resp = mysqli_fetch_array($respuestas))  
						{	
							if( $preg['id_tipo_pregunta'] == 1 ){//respuestas seleccion unica	
						?>
								<div class="form-check">
								  <label class="form-check-label">
									<input class="form-check-input" type="radio" name="respRadio<?php echo $preg['id'];?>" value="<?php echo $resp['id'];?>">
									<?php echo $resp['descripcion'];?>
								  </label>
								</div>
						<?php } else if( $preg['id_tipo_pregunta'] == 2 ){// respuestas seleccion multiple ?>
								<div class="form-check">
								  <label class="form-check-label">
									<input class="form-check-input" type="checkbox"  name="checkbox<?php echo $preg['id'];?>[]" value="<?php echo $resp['id'];?>">
									<?php echo $resp['descripcion'];?>
								  </label>
								</div>
							<?php } else if( $preg['id_tipo_pregunta'] == 3 ){//respuestas verdadero falso ?>
								<div class="form-check">
								  <label class="form-check-label">
									<input class="form-check-input" type="radio" name="respRadio<?php echo $preg['id'];?>" value="<?php echo $resp['id'];?>">
									<?php echo $resp['descripcion'];?>
								  </label>
								</div>
						<?php }	$iter ++;						
						} ?>
				</div>
				<?php
				   $contPreguntas ++;
				  } ?>				
			</div>			
			<!--<div class="col-sm-12 col-md-4 icon-p">
				<img src="../assets/img/icon15.png" class="img-fluid" alt="Responsive image">
			</div>-->
		</div>
		<div class="row e-btn-submit">			
			<?php if($contPreguntas > 0 ){ ?>			
			<input type="submit" value="ENVIAR" class="btn btn-primary" />
			<?php } else{ echo '<strong>No Existen Preguntas para el test.</strong>'; } ?>				<br/>		
		</div>
		</div>		
	</div>
</form>	
</body>
</html>
<script>
jQuery(document).on('submit','#form-test', function(event){
	var preguntasOk = true;
	<?php 
		
		foreach ($arrayPreguntas as &$valor) { ?>
		var cont = 0;
		var checkboxes = document.getElementsByName('checkbox'+<?php echo $valor; ?>+'[]');
		for(var i=0; i<checkboxes.length; i++) {
			 if (checkboxes[i].checked ) {				 
				 cont++;
			 }
		}
		var radios = document.getElementsByName('respRadio'+<?php echo $valor; ?>);
		for(var x=0; x<radios.length; x++) {
			 if (radios[x].checked ) {				 
				 cont++;
			 }
		}
		if( cont == 0 ){
			preguntasOk = false;
		}
	<?php } ?>
	if( preguntasOk ){
		if(confirm('Confirma enviar?')){
			 $.ajax({
				url: '/Usuario/actions.php?action=saveTest&idUnidad='+<?php echo $idUnidad; ?>+'&id_usuario='+<?php echo $_SESSION[ 'dataSession' ]['id'];?>+'&idCurso='+<?php echo $idCurso;  ?>,
				type: 'POST',
				data: new FormData(this),
				success: function (data) {
				 console.log(data);
					if( !data.error ){
						window.location.href ='/cursos/course-test-result.php?&idUnidad='+<?php echo $idUnidad; ?>+'&id_curso='+<?php echo $idCurso;?>+'&idUsuario='+<?php echo $_SESSION[ 'dataSession' ]['id'];?>;						
					}else{
						console.log(data);
					}		
				},
				error: function (data) {
					console.log(data);    
				},
				cache: false,
				contentType: false,
				processData: false
			});    
		}else{
			event.preventDefault();
		}
	}else{
		alert('Hay preguntas sin responder.');
		event.preventDefault();
	}
	return false;
});
</script>
<?php $connect->close(); ?>		