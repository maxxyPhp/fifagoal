<div class="container">
	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>

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
					<?php foreach ($matchs as $match): ?>
						<div class="row" style="margin-bottom:15px;">
							<div class="col-md-4">
								<?php if ($match['photouser1']): ?>
									<a href="/profil/view/<?= $match['match']->defi->defieur->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $match['photouser1']->photo ?>" alt="<?= $match['match']->defi->defieur->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['match']->defi->defieur->username ?>" class="img-circle photos_derniers_match_accueil"/></a>
								<?php else: ?>
									<a href="/profil/view/<?= $match['match']->defi->defieur->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match['match']->defi->defieur->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['match']->defi->defieur->username ?>" class="img-circle photos_derniers_match_accueil"/></a>
								<?php endif; ?>
							</div>
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['match']->equipe1->championnat->nom)) . '/' . $match['match']->equipe1->logo ?>" alt="<?= $match['match']->equipe1->nom ?>" width="40" style="margin-right:15px;" />
								<a href="/matchs/view/<?= $match['match']->id ?>" class="score_matchs"><?= $match['match']->score_joueur1 ?> - <?= $match['match']->score_joueur2 ?></a>
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['match']->equipe2->championnat->nom)) . '/' . $match['match']->equipe2->logo ?>" alt="<?= $match['match']->equipe2->nom ?>" width="40" style="margin-left:15px;" />
							</div>
							<div class="col-md-4">
								<?php if ($match['photouser2']): ?>
									<a href="/profil/view/<?= $match['match']->defi->defier->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $match['photouser2']->photo ?>" alt="<?= $match['match']->defi->defier->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['match']->defi->defier->username ?>" class="img-circle photos_derniers_match_accueil" style="float:right;"/></a>
								<?php else: ?>
									<a href="/profil/view/<?= $match['match']->defi->defier->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match['match']->defi->defier->username ?>" width="64" height="64" data-toggle="tooltip" data-placement="top" title="<?= $match['match']->defi->defier->username ?>" class="img-circle photos_derniers_match_accueil" style="float:right;"/></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div><!-- derniers matchs -->

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">FIFA 15 Soundtrack</div>
				<div class="panel-body">
					<iframe src="https://embed.spotify.com/?uri=https://play.spotify.com/user/easportsaudio/playlist/00i82lDzMDdiHWNjrIGAyw" width="300" height="380" frameborder="0" allowtransparency="true"></iframe>
				</div>
			</div>
		</div>
<?php //var_dump($buteurs);die(); ?>
		<div class="col-md-2">
			<div class="panel panel-default">
				<div class="panel-heading">Top Buteurs</div>
				<div class="panel-body">
					<ul class="media-list">
						<?php foreach ($buteurs as $buteur): ?>
							<li class="media">
								<a class="media-left">
									<?php if ($buteur->photo): ?>
										<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($buteur->nomc)) . '/' . str_replace(' ', '_', strtolower($buteur->nome)) . '/' . $buteur->photo ?>" alt="<?= strtoupper($buteur->nomj).' '.ucfirst($buteur->prenom) ?>" width="50" data-toggle="tooltip" data-placement="top" title="<?= strtoupper($buteur->nomj).' '.ucfirst($buteur->prenom) ?> (<?= $buteur->nome ?>)" class="top_buteurs">
									<?php else: ?>
										<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/notfound.png' ?>" alt="<?= strtoupper($buteur->nomj).' '.ucfirst($buteur->prenom) ?>" width="40" data-toggle="tooltip" data-placement="top" title="<?= strtoupper($buteur->nomj).' '.ucfirst($buteur->prenom)?> (<?= $buteur->nome ?>)" class="top_buteurs">
									<?php endif; ?>
								</a>
								<div class="media-body" style="float:right;margin-top:10px;">
									<?= $buteur->nb ?> buts
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.photos_derniers_match_accueil, .top_buteurs').tooltip();
	});
</script>
