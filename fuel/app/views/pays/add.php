<div class="container">
	<h1 class="page-header">
		<?php if($isUpdate): ?>
			Modifier
		<?php else: ?>
			Ajouter
		<?php endif; ?>
		un pays
	</h1>

	<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<div class="form-group">
			<label for="form_nom" class="col-sm-2 control-label">Nom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_pays" required="required" name="nom" <?php if($pays->nom): ?> value="<?= $pays->nom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_drapeau" class="col-sm-2 control-label">Drapeau</label>
			<div class="col-sm-10">
				<!-- <input type="file" class="form-control" id="form_drapeau"> -->
				<div id="form_drapeau">Upload</div>
			</div>
		</div>


		<input type="hidden" name="drapeau" id="hidden_drapeau" <?php if($pays->drapeau): ?> value="<?= $pays->drapeau ?>" <?php endif; ?>>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="<?php if($isUpdate){echo 'Modifier';}else{echo 'Ajouter';} ?>" name="add">
			</div>
		</div>

	<?= \Form::close(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#form_drapeau").uploadFile({
			url:window.location.origin+'/pays/uploadDrapeau',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('#hidden_drapeau').attr('value', data);
			}
		});
	});
</script>