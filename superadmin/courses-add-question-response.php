<?php
require '../conexion.php';
$questiontype = $_GET[ 'questiontype' ];
$numResp = $_GET[ 'numResp' ];
if( $questiontype != NULL && $questiontype == '1'){
for ($i = 1; $i <= $numResp; $i++) {	
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input type="radio" required aria-label="..." id="respRadio" name="respRadio" value="<?php echo $i; ?>">
		  </span>
		<input type="text" required class="form-control" aria-label="..." placeholder="Respuesta #<?php echo $i; ?>" id="respRadioText<?php echo $i; ?>" name="respRadioText<?php echo $i; ?>">
	</div>
</div>
<?php } } ?>
<?php
if( $questiontype != NULL && $questiontype == '2'){
for ($i = 1; $i <= $numResp; $i++) {
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input name="checkbox[]" type="checkbox" value="<?php echo $i; ?>">
		  </span>
		<input type="text" required class="form-control" aria-label="..." placeholder="Respuesta #<?php echo $i; ?>" id="respCheckText<?php echo $i; ?>" name="respCheckText<?php echo $i; ?>">
	</div>
</div>
<?php } } ?>
<?php
if( $questiontype != NULL && $questiontype == '3'){
?>
<div class="form-group input-add-course col-md-12">
	<label class="checkbox-inline"><input  type="radio" aria-label="..." id="respRadioTf" name="respRadioTf" value="1">  FALSO</label>
	<label class="checkbox-inline"><input  type="radio" aria-label="..." id="respRadioTf" name="respRadioTf" value="2">  VERDADERO</label>
</div>
<?php } ?>