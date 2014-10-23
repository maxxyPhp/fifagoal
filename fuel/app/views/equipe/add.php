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
				<!-- <input type="file" class="form-control" id="form_logo"> -->
				<div id="form_logo">Upload</div>
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

		$("#form_logo").uploadFile({
			url:window.location.origin+'/equipe/uploadLogo',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('#hidden_drapeau').attr('value', data);
			}
		});
	});
</script>
