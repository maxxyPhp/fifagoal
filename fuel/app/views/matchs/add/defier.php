<div class="thumbnail-profil">
	<?php if ($photo_defier): ?>
		<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $defi->defier->username ?>" class="img-thumbnail img-responsive center-block img-profil-rapport animated fadeInUp" width="120px" />
	<?php else: ?>
		<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi->defier->username ?>" class="img-thumbnail img-responsive center-block img-profil-rapport animated fadeInUp" width="120px" />
	<?php endif; ?>
</div>
<input type="hidden" name="joueur2" value="<?= $defi->defier->id ?>">

<!-- Div championnat -->
<div class="form-group animated fadeInUp center-block">
	<div class="col-sm-10">
		<select id="form_championnat_defier">
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

<!-- Div equipes -->
<div class="form-group" style="display:none;" id="div_equipes_defier">
	<div class="col-sm-10">
		<select id="form_equipe_defier" name="id_equipe_defier">
			<option></option>
		</select>
	</div>
</div>

<!-- Div buteurs -->
<div class="list-buteurs-defier" style="display:none;"></div>

<div class="form-group" style="display:none;" id="div_joueurs_defier">
	<div class="col-sm-10">
		<select id="form_joueur_defier" name="id_joueur_defier">
			<option></option>
		</select>
	</div>
</div>