<?php
require '../conexion.php';
$questiontype = $_GET[ 'questiontype' ];
if( $questiontype != NULL && $questiontype == '1'){
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="1">
		  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #1" id="respRadioText1" name="respRadioText1">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="2">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #2" id="respRadioText2" name="respRadioText2">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="3">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #3" id="respRadioText3" name="respRadioText3">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="4">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #4" id="respRadioText4" name="respRadioText4">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input type="radio" aria-label="..." id="respRadio" name="respRadio" value="5">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #5" id="respRadioText5" name="respRadioText5">
	</div>
</div>
<?php } ?>
<?php
if( $questiontype != NULL && $questiontype == '2'){
?>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
			<input name="checkbox[]" type="checkbox" value="1">
		  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #1" id="respCheckText1" name="respCheckText1">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input name="checkbox[]" type="checkbox" value="2">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #2" id="respCheckText2" name="respCheckText2">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input name="checkbox[]" type="checkbox" value="3">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #3" id="respCheckText3" name="respCheckText3">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input name="checkbox[]" type="checkbox" value="4">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #4" id="respCheckText4" name="respCheckText4">
	</div>
</div>
<div class="form-group input-add-course col-md-12">
	<div class="input-group">
		<span class="input-group-addon">
		<input name="checkbox[]" type="checkbox" value="5">
	  </span>
		<input type="text" class="form-control" aria-label="..." placeholder="Respuesta #5" id="respCheckText5" name="respCheckText5">
	</div>
</div>
<?php } ?>
<?php
if( $questiontype != NULL && $questiontype == '3'){
?>
<div class="form-group input-add-course col-md-12">
	<label class="checkbox-inline"><input  type="radio" aria-label="..." id="respRadioTf" name="respRadioTf" value="1">FALSO</label>
	<label class="checkbox-inline"><input  type="radio" aria-label="..." id="respRadioTf" name="respRadioTf" value="2">VERDADERO</label>
</div>
<?php } ?>