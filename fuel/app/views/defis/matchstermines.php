<?php if ($defis_termines): ?>
	<?php foreach ($defis_termines as $defi): ?>
		<div class="row" style="margin-top:10px;">
			<div class="col-md-6">
				<?php if ($defi['photouser']): ?>
					<?php if ($defi['defi']->defieur->id == \Auth::get('id')): ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
					<?php else: ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>" />
					<?php endif; ?>
				<?php else: ?>
					<?php if ($defi['defi']->defieur->id == \Auth::get('id')): ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
					<?php else: ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>" />
					<?php endif; ?>
				<?php endif; ?>
				<a href="/profil/view/<?= $defi['defi']->defier->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>

				<?php if (($defi['defi']->id_joueur_defier == \Auth::get('id') && $defi['defi']->match_valider2 == 0) || ($defi['defi']->id_joueur_defieur == \Auth::get('id') && $defi['defi']->match_valider1 == 0)): ?>
					<a href="/matchs/view/<?= $defi['defi']->id_match ?>" class="btn btn-warning"><i class="fa fa-exclamation-triangle"></i> N'oubliez pas de valider le match</a>
				<?php endif; ?>
			</div>

			<div class="col-md-4 div_resultat">
				<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe1->championnat->nom)) . '/' . $defi['defi']->match->equipe1->logo ?>" alt="<?= $defi['defi']->match->equipe1->nom ?>" width="30px" />
				<a href="/matchs/view/<?= $defi['defi']->match->id ?>"><div class="score_defis"><?= $defi['defi']->match->score_joueur1 ?> - <?= $defi['defi']->match->score_joueur2 ?></div></a>
				<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe2->championnat->nom)) . '/' . $defi['defi']->match->equipe2->logo ?>" alt="<?= $defi['defi']->match->equipe2->nom ?>" width="30px" />
			</div>

			<div class="col-md-2 div_resultat">
				<?php if ($defi['defi']->defieur->id == \Auth::get('id')): ?>
					<?php if ($defi['defi']->match->score_joueur1 > $defi['defi']->match->score_joueur2): ?>
						<span class="label label-success">Victoire</span>
					<?php elseif ($defi['defi']->match->score_joueur1 == $defi['defi']->match->score_joueur2): ?>
						<span class="label label-default">Nul</span>
					<?php else: ?>
						<span class="label label-danger">Défaite</span>
					<?php endif; ?>
				<?php else: ?>
					<?php if ($defi['defi']->match->score_joueur1 > $defi['defi']->match->score_joueur2): ?>
						<span class="label label-danger">Défaite</span>
					<?php elseif ($defi['defi']->match->score_joueur1 == $defi['defi']->match->score_joueur2): ?>
						<span class="label label-default">Nul</span>
					<?php else: ?>
						<span class="label label-success">Victoire</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>