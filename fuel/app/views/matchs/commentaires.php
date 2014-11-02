<div id="panel-commentaires" class="panel panel-default">
	<div class="panel-heading">
		<strong><i class="fa fa-comments-o"></i> Commentaires</strong>
	</div>
	<div class="panel-body">
		<textarea row="4" cols="50" id="nouv_commentaire" placeholder="Votre commentaire..."></textarea>
		<input type="hidden" id="user_comm" value="<?= \Auth::get('id') ?>">
		<input type="hidden" id="match_comm" value="<?= $match->id ?>">
		<a class="btn btn-primary btn-commentaire" style="margin-top:20px">Envoyer</a>
		<hr>
		
		<div id="commentaires">
			<ul class="media-list">
				<?php if ($commentaires): ?>
					<?php foreach ($commentaires as $commentaire): ?>
						<li class="media">
							<a class="pull-left" href="/profil/view/<?= $commentaire['commentaire']->user->id ?>">
								<?php if ($commentaire['photouser']): ?>
									<img class="media-object" src="<?= \Uri::base() . \Config::get('users.photo.path') . '/' . $commentaire['photouser']->photo ?>" alt="<?= $commentaire['commentaire']->user->username ?>" width="64px">
								<?php else: ?>
									<img class="media-object" src="<?= \Uri::base() . \Config::get('users.photo.path') . '/notfound.png' ?>" alt="<?= $commentaire['commentaire']->user->username ?>" width="64px">
								<?php endif; ?>
							</a>
							<div class="media-body">
								<h4 class="media-heading"><?= $commentaire['commentaire']->user->username ?><small> le <?= date('d/m/Y à H:i', $commentaire['commentaire']->created_at) ?></small></h4>
								<?= $commentaire['commentaire']->commentaire ?>
							</div>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<!-- SI PAS DE COMMENTAIRE -->
					<div class="alert alert-info">Pas encore de commentaires sur ce match. Soyez le premier !</div>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>