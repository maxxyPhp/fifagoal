<?php if ($defis_acp): ?>
	<?php foreach ($defis_acp as $defi): ?>
		<div class="row" style="margin-top:10px;">
			<div class="col-md-6">
				<?php if ($defi['photouser'] == null): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>"/>
				<?php endif; ?>
				<a href="/profil/view/<?= $defi['defi']->defieur->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
				Vous avez accepté le défi de <?= $defi['defi']->defieur->username ?>
			</div>

			<div class="col-md-6">

				<?php if ($defi['defi']->id_match != 0): ?>
					<div class="row">
						<div class="col-md-8">
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe1->championnat->nom)) . '/' . $defi['defi']->match->equipe1->logo ?>" alt="<?= $defi['defi']->match->equipe1->nom ?>" width="30px" />
							</div>
							<div class="col-md-4">
								<div class="score_defis"><?= $defi['defi']->match->score_joueur1 ?> - <?= $defi['defi']->match->score_joueur2 ?></div>
							</div>
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe2->championnat->nom)) . '/' . $defi['defi']->match->equipe2->logo ?>" alt="<?= $defi['defi']->match->equipe2->nom ?>" width="30px" />
							</div>
						</div>
						<div class="col-md-4">
							<a class="btn btn-primary" href="/matchs/view/<?= $defi['defi']->id_match ?>">Voir le rapport du match</a>
						</div>
					</div>
				<?php else: ?>
					<a class="btn btn-primary btn-rapport" data-defi="<?= $defi['defi']->id ?>">Faire le rapport du match</a>
					<form id="form-rapport-<?= $defi['defi']->id ?>" action="/matchs/add" method="post">
						<input type="hidden" name="defi" value="<?= $defi['defi']->id ?>" />
					</form>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach ?>
<?php else: ?>
	<div class="alert alert-warning" style="margin-top:10px;"><i class="fa fa-frown-o"></i> Vous n'avez pas encore accepté de défis</div>
<?php endif; ?>