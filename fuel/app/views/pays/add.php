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
				<input type="text" class="form-control" id="form_pays" required="required" name="nom">
			</div>
		</div>

		<div class="form-group">
			<label for="form_drapeau" class="col-sm-2 control-label">Drapeau</label>
			<div class="col-sm-10">
				<input type="file" class="form-control" id="form_drapeau">
			</div>
		</div>

		<input type="hidden" name="drapeau" id="hidden_drapeau">

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="<?php if($isUpdate){echo 'Modifier';}else{echo 'Ajouter';} ?>" name="add">
			</div>
		</div>

	<?= \Form::close(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_drapeau').uploadify({
			'buttonText' : 'Choissir un fichier',
			'buttonClass' : 'btn btn-info btn-upload-photo',
			'swf' : window.location.origin+'/assets/js/uploadify/uploadify.swf',
			'uploader' : window.location.origin+'/pays/uploadDrapeau',
			'fileDesc' : 'Image Files',
			'fileExt' : '*.jpg;*.jpeg;*.png;*.gif;*.bmp;*.pdf',
			'onUploadSuccess' : function(file, data, response){
				$('#hidden_drapeau').attr('value', file.name);
				console.log(file.name);
			}
		});
		$('#form_drapeau-button').removeClass('uploadify-button');
		$('#form_drapeau-button').css('height', '');
	});
</script>