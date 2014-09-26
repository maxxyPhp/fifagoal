<div class="container">
	<h1 class="page-header">
		<?php if ($isUpdate): ?>
			Modifier
		<?php else: ?>
			Ajouter
		<?php endif; ?>
		une équipe
	</h1>

	<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<div class="form-group">
			<label for="form_nom" class="col-sm-2 control-label">Nom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_nom" required="required" name="nom" <?php if ($equipe->nom): ?> value="<?= $equipe->nom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_logo" class="col-sm-2 control-label">Logo</label>
			<div class="col-sm-10">
				<input type="file" class="form-control" id="form_logo">
			</div>
		</div>

		<input type="hidden" name="logo" id="hidden_logo" <?php if ($equipe->logo): ?> value="<?= $equipe->logo ?>" <?php endif; ?>>

		<div class="form-group">
			<label for="form_championnat" class="col-sm-2 control-label">Championnat</label>
			<div class="col-sm-10">
				<select class="" name="id_championnat" id="form_championnat" required="required">
					<option></option>
					<?php foreach ($championnats as $championnat): ?>
						<?php if ($equipe->id_championnat == $championnat->id): ?> 
							<option value="<?= $championnat->id ?>" selected><?= $championnat->nom ?></option>
						<?php else: ?>
							<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
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
		$('#form_championnat').select2({
			placeholder: "Selectionnez un championnat",
			allowClear: true,
			width: 'element'
		});

		$('#form_logo').uploadify({
			'buttonText' : 'Choissir un fichier',
			'buttonClass' : 'btn btn-info btn-upload-photo',
			'swf' : window.location.origin+'/assets/js/uploadify/uploadify.swf',
			'uploader' : window.location.origin+'/equipe/uploadLogo',
			'fileDesc' : 'Image Files',
			'fileExt' : '*.jpg;*.jpeg;*.png;*.gif;*.bmp;*.pdf',
			'onUploadSuccess' : function(file, data, response){
				$('#hidden_logo').attr('value', file.name);
				console.log(file.name);
			}
		});
		$('#form_logo-button').removeClass('uploadify-button');
		$('#form_logo-button').css('height', '');
	});
</script>
