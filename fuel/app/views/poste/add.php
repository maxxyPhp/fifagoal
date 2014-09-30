<div class="container">
	<h1 class="page-header">
		<?php if ($isUpdate): ?>
			Modifier
		<?php else: ?>
			Ajouter
		<?php endif; ?>
		un poste
	</h1>

	<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<div class="form-group">
			<label for="form_nom" class="col-sm-2 control-label">Nom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_nom" required="required" name="nom" <?php if ($poste->nom): ?> value="<?= $poste->nom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_couleur" class="col-sm-2 control-label">Couleur</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_couleur" required="required" name="couleur" <?php if ($poste->couleur): ?> value="<?= $poste->couleur ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="<?php if($isUpdate){echo 'Modifier';}else{echo 'Ajouter';} ?>" name="add">
			</div>
		</div>

	<?= \Form::close(); ?>
</div>