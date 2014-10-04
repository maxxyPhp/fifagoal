<div class="container">
	<h1 class="center-block center">RAPPORT DE MATCH</h1>
	<div class="row rapport-match">
		<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<input type="hidden" name="defi" value="<?= $defi->id ?>">
		<input type="hidden" name="match" value="<?= $match->id ?>">
		<input type="hidden" name="modifieur" value="<?= \Auth::get('id') ?>">

		<!-- DEFIEUR -->
		<div class="col-md-4">
			<div class="thumbnail-profil">
				<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
			</div>
			<input type="hidden" name="joueur1" value="<?= $defieur->id ?>">
			<div class="form-group animated fadeInUp">
				<div class="col-sm-10">
					<select id="form_championnat_defieur">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<?php if ($equipe1->id_championnat == $championnat->id): ?>
										<option value="<?= $championnat->id ?>" selected><?= $championnat->nom ?></option>
									<?php else: ?>
										<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group animated fadeInUp" id="div_equipes_defieur">
				<div class="col-sm-10">
					<select id="form_equipe_defieur" name="id_equipe_defieur">
						<option></option>
						<?php foreach ($equipe1->championnat->equipes as $equipe): ?>
							<?php if ($equipe->id == $equipe1->id): ?>
								<option value="<?= $equipe->id ?>" selected><?= $equipe->nom ?></option>
							<?php else: ?>
								<option value="<?= $equipe->id ?>"><?= $equipe->nom ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>

		<!-- SCORE -->
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-4 club club_defieur">
					<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe1->championnat->nom)) . '/' . $equipe1->logo ?>" alt="<?= $equipe1->nom ?>" width="100px" class="logo_club_defieur" />
				</div>
				<div class="col-md-4" style="text-align:center;"><h1 style="display:inline-block;">vs</h1></div>
				<div class="col-md-4 club club_defier">
					<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe2->championnat->nom)) . '/' . $equipe2->logo ?>" alt="<?= $equipe2->nom ?>" width="100px" class="logo_club_defier" style="float:right;"/>
				</div>
			</div>

			<div class="score">
				<div class="row"><h2 class="center-block center">Score</h2></div>
				<div class="row">
					
					<div class="col-md-6">
						<input type="number" class="form-control" id="score_joueur1" name="score_joueur_1" min="0" max="20" value="<?= $match->score_joueur1 ?>">
					</div>
					<div class="col-md-6">
						<input type="number" class="form-control" id="score_joueur2" name="score_joueur_2" min="0" max="20" value="<?= $match->score_joueur2 ?>">
					</div>
				</div>
			</div>


			<div class="row valid_match">
				<input type="submit" name="add" value="Valider le match" class="btn btn-primary btn-lg btn-block">
			</div>
		</div>


		<!-- DEFIER -->
		<div class="col-md-4">
			<div class="thumbnail-profil">
				<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $defier->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
			</div>

			<input type="hidden" name="joueur2" value="<?= $defier->id ?>">
			<div class="form-group animated fadeInUp">
				<div class="col-sm-10">
					<select id="form_championnat_defier">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<?php if ($equipe2->id_championnat == $championnat->id): ?>
										<option value="<?= $championnat->id ?>" selected><?= $championnat->nom ?></option>
									<?php else: ?>
										<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group animated fadeInUp" id="div_equipes_defier">
				<div class="col-sm-10">
					<select id="form_equipe_defier" name="id_equipe_defier">
						<option></option>
						<?php foreach ($equipe2->championnat->equipes as $equipe): ?>
							<?php if ($equipe->id == $equipe2->id): ?>
								<option value="<?= $equipe->id ?>" selected><?= $equipe->nom ?></option>
							<?php else: ?>
								<option value="<?= $equipe->id ?>"><?= $equipe->nom ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<?= \Form::close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_championnat_defieur').select2({
			placeholder: "Selectionnez un championnat",
			width: '300px'
		});

		$('#form_equipe_defieur').select2({
			placeholder: "Selectionnez une équipe",
			width: '300px'
		});

		$('#form_championnat_defieur').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defieur'), $('#div_equipes_defieur'));
		});

		$('#form_championnat_defier').select2({
			placeholder: "Selectionnez un championnat",
			width: '300px'
		});

		$('#form_equipe_defier').select2({
			placeholder: "Selectionnez une équipe",
			width: '300px'
		});

		$('#form_championnat_defier').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defier'), $('#div_equipes_defier'));
		});

		/**
		 * afficherEquipes
		 * Affiche le nom des équipes d'un championnat
		 *
		 * @param int id_championnat
		 * @param Noeud select : le select contenant le nom des équipes
		 * @param Noeud afficher : la div contenant le select
		 */
		function afficherEquipes (id_championnat, select, afficher){
			$.ajax({
				url : window.location.origin + '/equipe/api/getEquipes.json',
				data: 'id_championnat='+id_championnat,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						select.select2('val', '');
						select.html('');
						equipe = data;
						for (var i in equipe){
							select.append('<option value="'+equipe[i]['id']+'">'+equipe[i]['nom']+'</option>');
						}
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}


		$('#form_equipe_defieur').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defieur'), 'logo_club_defieur');
		});

		$('#form_equipe_defier').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defier'), 'logo_club_defier');
		});

		/**
		 * ClickEquipe
		 * Affiche le logo de l'équipe sélectionnée
		 *
		 * @param id_equipe : l'id de l'équipe sélectionnée
		 * @param div_equipe : la div où l'image doit apparaître
		 * @param classImg : la classe donnée à l'image
		 */
		function clickEquipe (id_equipe, div_equipe, classImg){
			$('.'+classImg).remove();
			$.ajax({
				url: window.location.origin + '/equipe/api/getEquipe.json',
				data: 'id_equipe=' + id_equipe,
				dataType: 'json',
				type: 'get',
				success : function(data){
					div_equipe.append(
						'<img src="'+window.location.origin+'/upload/equipes/'+data[7]+'/'+data[2]+'" alt="'+data[0]+'" width="100px" class="'+classImg+'" />'
					);
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		}
	});
</script>