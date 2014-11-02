<div class="container">
	<?php if (($match->defi->match_valider2 == 0 && (\Auth::get('id') == $match->joueur2->id)) || $match->defi->match_valider1 == 0 && (\Auth::get('id') == $match->joueur1->id)): ?>
		<div class="alert alert-danger alert-no-valid" role="alert">
			<h4><i class="fa fa-exclamation-triangle"></i> Ce match n'est pas encore validé</h4>
			<p>Il ne sera pas publié tant que les deux joueurs ne valident pas le rapport.</p>
			<p>
				<a class="btn btn-success btn-confirm" data-user="<?= \Auth::get('id') ?>" data-match="<?= $match->id ?>"><i class="fa fa-check"></i> Je valide</a>
				<a href="/matchs/modif/<?= $match->id ?>" class="btn btn-danger btn-refuse" data-loading-text="Chargement..."><i class="fa fa-exclamation"></i> Je veux modifier ce rapport, car il y a une erreur</a>
			</p>
		</div>
	<?php elseif (!$match_valider): ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> Votre adversaire n'a pas encore validé ce match.</div>
	<?php endif; ?>


	<div class="panel panel-default">
		<div class="panel-heading">
			<strong><i class="fa fa-futbol-o"></i> Rapport de match</strong>
		</div>

		<div class="panel-body">
			<?= render('matchs/socialnetworks', array('match' => $match, 'jaime' => $jaime, 'commentaires' => $commentaires)); ?>

			<div class="row">
				<!-- DEFIEUR -->
				<div class="col-md-4 center-block center">
					<div class="thumbnail-profil">
						<?php if ($photo_defieur): ?>
							<a href="/profil/view/<?= $match->joueur1->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $match->joueur1->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" /></a>
						<?php else: ?>
							<a href="/profil/view/<?= $match->joueur1->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->joueur1->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" /></a>
						<?php endif; ?>
					</div>
					<p class="username"><strong><?= $match->joueur1->username ?></strong></p>
					<?= html_entity_decode($derniers_matchs_1) ?><br>
					<a href="/profil/view/<?= $match->joueur1->id ?>" class="btn btn-primary" style="margin-top:10px;">Voir son profil</a>
				</div>

				<!-- SCORE -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-6 club match_club_defieur">
							<a href="/club/view/<?= $match->equipe1->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" alt="<?= $match->equipe1->nom ?>" width="100px" class="logo_club_defier" /></a>
						</div>
						<div class="col-md-6 club match_club_defier">
							<a href="/club/view/<?= $match->equipe2->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" alt="<?= $match->equipe2->nom ?>" width="100px" class="logo_club_defier" /></a>
						</div>
					</div>

					<div class="score center-block center">
						<h1 class="score_defis"><?= $match->score_joueur1 ?> - <?= $match->score_joueur2 ?></h1>
						<?php if ($match->prolongation == 1 && $match->id_tab == 0): ?>
							<h4>après prolongation</h4>
						<?php endif; ?>

						<?php if ($match->id_tab != 0): ?>
							<h4><?= $match->tab->score_joueur1 ?> TAB <?= $match->tab->score_joueur2 ?></h4>
						<?php endif; ?>
					</div>
				</div>


				<!-- DEFIER -->
				<div class="col-md-4 center-block center">
					<div class="thumbnail-profil">
						<?php if ($photo_defier): ?>
							<a href="/profil/view/<?= $match->joueur2->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $match->joueur2->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" /></a>
						<?php else: ?>
							<a href="/profil/view/<?= $match->joueur2->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->joueur2->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" /></a>
						<?php endif; ?>
					</div>
					<p class="username"><strong><?= $match->joueur2->username ?></strong></p>
					<?= html_entity_decode($derniers_matchs_2) ?><br>
					<a href="/profil/view/<?= $match->joueur2->id ?>" class="btn btn-primary" style="margin-top:10px;">Voir son profil</a>
				</div>
			</div>

			<!-- TIMELINE -->
			<?php if ($buteurs || $match->tab): ?>
				<?= render('matchs/timeline', array('match' => $match, 'buteurs' => $buteurs, 'tireurs' => $tireurs)); ?>
			<?php endif; ?>

			<?php if ($match->score_joueur1 > $match->score_joueur2): ?>
				<i class="fa fa-thumbs-up"></i> Bravo à <?= $match->joueur1->username ?>
			<?php elseif ($match->score_joueur2 > $match->score_joueur1): ?>
				<i class="fa fa-thumbs-up"></i> Bravo à <?= $match->joueur2->username ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ($match_valider): ?>
		<!-- COMMENTAIRES -->
		<?= render('matchs/commentaires', array('match' => $match, 'commentaires' => $commentaires)); ?>
	<?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        		<h4 class="modal-title" id="myModalLabel">Player qui applaudissent ce match</h4>
      		</div>
      		<div class="modal-body">
      			<section class="table-responsive">
      				<table class="table table-hover">
      					<tbody class="modal-table"></tbody>
      				</table>
      			</section>
      		</div>
    	</div>
  	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.btn-like').on('click', function(){
			match = $(this).attr('data-match');
			$.ajax({
				url : window.location.origin + '/matchs/api/like.json',
				data: 'match='+match,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						$('.btn-like').attr('disabled', 'disabled');
						var nb = $('.nb_like').attr('data-like');
						console.log(nb);
						$('.btn-jaime').contents().filter(function(){
							return this.nodeType === 3;
						}).remove();
						$('.btn-jaime').append((parseInt(nb)+1)+' Vous applaudissez.');
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		// Player qui likent le match
		$('.btn-jaime').on('click', function(){
			match = $('.btn-like').attr('data-match');
			$.ajax({
				url : window.location.origin + '/matchs/api/playerswhoslike.json',
				data: 'match='+match,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						$('.modal-table').html('');
						user = data;
						if (user == ''){
							$('.modal-table').append('Personne n\'applaudit ce match pour l\'instant. Soyez le premier !');
						} else {
							for (var i in user){
								if (user[i]['photouser']){
									var photo = user[i]['photouser']['photo'];
								} else var photo = 'notfound.png';

								$('.modal-table').append(
									'<tr>'
										+'<td><img src="<?= \Uri::base() . \Config::get("users.photo.path") ?>'+photo+'" width="50" /></td>'
										+'<td>'+user[i]['user']['username']+'</td>'
										+'<td><a href="/profil/view/'+user[i]['user']['id']+'" class="btn btn-success"><i class="fa fa-eye"></i> Voir son profil</a></td>'+
									+'</tr>'	
								);
							}
						}
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

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
						if (data.photouser == undefined){
							photo = window.location.origin+'/upload/photo_user/notfound.png';
						} else photo = window.location.origin+'/upload/photo_user/'+data.photouser.photo;

						$('.media-list').prepend(
							'<li class="media">'
								+'<a class="pull-left" href="/profil/view/'+data.user.id+'">'
									+ '<img src="'+photo+'" alt="'+data.user.username+'" width="64px" >'
								+'</a>'
								+'<div class="media-body">'
									+'<h4 class="media-heading">'+data.user.username+'<small> à l\'instant</small></h4>'
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

		$('.btn-confirm').on('click', function(){
			user = $(this).attr('data-user');
			match = $(this).attr('data-match');
			$.ajax({
				url : window.location.origin + '/defis/api/validMatch.json',
				data: 'user='+user+'&match='+match,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					if (data == 'OK'){
						$('.alert-no-valid').remove();
						$('.container').prepend(
							'<div class="alert alert-success" role="alert">'
								+'<h4><i class="fa fa-check"></i> Le match est validé !</h4>'
								+'<p>L\'autre joueur va recevoir une notification l\'informant que le match est désormais publié.</p>'
							+'</div>'
						);
					}
				},
				error: function(){
					alert('Une erreur est survenue pendant la validation du match');
				},
			});
		});

		$('.btn-refuse').on('click', function(){
			$(this).button('loading');
		})
	});
</script>
<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.0";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<?= \Asset::js('timeline/timeline_match.js'); ?>

		