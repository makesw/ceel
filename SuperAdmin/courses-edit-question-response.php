<?php
require '../conexion.php';
$questiontype = $_GET[ 'questiontype' ];
$responseId = $_GET[ 'responseId' ];
$listResponses =  $connect->query( "SELECT * FROM respuestas where id_pregunta=".$responseId ) ;
if( $questiontype != NULL && $questiontype == '1'){//selecciòn unica
while($row = mysqli_fetch_array($listResponses)){ 
	?>
	<div class="form-group input-add-course col-md-12">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="radio" <?php if($row['es_correcta']){echo "checked";}?> aria-label="..." id="respRadio" name="respRadio" value="<?php echo $row['numero'];?>">
			  </span>
			<input type="text" class="form-control" aria-label="..." value="<?php echo $row['descripcion'];?>" id="respRadioText<?php echo $row['numero']; ?>" name="respRadioText<?php echo $row['numero'];?>">
		</div>
	</div>
<?php } }?>
<?php
if( $questiontype != NULL && $questiontype == '2'){ //selección multile
while($row = mysqli_fetch_array($listResponses)){ 
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input name="checkbox[]" type="checkbox" value="<?php echo $row['numero']; ?>" <?php if($row['es_correcta']){echo "checked";}?>>
		  </span>
		<input type="text" class="form-control" aria-label="..." value="<?php echo $row['descripcion'];?>" id="respCheckText<?php echo $row['numero']; ?>" name="respCheckText<?php echo $row['numero']; ?>">
	</div>
</div>
<?php } } ?>
<?php
if( $questiontype != NULL && $questiontype == '3'){
?>
<div class="form-group input-add-course col-md-12">
	<?php while($row = mysqli_fetch_array($listResponses)){ ?> 
	<label class="checkbox-inline"><input  type="radio" <?php if($row['es_correcta']){echo "checked";}?> aria-label="..." id="respRadioTf" name="respRadioTf" value="<?php echo $row['numero']; ?>"><?php echo $row['descripcion']; ?></label>
	<?php }?>	
</div>
<?php } ?>
<?php $connect->close(); ?>