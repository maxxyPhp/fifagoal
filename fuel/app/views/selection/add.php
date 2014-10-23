<div class="container">
	<h1 class="page-header">
		<?php if ($isUpdate): ?>
			Modifier
		<?php else: ?>
			Ajouter
		<?php endif; ?>
		une selection
	</h1>

	<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<div class="form-group">
			<label for="form_nom" class="col-sm-2 control-label">Nom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_nom" required="required" name="nom" <?php if ($selection->nom): ?> value="<?= $selection->nom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_logo" class="col-sm-2 control-label">Logo</label>
			<div class="col-sm-10">
				<div id="form_logo">Upload</div>
			</div>
		</div>

		<input type="hidden" name="logo" id="hidden_logo" <?php if ($selection->logo): ?> value="<?= $selection->logo ?>" <?php endif; ?>>

		<div class="form-group">
			<label for="form_championnat" class="col-sm-2 control-label">Pays</label>
			<div class="col-sm-10">
				<select class="" name="id_pays" id="form_pays" required="required">
					<option></option>
					<?php foreach ($pays as $pay): ?>
						<?php if ($selection->id_pays == $pay->id): ?> 
							<option value="<?= $pay->id ?>" selected><?= $pay->nom ?></option>
						<?php else: ?>
							<option value="<?= $pay->id ?>"><?= $pay->nom ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="<?php if($isUpdate){echo 'Modifier';}else{echo 'Ajouter';} ?>" name="add">
			</div>
		</div>

	<?= \Form::close(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_pays').select2({
			placeholder: "Selectionnez un pays",
			allowClear: true,
			width: 'element'
		});

		$("#form_logo").uploadFile({
			url:window.location.origin+'/selection/uploadLogo',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('#hidden_logo').attr('value', data);
			}
		});
	});
</script>
