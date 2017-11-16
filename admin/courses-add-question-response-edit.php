<?php
require '../conexion.php';
$questiontype = $_GET[ 'questiontype' ];
$responseId = $_GET[ 'responseId' ];
$listResponses =  $connect->query( "SELECT id, descripcion, es_correcta FROM respuestas where id_pregunta=".$responseId ) ;
if( $questiontype != NULL && $questiontype == '1'){
	$rowCount = 1;
	while($row = mysqli_fetch_array($listResponses)){ 
	?>
	<div class="form-group input-add-course col-md-12">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="radio" <?php if($row['es_correcta']){echo "checked";}?> aria-label="..." id="respRadio" name="respRadio" value="<?php echo $rowCount;?>">
			  </span>
			<input type="text" disabled class="form-control" aria-label="..." value="<?php echo $row['descripcion'];?>" id="respRadioText<?php echo $rowCount; ?>" name="respRadioText<?php echo $rowCount;?>">
		</div>
	</div>
	<?php $rowCount++; }
	for($i=$rowCount;$i<=5;$i++){ ?>
		<div class="form-group input-add-course col-md-12">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="<?php echo $i;?>">
			  </span>
			<input type="text" disabled class="form-control" aria-label="..." value="" id="respRadioText<?php echo $i; ?>" name="respRadioText<?php echo $i;?>">
		</div>
	</div>
	<?php }
}?>
<?php
if( $questiontype != NULL && $questiontype == '2'){
	$rowCount = 1;
	while($row = mysqli_fetch_array($listResponses)){ 
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input name="checkbox[]" type="checkbox" value="<?php echo $rowCount; ?>" <?php if($row['es_correcta']){echo "checked";}?>>
		  </span>
		<input type="text" disabled class="form-control" aria-label="..." value="<?php echo $row['descripcion'];?>" id="respCheckText<?php echo $rowCount; ?>" name="respCheckText<?php echo $rowCount; ?>">
	</div>
</div>
<?php $rowCount++; }
	for($i=$rowCount;$i<=5;$i++){ ?>
		<div class="form-group input-add-course col-md-12">
		<div class="input-group">
		<span class="input-group-addon">
			<input name="checkbox[]" type="checkbox" value="<?php echo $i; ?>">
		  </span>
		<input type="text" disabled class="form-control" aria-label="..." value="<?php echo $row['descripcion'];?>" id="respCheckText<?php echo $i; ?>" name="respCheckText<?php echo $i; ?>">
	</div>
	</div>
	<?php }
}?>
<?php
if( $questiontype != NULL && $questiontype == '3'){
?>
<div class="form-group input-add-course col-md-12">
	<?php while($row = mysqli_fetch_array($listResponses)){ ?> 
	<label class="checkbox-inline"><input  type="radio" disabled <?php if($row['es_correcta']){echo "checked";}?> aria-label="..." id="respRadioTf" name="respRadioTf" value="1"><?php echo $row['descripcion']; ?></label>
	<?php }?>	
</div>
<?php } ?>
<?php $connect->close(); ?>