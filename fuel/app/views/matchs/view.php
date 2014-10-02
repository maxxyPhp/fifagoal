<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong><i class="fa fa-futbol-o"></i> Rapport de match</strong>
		</div>

		<div class="panel-body">

			<!-- DEFIEUR -->
			<div class="col-md-4 center-block center">
				<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $defieur->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<p class="username"><strong><?= $defieur->username ?></strong></p>
				<?= html_entity_decode($derniers_matchs_1) ?><br>
				<a href="/profil/view/<?= $defieur->id ?>" class="btn btn-primary" style="margin-top:10px;">Voir son profil</a>
			</div>

			<!-- SCORE -->
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6 club match_club_defieur">
						<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . $championnat1 . '/' . $equipe1->logo ?>" alt="<?= $equipe1->nom ?>" width="100px" class="logo_club_defier" />
					</div>
					<div class="col-md-6 club match_club_defier">
						<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . $championnat2 . '/' . $equipe2->logo ?>" alt="<?= $equipe2->nom ?>" width="100px" class="logo_club_defier" />
					</div>
				</div>

				<div class="score center-block center">
					<h1 class="score_defis"><?= $match->score_joueur1 ?> - <?= $match->score_joueur2 ?></h1>
				</div>
			</div>


			<!-- DEFIER -->
			<div class="col-md-4 center-block center">
				<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $defier->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<p class="username"><strong><?= $defier->username ?></strong></p>
				<?= html_entity_decode($derniers_matchs_2) ?><br>
				<a href="/profil/view/<?= $defier->id ?>" class="btn btn-primary" style="margin-top:10px;">Voir son profil</a>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<strong><i class="fa fa-comments-o"></i> Commentaires</strong>
		</div>
		<div class="panel-body">
			<?php if ($commentaires): ?>

			<?php else: ?>
				<div class="alert alert-info">Pas encore de commentaires sur ce match. Soyez le premier !</div>
				<textarea row="4" cols="50" id="nouv_commentaire" placeholder="Votre commentaire..."></textarea>
				<a class="btn btn-primary btn-commentaire" style="margin-top:20px">Envoyer</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#nouv_commentaire').redactor();
	});
</script>