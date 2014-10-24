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
			<?php if ($match->defi->match_valider1 == 1 && $match->defi->match_valider2 == 1): ?>
				<div id="fb-root"></div>
				<div class="row">
					<div class="col-md-2">
						<?php if (!$jaime): ?>
							<a class="btn btn-default btn-like" data-match="<?= $match->id ?>" style="margin-left:50px;"><i class="fa fa-thumbs-o-up"></i> J'applaudis</a>
						<?php else: ?>
							<a class="btn btn-default btn-like" data-match="<?= $match->id ?>" disabled="disabled" style="margin-left:50px;"><i class="fa fa-thumbs-o-up"></i> J'applaudis</a>
						<?php endif; ?>
					</div>
					<div class="col-md-10 nb_like" data-like="<?= count($match->like) ?>">
						<a class="btn-jaime" data-toggle="modal" data-target="#myModal"><i class="fa fa-thumbs-up"></i> <?= count($match->like) ?><?php if ($jaime): ?>  Vous applaudissez.<?php endif; ?></a>
						<a href="#panel-commentaires" style="margin-left:30px;"><i class="fa fa-comments"></i> <?= count($commentaires) ?></a>
						<div style="margin-left:20px;" class="fb-share-button" data-href="<?= \Uri::base() ?>matchs/view/<?= $match->id ?>" data-layout="button_count"></div>
						<div class="bouton-twitter">
							<a href="https://twitter.com/share" class="twitter-share-button" data-lang="fr" data-hashtags="FIFAGOAL">Tweeter</a>
						</div>
					</div>
				</div>
				<hr>
			<?php endif; ?>

			<div class="row">
				<!-- DEFIEUR -->
				<div class="col-md-4 center-block center">
					<div class="thumbnail-profil">
						<?php if ($photo_defieur): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defieur->photo ?>" alt="<?= $match->joueur1->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->joueur1->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
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
							<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" alt="<?= $match->equipe1->nom ?>" width="100px" class="logo_club_defier" />
						</div>
						<div class="col-md-6 club match_club_defier">
							<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" alt="<?= $match->equipe2->nom ?>" width="100px" class="logo_club_defier" />
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
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $photo_defier->photo ?>" alt="<?= $match->joueur2->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
						<?php else: ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $match->joueur2->username ?>" class="img-thumbnail center-block img-profil-rapport animated fadeInUp" width="120px" />
						<?php endif; ?>
					</div>
					<p class="username"><strong><?= $match->joueur2->username ?></strong></p>
					<?= html_entity_decode($derniers_matchs_2) ?><br>
					<a href="/profil/view/<?= $match->joueur2->id ?>" class="btn btn-primary" style="margin-top:10px;">Voir son profil</a>
				</div>
			</div>

			<!-- TIMELINE -->
			<?php if ($buteurs || $match->tab): ?>
			
				<div class="row" style="margin-top:20px;">
					<div id="ss-links" class="ss-links">
						<a href="#match">M</a>
						<?php if ($match->prolongation == 1): ?>
							<a href="#prolong">Pr</a>
						<?php endif; ?>

						<?php if ($match->id_tab != 0): ?>
							<a href="#tab">TAB</a>
						<?php endif; ?>
					</div>

					<div id="ss-container" class="ss-container">
						<div class="ss-row">
		                    <div class="ss-left">
		                        <h2 id="match">Début</h2>
		                    </div>
		                    <div class="ss-right">
		                        <h2>du match</h2>
		                    </div>
	                	</div>

	                	<div class="ss-row ss-medium">
		                    <div class="ss-left">
		                       <div class="ss-circle">
		                       		<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" alt="<?= $match->equipe1->nom ?>" width="90" class="timeline_logo_club">
		                       </div>
		                    </div>
		                    <div class="ss-right">
		                         <div class="ss-circle">
		                         	<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" alt="<?= $match->equipe2->nom ?>" width="90" class="timeline_logo_club">
		                         </div>
		                    </div>
	                	</div>
	                	<?php $b = $buteurs; ?>
	                	<?php $i = 0; ?>
						<?php foreach ($buteurs as $buteur):  ?>
							<?php $i++; ?>
		
							<?php if ($i > 1 && $buteur->minute > 90 && !($b->minute > 90) || ($i == 1 && $buteur->minute > 90)): ?>
								<?php if ($buteur->minute > 90): ?>
									<div class="ss-row">
					                    <div class="ss-left">
					                        <h2 id="prolong">Début de la</h2>
					                    </div>
					                    <div class="ss-right">
					                        <h2>prolongation</h2>
					                    </div>
				                	</div>
								<?php endif; ?>
							<?php endif; ?>
							<?php $b = $buteur; ?>

							<?php if ($buteur->joueur->equipe->id == $match->equipe1->id): ?>
								<div class="ss-row ss-medium">
									<div class="ss-left">
				                        <div class="ss-circle">
				                        	<?php if ($buteur->joueur->photo): ?>
				                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe1->nom)) . '/' . $buteur->joueur->photo ?>" width="100"/><br>
				                        	<?php else: ?>
				                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
				                        	<?php endif; ?>
				                        	<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" width="50" class="logo_buteurs"><br>
				                        	<?php foreach ($buteur->joueur->pays as $pays): ?>
				                        		<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
				                        	<?php endforeach; ?>
				                        	<div class="label label-<?= $buteur->joueur->poste->couleur ?>"><?= $buteur->joueur->poste->nom ?></div>
				                        </div>
				                    </div>
				                    <div class="ss-right">
				                        <h3>
				                            <span><i class="fa fa-futbol-o"></i> GOAL</span>
				                            <div class="buteurs_view"><?= ucfirst($buteur->joueur->prenom) .' '. strtoupper($buteur->joueur->nom). ' ! '?><small><?= '(' . $buteur->minute .'ème)' ?></small></div>
				                        </h3>
				                    </div>
								</div>
							<?php else: ?>
								<div class="ss-row ss-medium">
									<div class="ss-left">
										<h3>
				                            <span>GOAL <i class="fa fa-futbol-o"></i></span>
				                            <div class="buteurs_view"><?= ucfirst($buteur->joueur->prenom) .' '. strtoupper($buteur->joueur->nom). ' ! '?><small><?= '(' . $buteur->minute .'ème)' ?></small></div>
				                        </h3>
				                    </div>
				                    <div class="ss-right">
				                        <div class="ss-circle">
				                        	<?php if ($buteur->joueur->photo): ?>
					                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe2->nom)) . '/' . $buteur->joueur->photo ?>" width="100"/><br>
					                        <?php else: ?>
				                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
				                        	<?php endif; ?>
					                        <img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" width="50" class="logo_buteurs"><br>
					                       	<?php foreach ($buteur->joueur->pays as $pays): ?>
					                        	<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
					                        <?php endforeach; ?>
					                        <div class="label label-<?= $buteur->joueur->poste->couleur ?>"><?= $buteur->joueur->poste->nom ?></div>
				                   		</div>
				                    </div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>

						<?php if ($match->tab): ?>
							<div class="ss-row">
			                    <div class="ss-left">
			                        <h2 id="tab">Tirs aux</h2>
			                    </div>
			                    <div class="ss-right">
			                        <h2>buts</h2>
			                    </div>
		                	</div>
		                	<?php $i = 0; ?>
		                	<?php foreach ($tireurs as $i => $tireur): ?>
		                		<?php $i++; ?>
		                		<?php if ($i == 11): ?>
		                			<div class="ss-row">
					                    <div class="ss-left">
					                        <h2>Mort</h2>
					                    </div>
					                    <div class="ss-right">
					                        <h2>subite</h2>
					                    </div>
				                	</div>
		                		<?php endif; ?>
		                			<div class="ss-row ss-small">
		                			<?php if ($tireur->joueur->equipe->id == $match->id_equipe1): ?>
					                    <div class="ss-left">
					                    	<?php if ($tireur->reussi == 1): ?>
					                    		<div class="ss-circle">
					                    	<?php else: ?>
					                    		<div class="ss-circle">
					                    	<?php endif; ?>
					                        	<?php if ($tireur->joueur->photo): ?>
						                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe1->nom)) . '/' . $tireur->joueur->photo ?>" width="100"/><br>
						                        <?php else: ?>
					                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
					                        	<?php endif; ?>
					                   		</div>
					                    </div>
					                    <div class="ss-right">
					                    	<h3>
					                    		<div class="row">
					                    			<div class="col-md-1">
						                    			<?php if ($tireur->reussi == 1): ?>
						                    				<div class="tab-circle-ok"></div>
						                    			<?php else: ?>
						                    				<div class="tab-circle-rate"></div>
						                    			<?php endif; ?>
					                    			</div>
					                    			<div class="col-md-10"><div class="buteurs_view"><?= ucfirst($tireur->joueur->prenom) .' '. strtoupper($tireur->joueur->nom) ?></div></div>
					                    		</div>
					                    	</h3>
					                    </div>
				               	 	<?php else: ?>
				               	 		<div class="ss-left">
				               	 			<h3>
				               	 				<div class="row">
				               	 					<div class="col-md-11"><div class="buteurs_view"><?= ucfirst($tireur->joueur->prenom) .' '. strtoupper($tireur->joueur->nom) ?></div></div>
					                    			<div class="col-md-1">
						                    			<?php if ($tireur->reussi == 1): ?>
						                    				<div class="tab-circle-ok"></div>
						                    			<?php else: ?>
						                    				<div class="tab-circle-rate"></div>
						                    			<?php endif; ?>
					                    			</div>
					               	 			</div>
				               	 			</h3>
				               	 		</div>
					                    <div class="ss-right">
					                        <div class="ss-circle">
					                        	<?php if ($tireur->joueur->photo): ?>
						                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe2->nom)) . '/' . $tireur->joueur->photo ?>" width="100"/><br>
						                        <?php else: ?>
					                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
					                        	<?php endif; ?>
					                   		</div>
					                    </div>
					                <?php endif; ?>				            
			                	</div>
		                	<?php endforeach; ?>
		                	<div class="ss-row">
			                    <div class="ss-left">
			                    	<?php if ($match->tab->score_joueur1 > $match->score_joueur2): ?>
			                    		<h2><?= $match->equipe1->nom ?> l'emporte</h2>
			                    	<?php else: ?>
			                        	<h2><?= $match->equipe2->nom ?> l'emporte</h2>
			                        <?php endif; ?>
			                    </div>
			                    <div class="ss-right">
			                        <h2><?= $match->tab->score_joueur1 ?> t.a.b. à <?= $match->tab->score_joueur2 ?></h2>
			                    </div>
		                	</div>
						<?php endif; ?>
						<div class="ss-row">
		                    <div class="ss-left">
		                        <h2 id="november">Fin du</h2>
		                    </div>
		                    <div class="ss-right">
		                        <h2>match</h2>
		                    </div>
	                	</div>
					</div>
				</div><!-- end timeline -->
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
<script type="text/javascript">
		$(function() {

			var $sidescroll	= (function() {
					
					// the row elements
				var $rows			= $('#ss-container > div.ss-row'),
					// we will cache the inviewport rows and the outside viewport rows
					$rowsViewport, $rowsOutViewport,
					// navigation menu links
					$links			= $('#ss-links > a'),
					// the window element
					$win			= $(window),
					// we will store the window sizes here
					winSize			= {},
					// used in the scroll setTimeout function
					anim			= false,
					// page scroll speed
					scollPageSpeed	= 2000 ,
					// page scroll easing
					scollPageEasing = 'easeInOutExpo',
					// perspective?
					hasPerspective	= false,
					
					perspective		= hasPerspective && Modernizr.csstransforms3d,
					// initialize function
					init			= function() {
						
						// get window sizes
						getWinSize();
						// initialize events
						initEvents();
						// define the inviewport selector
						defineViewport();
						// gets the elements that match the previous selector
						setViewportRows();
						// if perspective add css
						if( perspective ) {
							$rows.css({
								'-webkit-perspective'			: 600,
								'-webkit-perspective-origin'	: '50% 0%'
							});
						}
						// show the pointers for the inviewport rows
						$rowsViewport.find('a.ss-circle').addClass('ss-circle-deco');
						// set positions for each row
						placeRows();
						
					},
					// defines a selector that gathers the row elems that are initially visible.
					// the element is visible if its top is less than the window's height.
					// these elements will not be affected when scrolling the page.
					defineViewport	= function() {
					
						$.extend( $.expr[':'], {
						
							inviewport	: function ( el ) {
								if ( $(el).offset().top < winSize.height ) {
									return true;
								}
								return false;
							}
						
						});
					
					},
					// checks which rows are initially visible 
					setViewportRows	= function() {
						
						$rowsViewport 		= $rows.filter(':inviewport');
						$rowsOutViewport	= $rows.not( $rowsViewport )
						
					},
					// get window sizes
					getWinSize		= function() {
					
						winSize.width	= $win.width();
						winSize.height	= $win.height();
					
					},
					// initialize some events
					initEvents		= function() {
						
						// navigation menu links.
						// scroll to the respective section.
						$links.on( 'click.Scrolling', function( event ) {
							
							// scroll to the element that has id = menu's href
							$('html, body').stop().animate({
								scrollTop: $( $(this).attr('href') ).offset().top
							}, scollPageSpeed, scollPageEasing );
							
							return false;
						
						});
						
						$(window).on({
							// on window resize we need to redefine which rows are initially visible (this ones we will not animate).
							'resize.Scrolling' : function( event ) {
								
								// get the window sizes again
								getWinSize();
								// redefine which rows are initially visible (:inviewport)
								setViewportRows();
								// remove pointers for every row
								$rows.find('a.ss-circle').removeClass('ss-circle-deco');
								// show inviewport rows and respective pointers
								$rowsViewport.each( function() {
								
									$(this).find('div.ss-left')
										   .css({ left   : '0%' })
										   .end()
										   .find('div.ss-right')
										   .css({ right  : '0%' })
										   .end()
										   .find('a.ss-circle')
										   .addClass('ss-circle-deco');
								
								});
							
							},
							// when scrolling the page change the position of each row	
							'scroll.Scrolling' : function( event ) {
								
								// set a timeout to avoid that the 
								// placeRows function gets called on every scroll trigger
								if( anim ) return false;
								anim = true;
								setTimeout( function() {
									
									placeRows();
									anim = false;
									
								}, 10 );
							
							}
						});
					
					},
					// sets the position of the rows (left and right row elements).
					// Both of these elements will start with -50% for the left/right (not visible)
					// and this value should be 0% (final position) when the element is on the
					// center of the window.
					placeRows		= function() {
						
							// how much we scrolled so far
						var winscroll	= $win.scrollTop(),
							// the y value for the center of the screen
							winCenter	= winSize.height / 2 + winscroll;
						
						// for every row that is not inviewport
						$rowsOutViewport.each( function(i) {
							
							var $row	= $(this),
								// the left side element
								$rowL	= $row.find('div.ss-left'),
								// the right side element
								$rowR	= $row.find('div.ss-right'),
								// top value
								rowT	= $row.offset().top;
							
							// hide the row if it is under the viewport
							if( rowT > winSize.height + winscroll ) {
								
								if( perspective ) {
								
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-75%, 0, 0) rotateY(-90deg) translate3d(-75%, 0, 0)',
										'opacity'			: 0
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(75%, 0, 0) rotateY(90deg) translate3d(75%, 0, 0)',
										'opacity'			: 0
									});
								
								}
								else {
								
									$rowL.css({ left 		: '-50%' });
									$rowR.css({ right 		: '-50%' });
								
								}
								
							}
							// if not, the row should become visible (0% of left/right) as it gets closer to the center of the screen.
							else {
									
									// row's height
								var rowH	= $row.height(),
									// the value on each scrolling step will be proporcional to the distance from the center of the screen to its height
									factor 	= ( ( ( rowT + rowH / 2 ) - winCenter ) / ( winSize.height / 2 + rowH / 2 ) ),
									// value for the left / right of each side of the row.
									// 0% is the limit
									val		= Math.max( factor * 50, 0 );
									
								if( val <= 0 ) {
								
									// when 0% is reached show the pointer for that row
									if( !$row.data('pointer') ) {
									
										$row.data( 'pointer', true );
										$row.find('.ss-circle').addClass('ss-circle-deco');
									
									}
								
								}
								else {
									
									// the pointer should not be shown
									if( $row.data('pointer') ) {
										
										$row.data( 'pointer', false );
										$row.find('.ss-circle').removeClass('ss-circle-deco');
									
									}
									
								}
								
								// set calculated values
								if( perspective ) {
									
									var	t		= Math.max( factor * 75, 0 ),
										r		= Math.max( factor * 90, 0 ),
										o		= Math.min( Math.abs( factor - 1 ), 1 );
									
									$rowL.css({
										'-webkit-transform'	: 'translate3d(-' + t + '%, 0, 0) rotateY(-' + r + 'deg) translate3d(-' + t + '%, 0, 0)',
										'opacity'			: o
									});
									$rowR.css({
										'-webkit-transform'	: 'translate3d(' + t + '%, 0, 0) rotateY(' + r + 'deg) translate3d(' + t + '%, 0, 0)',
										'opacity'			: o
									});
								
								}
								else {
									
									$rowL.css({ left 	: - val + '%' });
									$rowR.css({ right 	: - val + '%' });
									
								}
								
							}	
						
						});
					
					};
				
				return { init : init };
			
			})();
			
			$sidescroll.init();
			
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