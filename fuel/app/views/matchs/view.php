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

	<!-- COMMENTAIRES -->
	<div class="panel panel-default">
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
								<a class="pull-left" href="/profil/view/<?= $commentaire['user']['id']	 ?>">
									<img class="media-object" src="<?= \Uri::base() . \Config::get('users.photo.path') . '/' . $commentaire['photouser']['photo'] ?>" alt="<?= $commentaire['user']['username'] ?>" width="64px">
								</a>
								<div class="media-body">
									<h4 class="media-heading"><?= $commentaire['user']['username'] ?></h4>
									<?= $commentaire['commentaire']['commentaire'] ?>
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
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#nouv_commentaire').redactor();

		$('.btn-commentaire').on('click', function(){
			content = $('#nouv_commentaire').getText();
			if (content == '') return false;
			$.ajax({
				url: window.location.origin + '/matchs/api/addComment.json',
				data: 'match='+$('#match_comm').val()+'&user='+$('#user_comm').val()+'&content='+content,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					if (data != 'KO'){
						console.log(data.commentaire);
						var photo;
						if (data.photouser == ''){
							photo = window.location.origin+'/upload/photo_user/notfound.png';
						} else photo = window.location.origin+'/upload/photo_user/'+data.photouser.photo;

						$('.media-list').prepend(
							'<li class="media">'
								+'<a class="pull-left" href="/profil/view/'+data.user.id+'">'
									+ '<img src="'+photo+'" alt="'+data.user.username+'" width="64px" >'
								+'</a>'
								+'<div class="media-body">'
									+'<h4 class="media-heading">'+data.user.username+'</h4>'
									+ data.commentaire
								+'</div>'
							+'</li>'
						);

						$('.alert-info').remove();
					} else alert('Une erreur est survenue pendant la sauvegarde du commentaire');
				},
				error: function(){
					alert('Une erreur est survenue. Réésayez ultérieurement.');
				},
			});
		});
	});
</script>