<div class="container">
	<h1 class="center-block center">MODIFICATION D'UN RAPPORT DE MATCH</h1>
	<div class="row rapport-match">
		<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<input type="hidden" name="defi" value="<?= $defi->id ?>">
		<input type="hidden" name="match" value="<?= $match->id ?>">
		<input type="hidden" name="modifieur" value="<?= \Auth::get('id') ?>">

		<!-- DEFIEUR -->
		<div class="col-md-4">
			<div class="thumbnail-profil">
				<?php if ($photo_defieur): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php endif; ?>
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

			<?php //var_dump($equipe1->joueurs);die(); ?>
			<!-- Div buteurs -->
			<div class="list-buteurs-defieur">
				<h4>Liste des buteurs :</h4>

				<?php// var_dump($buteurs_dom);die(); ?>
				<?php foreach ($buteurs_dom as $score => $buteur): ?>
					<div class="form-group animated fadeInUp buteurs-domicile buteurs-dom-.$i">
						<div class="col-sm-8">
							<select id="buteurs-dom-<?= $score+1 ?>" name="buteurs-dom[<?= $score+1 ?>]" class="buteurs">
								<option></option>
								<?php foreach ($equipe1->joueurs as $joueur): ?>
									<?php if ($buteur['joueur']->id == $joueur->id): ?>
										<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php else: ?>
										<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-sm-4">
							<?php if ($match->prolongation == 1): ?>
								<input type="number" name="minute_dom_buteur[<?= $score+1 ?>]" min="1" max="120" value="<?= $buteur['but']->minute ?>" class="form-control buteurs-dom-<?= $score+1 ?>" placeholder="Minute">
							<?php else: ?>
								<input type="number" name="minute_dom_buteur[<?= $score+1 ?>]" min="1" max="90" value="<?= $buteur['but']->minute ?>" class="form-control buteurs-dom-<?= $score+1 ?>" placeholder="Minute">
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="form-group" style="display:none;" id="div_joueurs_defieur">
				<div class="col-sm-10">
					<select id="form_joueur_defieur" name="id_joueur_defieur">
						<option></option>
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

				<div class="center-block center" style="margin-top:10px;">
					<?php if ($match->prolongation == 1): ?>
						<input type="checkbox" name="prolongation" id="prolong" checked>
					<?php else: ?>
						<input type="checkbox" name="prolongation" id="prolong">
					<?php endif; ?>
				</div>
			</div>


			<div class="row valid_match">
				<input type="submit" name="add" value="Valider le match" class="btn btn-primary btn-lg btn-block">
			</div>
		</div>


		<!-- DEFIER -->
		<div class="col-md-4">
			<div class="thumbnail-profil">
				<?php if ($photo_defier): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $defier->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php endif; ?>
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

			<!-- Div buteurs -->
			<div class="list-buteurs-defier">
				<h4>Liste des buteurs :</h4>
				<?php foreach ($buteurs_ext as $score => $buteur): ?>
					<div class="form-group animated fadeInUp buteurs-exterieur buteurs-ext-<?= $score+1 ?>">
						<div class="col-sm-8">
							<select id="buteurs-ext-<?= $score+1 ?>" name="buteurs-ext[<?= $score+1 ?>]" class="buteurs">
								<option></option>
								<?php foreach ($equipe2->joueurs as $joueur): ?>
									<?php if ($buteur['joueur']->id == $joueur->id): ?>
										<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php else: ?>
										<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-sm-4">
							<?php if ($match->prolongation == 1): ?>
								<input type="number" name="minute_ext_buteur[<?= $score+1 ?>]" min="1" max="120" value="<?= $buteur['but']->minute ?>" class="form-control buteurs-ext-<?= $score+1 ?>" placeholder="Minute">
							<?php else: ?>
								<input type="number" name="minute_ext_buteur[<?= $score+1 ?>]" min="1" max="90" value="<?= $buteur['but']->minute ?>" class="form-control buteurs-ext-<?= $score+1 ?>" placeholder="Minute">
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="form-group" style="display:none;" id="div_joueurs_defier">
				<div class="col-sm-10">
					<select id="form_joueur_defier" name="id_joueur_defier">
						<option></option>
					</select>
				</div>
			</div>
		</div>

		<?= \Form::close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var max_minute;
		
		if (<?= $match->prolongation ?> == 1){
			max_minute = 120;
		} else max_minute = 90;

		$('.buteurs').select2({
			width: '240px'
		});

		$('#prolong').bootstrapSwitch({
			size: 'normal',
			onText: 'Oui',
			offText: 'Non',
			labelText: 'Prolong',
			onSwitchChange: function (event, state){
				if (state){
					max_minute = 120;
					$(':input[type="number"]').attr('max', 120);
				} else {
					max_minute = 90;
					$(':input[type="number"]').attr('max', 90);
					$(':input[type="number"]').each(function(index){
						if ($(this).val() > 90){
							$(this).val(90);
						}
					});
				} 
			}
		});

		$('#form_championnat_defieur, #form_championnat_defier').select2({
			placeholder: "Selectionnez un championnat",
			width: '300px'
		});

		$('#form_equipe_defieur, #form_equipe_defier').select2({
			placeholder: "Selectionnez une équipe",
			width: '300px'
		});

		$('#form_championnat_defieur').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defieur'), $('#div_equipes_defieur'));
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
			clickEquipe ($(this).val(), $('.club_defieur'), 'logo_club_defieur', 'list-buteurs-defieur', $('#score_joueur1').val(), 'buteurs-domicile', 'dom', 'defieur');
		});

		$('#form_equipe_defier').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defier'), 'logo_club_defier', 'list-buteurs-defier', $('#score_joueur2').val(), 'buteurs-exterieur', 'ext', 'defier');
		});

		/**
		 * ClickEquipe
		 * Affiche le logo de l'équipe sélectionnée
		 *
		 * @param id_equipe : l'id de l'équipe sélectionnée
		 * @param div_equipe : la div où l'image doit apparaître
		 * @param classImg : la classe donnée à l'image
		 */
		function clickEquipe (id_equipe, div_equipe, classImg, listButeurs, score, classButeurs, ordre, joueur){
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

					if ($('#form_equipe_defieur').val() != 0 && $('#form_equipe_defier').val() != 0){
						$('.score').show();
						$('input:submit').attr('disabled', false);
					}

					$('.'+listButeurs).html('');
					actionScore(score, $('.'+listButeurs+' > div').length, listButeurs, classButeurs, ordre, $('#form_equipe_'+joueur).val());
					// $('.list-buteurs-defieur').html('');
					
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		}

		/**
		 *
		 * GESTION DES BUTEURS
		 *
		 */
		$('#score_joueur1').on('click blur', function(){
			actionScore($(this).val(), $('.list-buteurs-defieur > div').length, 'list-buteurs-defieur', 'buteurs-domicile', 'dom', $('#form_equipe_defieur').val());
		});

		$('#score_joueur2').on('click blur', function(){
			actionScore($(this).val(), $('.list-buteurs-defier > div').length, 'list-buteurs-defier', 'buteurs-exterieur', 'ext', $('#form_equipe_defier').val());
		});

		/**
		 * actionScore
		 * Détermine l'action à effectuer pendant évenement score
		 *
		 * @param int score
		 * @param int nb_element : le nombre de select déjà présente
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int idEquipe : l'id de l'équipe sélectionnée
		 */
		function actionScore (score, nb_element, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score == nb_element){
				return false;
			} else if (score < nb_element){
				for (var i = nb_element; i > score; i--){
					$('.'+listeJoueur).children().each(function(){
						if ($(this).hasClass('buteurs-'+ordre+'-'+i) || i == 1){
							$(this).remove();
						}
					});
				}
			} else {
				for (var i = nb_element+1; i <= score; i++){
					afficherChoixButeurs(i, listeJoueur, nomDivGen, ordre, idEquipe);
				}
			}
		}
				


		/**
		 * afficherChoixButeurs
		 * Affiche des listes déroulantes avec le nom des joueurs de l'équipes sélectionnée
		 *
		 * @param int score
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int selectEquipe : l'id de l'équipe sélectionnée
		 */
		function afficherChoixButeurs (score, listeJoueur, nomDivGen, ordre, selectEquipe){
			if (score > 0){
				$('.'+listeJoueur).append(
					'<div class="form-group '+nomDivGen+' buteurs-'+ordre+'-'+score+' animated fadeInUp" style="display:none;">'
						+'<div class="col-sm-8">'
							+'<select id="buteurs-'+ordre+'-'+score+'" name="buteurs-'+ordre+'['+score+']" class="buteurs">'
								+'<option></option>'
							+'</select>'
						+'</div>'
						+'<div class="col-sm-4">'
							+'<input type="number" name="minute_'+ordre+'_buteur['+score+']" min="1" max="'+max_minute+'" class="form-control buteurs-'+ordre+'-'+score+'" placeholder="Minute" style="display:none;">'
						+'</div>'
					+'</div>'
				);

				afficherJoueurs(selectEquipe, $('#buteurs-'+ordre+'-'+score));

				$('#buteurs-'+ordre+'-'+score).select2({
					placeholder: "Selectionnez un joueur",
					width: '230px'
				});

				if ($('.'+listeJoueur+' > h4').length == 0){
					$('.'+listeJoueur).prepend('<h4>Liste des buteurs :</h4>');
				}

				$('.'+nomDivGen).show();
				$('.'+listeJoueur).show();
			}
		}


		/**
		 * afficherJoueur
		 * Fais une requête pour obtenir les joueurs d'une équipe
		 *
		 * @param int id_equipe
		 * @param element select : le select qui contiendra les noms des joueurs
		 */
		function afficherJoueurs (id_equipe, select){
			$.ajax({
				url : window.location.origin + '/joueur/api/getJoueurs.json',
				data: 'id_equipe='+id_equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						select.html('');
						joueur = data;
						for (var i in joueur){
							select.append('<option value="'+joueur[i]['id']+'">'+joueur[i]['nom'].toUpperCase()+' - '+joueur[i]['prenom'].charAt(0).toUpperCase() + joueur[i]['prenom'].substring(1).toLowerCase()+'</option>');
						}
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}

		// Choix d'un buteur, focus sur la minute
		$('body').on('change', '.buteurs', function(){
			id = $(this).attr('id');
			$('.'+id).show().focus();
		});
	});
</script>