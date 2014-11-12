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
				<div class="row tab-score" style="display:none;">
					<div class="col-md-6">
						<input type="number" class="form-control" id="tab_joueur1" name="tab_joueur_1" min="3" max="20" placeholder="Score J1">
					</div>
					<div class="col-md-6">
						<input type="number" class="form-control" id="tab_joueur2" name="tab_joueur_2" min="3" max="20" placeholder="Score J2">
					</div>
				</div>

				<!-- DETAILLE -->
				<div class="row tab-detaille">
					<div class="col-md-6">
						<input type="number" class="form-control" id="score_tab_joueur1" name="score_tab_joueur_1" min="3" max="20" value="<?php if (!empty($nb_tireurs_dom)): echo $nb_tireurs_dom; endif; ?>" placeholder="Nb tirs" data-toggle="popover" data-trigger="focus" title="Attention" data-content="Les cases des TAB permettent d'indiquer le nombre de tirs de chaque équipe, et non le score. Vous pourrez ensuite indiquer si certains joueurs ont loupés leurs tirs ou non. Il faut minimum trois tirs pour gagner une séance de TAB.">
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
								<?php if ($tireur->joueur->equipe->id == $match->id_equipe1 || (!empty($tireur->joueur->selection) && $tireur->joueur->selection->id == $match->id_equipe1)): ?>
									<?php $i++; ?>
									<div class="form-group tireurs-domicile tireurs-dom-<?= $i ?> animated fadeInUp">
										<div class="col-sm-10">
											<select id="tireurs-dom-<?= $i ?>" name="tireurs-dom[<?= $i ?>]" class="tireurs">
												<option></option>
												<?php if ($match->equipe1->isSelection == 0): ?>
													<?php foreach ($match->equipe1->joueurs as $joueur): ?>
														<?php if ($joueur->id == $tireur->id_joueur): ?>
															<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php else: ?>
															<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php else: ?>
													<?php foreach ($match->equipe1->selectionne as $joueur): ?>
														<?php if ($joueur->id == $tireur->id_joueur): ?>
															<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php else: ?>
															<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endif; ?>
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
								<?php if ($tireur->joueur->equipe->id == $match->id_equipe2 || (!empty($tireur->joueur->selection) && $tireur->joueur->selection->id == $match->id_equipe2)): ?>
									<?php $i++; ?>
									<div class="form-group tireurs-exterieur tireurs-ext-<?= $i ?> animated fadeInUp">
										<div class="col-sm-10">
											<select id="tireurs-ext-<?= $i ?>" name="tireurs-ext[<?= $i ?>]" class="tireurs">
												<option></option>
												<?php if ($match->equipe2->isSelection == 0): ?>
													<?php foreach ($match->equipe2->joueurs as $joueur): ?>
														<?php if ($joueur->id == $tireur->id_joueur): ?>
															<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php else: ?>
															<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php else: ?>
													<?php foreach ($match->equipe2->selectionne as $joueur): ?>
														<?php if ($joueur->id == $tireur->id_joueur): ?>
															<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php else: ?>
															<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) ?>  <?= ucfirst($joueur->prenom) ?></option>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endif; ?>
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