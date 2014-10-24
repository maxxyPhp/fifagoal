<div class="container">
	<h1 class="center-block center">MODIFICATION D'UN RAPPORT DE MATCH</h1>
	<div class="row rapport-match">
		<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<input type="hidden" name="defi" value="<?= $match->defi->id ?>">
		<input type="hidden" name="match" value="<?= $match->id ?>">
		<input type="hidden" name="modifieur" value="<?= \Auth::get('id') ?>">

		<!-- DEFIEUR -->
		<div class="col-md-4">
			<div class="thumbnail-profil">
				<?php if ($photo_defieur): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $match->defi->defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->defi->defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php endif; ?>
			</div>
			<input type="hidden" name="joueur1" value="<?= $match->defi->defieur->id ?>">
			<div class="form-group animated fadeInUp">
				<div class="col-sm-10">
					<select id="form_championnat_defieur">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<?php if ($match->equipe1->id_championnat == $championnat->id): ?>
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
						<?php foreach ($match->equipe1->championnat->equipes as $equipe): ?>
							<?php if ($equipe->id == $match->equipe1->id): ?>
								<option value="<?= $equipe->id ?>" selected><?= $equipe->nom ?></option>
							<?php else: ?>
								<option value="<?= $equipe->id ?>"><?= $equipe->nom ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<!-- Div buteurs -->
			<div class="list-buteurs-defieur">
				<?php if ($buteurs): ?>
					<h4>Liste des buteurs :</h4>
					<?php $i = 0; ?>
					<?php foreach ($buteurs as $score => $buteur): ?>
						<?php if ($buteur->joueur->equipe->id == $match->equipe1->id): ?>
							<?php $i++; ?>
							<div class="form-group animated fadeInUp buteurs-domicile buteurs-dom-.$i">
								<div class="col-sm-8">
									<select id="buteurs-dom-<?= $i ?>" name="buteurs-dom[<?= $i ?>]" class="buteurs">
										<option></option>
										<?php foreach ($match->equipe1->joueurs as $joueur): ?>
											<?php if ($buteur->joueur->id == $joueur->id): ?>
												<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
											<?php else: ?>
												<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-4">
									<?php if ($match->prolongation == 1): ?>
										<input type="number" name="minute_dom_buteur[<?= $i ?>]" min="1" max="120" value="<?= $buteur->minute ?>" class="form-control buteurs-dom-<?= $i ?>" placeholder="Minute">
									<?php else: ?>
										<input type="number" name="minute_dom_buteur[<?= $i ?>]" min="1" max="90" value="<?= $buteur->minute ?>" class="form-control buteurs-dom-<?= $i ?>" placeholder="Minute">
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
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
					<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" alt="<?= $match->equipe1->nom ?>" width="100px" class="logo_club_defieur" />
				</div>
				<div class="col-md-4" style="text-align:center;"><h1 style="display:inline-block;">vs</h1></div>
				<div class="col-md-4 club club_defier">
					<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" alt="<?= $match->equipe2->nom ?>" width="100px" class="logo_club_defier" style="float:right;"/>
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

				<div class="center-block center" style="margin-top:10px;">
					<?php if ($match->id_tab != 0): ?>
						<input type="checkbox" name="tab" id="tab" checked>
					<?php else: ?>
						<input type="checkbox" name="tab" id="tab">
					<?php endif; ?>
				</div>

				<?php if ($match->id_tab == 0): ?>
					<div id="rapp_tab" class="center-block center" style="display:none;margin-top:10px;">
				<?php else: ?>
					<div id="rapp_tab" class="center-block center" style="margin-top:10px;">
				<?php endif; ?>
						<div class="row"><h3 class="center-block center">Tirs aux buts</h3></div>
						<?php if (!empty($match->tab->tireurs)): ?>
							<input type="hidden" id="mode_tab" name="mode_tab" value="detaille">
						<?php else: ?>
							<input type="hidden" id="mode_tab" name="mode_tab" value="score">
						<?php endif; ?>
						<a class="btn btn-primary btn-lg btn-score-tab" data-mode="score"><i class="fa fa-tag fa-2x pull-left"></i> Score</a>
						<a class="btn btn-primary btn-lg btn-score-tab" data-mode="detaille"><i class="fa fa-newspaper-o fa-2x pull-left"></i> Descriptif détaillé</a>

						<?php if (empty($match->tab->tireurs)): ?>
							<!-- SCORE -->
							<div class="row tab-score">
								<div class="col-md-6">
									<input type="number" class="form-control" id="tab_joueur1" name="tab_joueur_1" min="3" max="20" value="<?php if (!empty($match->tab->score_joueur1)): echo $match->tab->score_joueur1; endif; ?>" placeholder="Score J1">
								</div>
								<div class="col-md-6">
									<input type="number" class="form-control" id="tab_joueur2" name="tab_joueur_2" min="3" max="20" value="<?php if (!empty($match->tab->score_joueur2)): echo $match->tab->score_joueur2; endif; ?>" placeholder="Score J2">
								</div>
							</div>

							<!-- DETAILLE -->
							<div class="row tab-detaille" style="display:none;">
								<div class="col-md-6">
									<input type="number" class="form-control" id="score_tab_joueur1" name="score_tab_joueur_1" min="3" max="20" placeholder="Nb tirs" data-toggle="popover" data-trigger="focus" title="Attention" data-content="Les cases des TAB permettent d'indiquer le nombre de tirs de chaque équipe, et non le score. Vous pourrez ensuite indiquer si certains joueurs ont loupés leurs tirs ou non. Il faut minimum trois tirs pour gagner une séance de TAB.">
								</div>
								<div class="col-md-6">
									<input type="number" class="form-control" id="score_tab_joueur2" name="score_tab_joueur_2" min="3" max="20" placeholder="Nb tirs">
								</div>
							</div>
						<?php else: ?>
							<!-- SCORE -->
							<div class="row tab-score">
								<div class="col-md-6">
									<input type="number" class="form-control" id="tab_joueur1" name="tab_joueur_1" min="3" max="20" placeholder="Score J1">
								</div>
								<div class="col-md-6">
									<input type="number" class="form-control" id="tab_joueur2" name="tab_joueur_2" min="3" max="20" placeholder="Score J2">
								</div>
							</div>

							<!-- DETAILLE -->
							<div class="row tab-detaille" style="display:none;">
								<div class="col-md-6">
									<input type="number" class="form-control" id="score_tab_joueur1" name="score_tab_joueur_1" min="3" max="20" value="<?php if ($nb_tireurs_dom): echo $nb_tireurs_dom; endif; ?>" placeholder="Nb tirs" data-toggle="popover" data-trigger="focus" title="Attention" data-content="Les cases des TAB permettent d'indiquer le nombre de tirs de chaque équipe, et non le score. Vous pourrez ensuite indiquer si certains joueurs ont loupés leurs tirs ou non. Il faut minimum trois tirs pour gagner une séance de TAB.">
								</div>
								<div class="col-md-6">
									<input type="number" class="form-control" id="score_tab_joueur2" name="score_tab_joueur_2" min="3" max="20" value="<?php if ($nb_tireurs_ext): echo $nb_tireurs_ext; endif; ?>" placeholder="Nb tirs">
								</div>
							</div>
						<?php endif; ?>

						<div class="row tab-tireurs-detail" style="margin-top:10px;">
							<div class="col-md-6">
								<div class="list-tireurs-defieur">
									<?php if ($tireurs): ?>
										<?php $i = 0; ?>
										<?php foreach ($tireurs as $tireur): ?>
											<?php if ($tireur->joueur->equipe->id == $match->id_equipe1): ?>
												<?php $i++; ?>
												<div class="form-group tireurs-domicile tireurs-dom-<?= $i ?> animated fadeInUp">
													<div class="col-sm-10">
														<select id="tireurs-dom-<?= $i ?>" name="tireurs-dom[<?= $i ?>]" class="tireurs">
															<option></option>
															<?php foreach ($match->equipe1->joueurs as $joueur): ?>
																<?php if ($joueur->id == $tireur->id_joueur): ?>
																	<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?> - <?= ucfirst($joueur->prenom) ?></option>
																<?php else: ?>
																	<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?> - <?= ucfirst($joueur->prenom) ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-2">
														<?php if ($tireur->reussi == 1): ?>
															<input type="checkbox" name="tireurs_dom_reussite[<?= $i ?>]" class="tireurs-dom-<?= $i ?>" checked>
														<?php else: ?>
															<input type="checkbox" name="tireurs_dom_reussite[<?= $i ?>]" class="tireurs-dom-<?= $i ?>">
														<?php endif; ?>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="list-tireurs-defier">
									<?php if ($tireurs): ?>
										<?php $i = 0; ?>
										<?php foreach ($tireurs as $tireur): ?>
											<?php if ($tireur->joueur->equipe->id == $match->id_equipe2): ?>
												<?php $i++; ?>
												<div class="form-group tireurs-exterieur tireurs-ext-<?= $i ?> animated fadeInUp">
													<div class="col-sm-10">
														<select id="tireurs-ext-<?= $i ?>" name="tireurs-ext[<?= $i ?>]" class="tireurs">
															<option></option>
															<?php foreach ($match->equipe2->joueurs as $joueur): ?>
																<?php if ($joueur->id == $tireur->id_joueur): ?>
																	<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?> - <?= ucfirst($joueur->prenom) ?></option>
																<?php else: ?>
																	<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?> - <?= ucfirst($joueur->prenom) ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-2">
														<?php if ($tireur->reussi == 1): ?>
															<input type="checkbox" name="tireurs_ext_reussite[<?= $i ?>]" class="tireurs-ext-<?= $i ?>" checked>
														<?php else: ?>
															<input type="checkbox" name="tireurs_ext_reussite[<?= $i ?>]" class="tireurs-ext-<?= $i ?>">
														<?php endif; ?>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</div>
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
				<?php if ($photo_defier): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $match->defi->defier->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->defi->defier->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php endif; ?>
			</div>

			<input type="hidden" name="joueur2" value="<?= $match->defi->defier->id ?>">
			<div class="form-group animated fadeInUp">
				<div class="col-sm-10">
					<select id="form_championnat_defier">
						<option></option>
						<?php foreach ($pays as $pay): ?>
							<optgroup label="<?= $pay->nom ?>">
							<?php foreach ($championnats as $championnat): ?>
								<?php if ($championnat->id_pays == $pay->id): ?>
									<?php if ($match->equipe2->id_championnat == $championnat->id): ?>
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
						<?php foreach ($match->equipe2->championnat->equipes as $equipe): ?>
							<?php if ($equipe->id == $match->equipe2->id): ?>
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
				<?php if ($buteurs): ?>
					<h4>Liste des buteurs :</h4>
					<?php $i = 0; ?>
					<?php foreach ($buteurs as $score => $buteur): ?>
						<?php if ($buteur->joueur->equipe->id == $match->equipe2->id): ?>
							<?php $i++; ?>
							<div class="form-group animated fadeInUp buteurs-exterieur buteurs-ext-<?= $i ?>">
								<div class="col-sm-8">
									<select id="buteurs-ext-<?= $i ?>" name="buteurs-ext[<?= $i ?>]" class="buteurs">
										<option></option>
										<?php foreach ($match->equipe2->joueurs as $joueur): ?>
											<?php if ($buteur->joueur->id == $joueur->id): ?>
												<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
											<?php else: ?>
												<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-4">
									<?php if ($match->prolongation == 1): ?>
										<input type="number" name="minute_ext_buteur[<?= $i ?>]" min="1" max="120" value="<?= $buteur->minute ?>" class="form-control buteurs-ext-<?= $i ?>" placeholder="Minute">
									<?php else: ?>
										<input type="number" name="minute_ext_buteur[<?= $i ?>]" min="1" max="90" value="<?= $buteur->minute ?>" class="form-control buteurs-ext-<?= $i ?>" placeholder="Minute">
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
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

		$('#score_tab_joueur1').popover({
			animation: true,
			placement: 'top',
		});
		
		if (<?= $match->prolongation ?> == 1){
			max_minute = 120;
		} else max_minute = 90;

		$('.buteurs').select2({
			width: '240px'
		});

		$('.tireurs').select2({
			width: '140px'
		})

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

		$('#tab').bootstrapSwitch({
			size: 'normal',
			onText: 'Oui',
			offText: 'Non',
			labelText: 'TAB ?',
			onSwitchChange: function (event, state){
				//OUI
				if (state){
					$('#rapp_tab').show();
				} else {
					$('#rapp_tab').hide();
				} 
			}
		});

		if ($('#mode_tab').val() == 'detaille'){
			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == 'detaille'){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		} else {
			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == 'score'){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		}


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
			$('.logo_club_defieur').remove();
			resetAll ($('.list-buteurs-defieur > div').length, $('.list-tireurs-defieur > div').length, '#buteurs-dom-', '#tireurs-dom-');
		});


		$('#form_championnat_defier').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defier'), $('#div_equipes_defier'));
			$('.logo_club_defier').remove();
			resetAll ($('.list-buteurs-defier > div').length, $('.list-tireurs-defier > div').length, '#buteurs-ext-', '#tireurs-ext-');
		});

		function resetAll (nb_element_buteurs, nb_element_tireurs, select_buteurs, select_tireurs){
			for (var i = 1; i <= nb_element_buteurs; i++){
				select = $(select_buteurs+i);
				select.html('');
				select.select2('val', '');
				select.append('<option></option>');
				select.select2({
					placeholder: "Selectionnez un joueur",
					width: '230px'
				});
			}

			for (var i = 1; i <= nb_element_tireurs; i++){
				select = $(select_tireurs+i);
				select.html('');
				select.select2('val', '');
				select.append('<option></option>');
				select.select2({
					placeholder: "Tireur #"+i,
					width: '140px'
				});
			}

			$('input:submit').attr('disabled', true);
		}

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

		/**
		 *
		 * CHOIX D'UNE EQUIPE
		 *
		 */
		$('#form_equipe_defieur').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defieur'), 'logo_club_defieur', 'list-buteurs-defieur', $('#score_joueur1').val(), 'buteurs-domicile', 'dom', 'defieur');
			resetButeurs ($('.list-buteurs-defieur > div').length, $('#form_equipe_defieur').val(), '#buteurs-dom-');
			resetTireurs($('.list-tireurs-defieur > div').length, $('#form_equipe_defieur').val(), '#tireurs-dom-');
		});

		$('#form_equipe_defier').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defier'), 'logo_club_defier', 'list-buteurs-defier', $('#score_joueur2').val(), 'buteurs-exterieur', 'ext', 'defier');
			resetButeurs ($('.list-buteurs-defier > div').length, $('#form_equipe_defier').val(), '#buteurs-ext-');
			resetTireurs($('.list-tireurs-defier > div').length, $('#form_equipe_defier').val(), '#tireurs-ext-');
		});

		/**
		 * resetButeurs
		 * Change le noms des buteurs dans les listes déroulantes
		 *
		 * @param int nb_element
		 * @param int idEquipe
		 * @param String select
		 */
		function resetButeurs (nb_element, idEquipe, select){
			for (var i = 1; i <= nb_element; i++){
				afficherJoueurs(idEquipe, $(select+i), 'but');
			}
		}

		/**
		 * resetTireurs
		 * Change les nomrs des tireurs dans les listes déroulantes
		 *
		 * @param int nb_element
		 * @param int idEquipe
		 * @param String select
		 */
		function resetTireurs (nb_element, idEquipe, select){
			for (var i = 1; i <= nb_element; i++){
				afficherJoueurs(idEquipe, $(select+i), 'tir_reset');
			}
		}

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
		function afficherJoueurs (id_equipe, select, context){
			$.ajax({
				url : window.location.origin + '/joueur/api/getJoueurs.json',
				data: 'id_equipe='+id_equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						select.html('');
						
						if (context == 'but'){
							select.select2('val', '');
							select.append('<option></option>');
							select.select2({
								placeholder: "Selectionnez un joueur",
								width: '230px'
							});
						}
						else if (context == 'tir_reset'){
							select.select2('val', '');
							select.append('<option></option>');
							select.select2({
								placeholder: "Tireur",
								width: '140px'
							});
						}
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

		/**
		 *
		 * GESTION DES TAB
		 *
		 */
		 // Choix du mode de TAB
		$('.btn-score-tab').on('click', function(){
			mode = $(this).attr('data-mode');
			btn = $(this);
			if (mode == 'score'){
				$('.tab-detaille').hide();
				$('.tab-tireurs-detail').hide();
				$('.tab-score').show();
				$('#mode_tab').attr('value', 'score');
			} else {
				$('.tab-score').hide();
				$('.tab-detaille').show();
				$('.tab-tireurs-detail').show();
				$('#mode_tab').attr('value', 'detaille');
			}

			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == mode){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		});

		$('#score_tab_joueur1').on('click blur', function(){
			actionTAB($(this).val(), $('.list-tireurs-defieur > div').length, 'list-tireurs-defieur', 'tireurs-domicile', 'dom', $('#form_equipe_defieur').val());
		});

		$('#score_tab_joueur2').on('click blur', function(){
			actionTAB($(this).val(), $('.list-tireurs-defier > div').length, 'list-tireurs-defier', 'tireurs-exterieur', 'ext', $('#form_equipe_defier').val());
		});

		function actionTAB (score, nb_element, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score == nb_element){
				return false;
			} else if (score < nb_element){
				for (var i = nb_element; i > score; i--){
					$('.'+listeJoueur).children().each(function(){
						if ($(this).hasClass('tireurs-'+ordre+'-'+i) || i == 1){
							$(this).remove();
						}
					});
				}
			} else {
				for (var i = nb_element+1; i <= score; i++){
					afficherChoixTireurs(i, listeJoueur, nomDivGen, ordre, idEquipe);
				}
			}
		}

		function afficherChoixTireurs (score, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score > 0){
				$('.'+listeJoueur).append(
					'<div class="form-group '+nomDivGen+' tireurs-'+ordre+'-'+score+' animated fadeInUp" style="display:none;">'
						+'<div class="col-sm-10">'
							+'<select id="tireurs-'+ordre+'-'+score+'" name="tireurs-'+ordre+'['+score+']" class="tireurs">'
								+'<option></option>'
							+'</select>'
						+'</div>'
						+'<div class="col-sm-2">'
							+'<input type="checkbox" name="tireurs_'+ordre+'_reussite['+score+']" class="tireurs-'+ordre+'_'+score+'">'
						+'</div>'
					+'</div>'
				);

				afficherJoueurs(idEquipe, $('#tireurs-'+ordre+'-'+score));

				$('#tireurs-'+ordre+'-'+score).select2({
					placeholder: "Tireur #"+score,
					width: '140px'
				});

				$('.'+nomDivGen).show();
				$('.'+listeJoueur).show();
			}
		}

		// $('body').on('change', '.tireurs', function(){
		// 	id = $(this).attr('id');
		// 	console.log(id);
		// 	console.log($('#'+id).val());
		// });
	});
</script>