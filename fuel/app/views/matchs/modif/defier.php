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
			<?php if ($buteur->joueur->equipe->id == $match->equipe2->id || (!empty($buteur->joueur->selection) && $buteur->joueur->selection->id == $match->equipe2->id)): ?>
				<?php $i++; ?>
				<div class="form-group animated fadeInUp buteurs-exterieur buteurs-ext-<?= $i ?>">
					<div class="col-sm-8">
						<select id="buteurs-ext-<?= $i ?>" name="buteurs-ext[<?= $i ?>]" class="buteurs">
							<option></option>
							<?php if ($match->equipe2->isSelection == 0): ?>
								<?php foreach ($match->equipe2->joueurs as $joueur): ?>
									<?php if ($buteur->joueur->id == $joueur->id): ?>
										<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php else: ?>
										<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else: ?>
								<?php foreach($match->equipe2->selectionne as $joueur): ?>
									<?php if ($buteur->joueur->id == $joueur->id): ?>
										<option value="<?= $joueur->id ?>" selected><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php else: ?>
										<option value="<?= $joueur->id ?>"><?= strtoupper($joueur->nom) . ' '.ucfirst($joueur->prenom) ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
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