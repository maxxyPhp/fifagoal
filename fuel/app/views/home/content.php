<div class="container">
	<div class="well">
		<div class="row">
			<div class="col-md-6" style="font-family: 'Ubuntu', sans-serif;">
				<?php $i = rand(1, 4); ?>
				<?php switch ($i){
					case 1:
						echo "<h2>Salut ". \Auth::get('username') ." !</h2><br><p>Prêt à mettre quelques raclées aujourd'hui ?</p>";
						break;
					case 2:
						echo "<h2>Hey ". \Auth::get('username') ." !</h2><br><p>Un coup-franc pleine lucarne ou un peno foireux aujourd'hui ?</p>";
						break;
					case 3:
						echo "<h2>Hello ".\Auth::get('username')." !</h2><br><p>On monte quelques places au classement aujourd'hui ?</p>";
						break;
					case 4:
						echo "<h2>Salut vieille branche !</h2><br><p>Tu nous avais manquer !</p>";
						break;
				} ?>
			</div>

			<div class="col-md-6">
				<?php if ($photo): ?>
					<a href="/profil"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo->photo ?>" alt="<?= \Auth::get('username') ?>" width="100" class="photo_accueil img-responsive" title="Mon profil" /></a>
				<?php else: ?>
					<a href="/profil"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= \Auth::get('username') ?>" width="100" class="photo_accueil img-responsive" /></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<?php if ($matchs): ?>
			<div class="panel panel-default">
				<div class="panel-heading">Les derniers matchs joués</div>
				<div class="panel-body">
					<?php //var_dump($matchs);die(); ?>
					<?php foreach ($matchs as $match): ?>
						<div class="row" style="margin-bottom:15px;">
							<div class="col-md-4">
								<?php if ($match['photouser1']): ?>
									<a href="/profil/view/<?= $match['defieur']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $match['photouser1']->photo ?>" alt="<?= $match['defieur']->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['defieur']->username ?>" class="img-circle photos_derniers_match_accueil"/></a>
								<?php else: ?>
									<a href="/profil/view/<?= $match['defieur']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match['defieur']->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['defieur']->username ?>" class="img-circle photos_derniers_match_accueil"/></a>
								<?php endif; ?>
							</div>
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['match']->equipe1->championnat->nom)) . '/' . $match['match']->equipe1->logo ?>" alt="<?= $match['match']->equipe1->nom ?>" width="40" style="margin-right:15px;" />
								<a href="/matchs/view/<?= $match['match']->id ?>" class="score_matchs"><?= $match['match']->score_joueur1 ?> - <?= $match['match']->score_joueur2 ?></a>
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['match']->equipe2->championnat->nom)) . '/' . $match['match']->equipe2->logo ?>" alt="<?= $match['match']->equipe2->nom ?>" width="40" style="margin-left:15px;" />
							</div>
							<div class="col-md-4">
								<?php if ($match['photouser2']): ?>
									<a href="/profil/view/<?= $match['defier']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $match['photouser2']->photo ?>" alt="<?= $match['defier']->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['defier']->username ?>" class="img-circle photos_derniers_match_accueil" style="float:right;"/></a>
								<?php else: ?>
									<a href="/profil/view/<?= $match['defier']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match['defier']->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['defier']->username ?>" class="img-circle photos_derniers_match_accueil" style="float:right;"/></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.photos_derniers_match_accueil').tooltip();
	});
</script>
