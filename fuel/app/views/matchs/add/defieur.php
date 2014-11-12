<div class="thumbnail-profil">
	<?php if ($photo_defieur): ?>
		<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $defi->defieur->username ?>" class="img-thumbnail img-responsive center-block img-profil-rapport animated fadeInUp" width="120px" />
	<?php else: ?>
		<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi->defieur->username ?>" class="img-thumbnail img-responsive center-block img-profil-rapport animated fadeInUp" width="120px" />
	<?php endif; ?>
</div>	
<input type="hidden" name="joueur1" value="<?= $defi->defieur->id ?>">

<!-- Div championnat -->
<div class="form-group animated fadeInUp">
	<div class="col-sm-10">
		<select id="form_championnat_defieur">
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

<!-- Div équipes -->
<div class="form-group" style="display:none;" id="div_equipes_defieur">
	<div class="col-sm-10">
		<select id="form_equipe_defieur" name="id_equipe_defieur">
			<option></option>
		</select>
	</div>
</div>

<!-- Div buteurs -->
<div class="list-buteurs-defieur" style="display:none;"></div>

<div class="form-group" style="display:none;" id="div_joueurs_defieur">
	<div class="col-sm-10">
		<select id="form_joueur_defieur" name="id_joueur_defieur">
			<option></option>
		</select>
	</div>
</div>