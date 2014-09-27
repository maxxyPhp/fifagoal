<div class="container">
	<h1 class="page-header">
		<?php if ($isUpdate): ?>
			Modifier
		<?php else: ?>
			Ajouter
		<?php endif; ?>
		un joueur
	</h1>

	<?= \Form::open(array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) ?>
		<div class="form-group">
			<label for="form_nom" class="col-sm-2 control-label">Nom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_nom" required="required" name="nom" <?php if ($joueur->nom): ?> value="<?= $joueur->nom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_prenom" class="col-sm-2 control-label">Prénom</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="form_nom" name="prenom" <?php if ($joueur->prenom): ?> value="<?= $joueur->prenom ?>" <?php endif; ?>>
			</div>
		</div>

		<div class="form-group">
			<label for="form_poste" class="col-sm-2 control-label">Poste</label>
			<div class="col-sm-10">
				<select name="id_poste" id="form_poste" required="required">
					<option></option>
					<?php foreach ($postes as $poste): ?>
						<?php if ($joueur->id_poste == $poste->id): ?>
							<option value="<?= $poste->id ?>" selected><?= $poste->nom ?></option>
						<?php else: ?>
							<option value="<?= $poste->id ?>"><?= $poste->nom ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<!-- EQUIPE SI CREATION -->
		<?php if (!$isUpdate): ?>
			<div class="form-group">
				<label for="form_championnat" class="col-sm-2 control-label">Equipe</label>
				<div class="col-sm-10">
					<select id="form_championnat">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group" style="display:none;" id="div_equipes">
				<div class="col-sm-2"></div>
				<div class="col-sm-10">
					<select id="form_equipe" name="id_equipe">
						<option></option>
					</select>
				</div>
			</div>
		<?php endif; ?>

		<!-- EQUIPE SI MODIF -->
		<?php if($isUpdate): ?>
			<div class="form-group" id="select_champ" style="display:none;">
				<label for="form_championnat" class="col-sm-2 control-label">Championnat</label>
				<div class="col-sm-10">
					<select id="form_championnat_update">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group" id="div_equipes">
				<label for="form_equipe" class="col-sm-2 control-label">Equipe</label>
				<div class="col-sm-10">
					<select id="form_equipe_update" name="id_equipe">
						<option></option>
						<?php foreach ($equipes_championnat as $equipe_champ): ?>
							<?php if ($equipe_champ->id == $joueur->id_equipe): ?>
								<option value="<?= $equipe_champ->id ?>" selected><?= $equipe_champ->nom ?></option>
							<?php else: ?>
								<option value="<?= $equipe_champ->id ?>"><?= $equipe_champ->nom ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<a class="btn btn-primary btn-change" style="margin-left:20px;">Changer de championnat</a>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group">
			<label for="form_selection" class="col-sm-2 control-label">Sélection</label>
			<div class="col-sm-10">
				<select name="id_selection" id="form_selection">
					<option></option>
					<?php foreach ($selections as $selection): ?>
						<?php if ($joueur->id_selection == $selection->id): ?> 
							<option value="<?= $selection->id ?>" selected><?= $selection->nom ?></option>
						<?php else: ?>
							<option value="<?= $selection->id ?>"><?= $selection->nom ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="form_photo" class="col-sm-2 control-label">Photo</label>
			<div class="col-sm-10">
				<input type="file" class="form-control btn btn-info" data-toggle="file-input" id="form_photo" name="photo">
			</div>
		</div>

		<!-- <input type="hidden" name="photo" id="hidden_photo" <?php if ($joueur->photo): ?> value="<?= $joueur->photo ?>" <?php endif; ?>> -->

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="<?php if($isUpdate){echo 'Modifier';}else{echo 'Ajouter';} ?>" name="add">
			</div>
		</div>

	<?= \Form::close(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_poste').select2({
			placeholder: "Selectionnez un poste",
			allowClear: true,
			width: '300px'
		});

		$('#form_selection').select2({
			placeholder: "Selectionnez une selection",
			allowClear: true,
			width: '300px'
		});

		$('#form_championnat').select2({
			placeholder: "Selectionnez un championnat",
			allowClear: true,
			width: '300px'
		});

		$('#form_equipe').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});

		$('#form_championnat_update').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});

		$('#form_equipe_update').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});
		
		/**
		 * UPLOADIFY
		 */
		// $('#form_photo').uploadify({
		// 	'buttonText' : 'Choissir un fichier',
		// 	'buttonClass' : 'btn btn-info btn-upload-photo',
		// 	'swf' : window.location.origin+'/assets/js/uploadify/uploadify.swf',
		// 	'uploader' : window.location.origin+'/joueur/uploadPhoto',
		// 	'fileDesc' : 'Image Files',
		// 	'fileExt' : '*.jpg;*.jpeg;*.png;*.gif;*.bmp;*.pdf',
		// 	'onUploadSuccess' : function(file, data, response){
		// 		$('#hidden_photo').attr('value', file.name);
		// 		console.log(file.name);
		// 	}
		// });
		// $('#form_photo-button').removeClass('uploadify-button');
		// $('#form_photo-button').css('height', '');

		$('#form_championnat').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe'));
		});

		$('.btn-change').on('click', function(){
			$('#select_champ').show();
		});

		$('#form_championnat_update').on('change', function(){
			id_championnat = $(this).val();
			$('#form_equipe_update').html('<option></option');
			$('#form_equipe_update').select2('val', '');
			$('#form_equipe_update').select2({placeholder: "Selectionnez une équipe", witdh: '300px'});
			afficherEquipes(id_championnat, $('#form_equipe_update'));
		})

		function afficherEquipes (id_championnat, select){
			$.ajax({
				url : window.location.origin + '/equipe/api/getEquipes.json',
				data: 'id_championnat='+id_championnat,
				type: 'get',
				dataType: 'json',
				success: function(data){
					equipe = data;
					for (var i in equipe){
						console.log(equipe[i]['nom']);
						select.append('<option value="'+equipe[i]['id']+'">'+equipe[i]['nom']+'</option>');
					}
					$('#div_equipes').show();
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}
	});
</script>
