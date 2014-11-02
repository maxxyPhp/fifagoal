<div class="container">
	<h1 class="page-header">Recherche : <?= \Session::get('recherche') ?></h1>

	<ul class="nav nav-tabs" role="tablist" style="margin-bottom:20px;">
		<?php if (count($users) > 0): ?>
	 		<li class="active">
	 	<?php else: ?>
	 		<li>
	 	<?php endif; ?>
	 		<a href="#users" data-toggle="tab">Membres <span class="badge"><?= count($users) ?></span></a></li>
	 		
	 	<?php if (count($users) == 0 && count($championnats) > 0): ?>
	 		<li class="active">
	 	<?php else: ?>
	 		<li>
	 	<?php endif; ?>
	 		<a href="#championnats" data-toggle="tab">Championnats <span class="badge"><?= count($championnats) ?></span></a></li>
	 	
	 	<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) > 0): ?>
	 		<li class="active">
	 	<?php else: ?>
	 		<li>
	 	<?php endif; ?>
	 		<a href="#equipes" data-toggle="tab">Equipes <span class="badge"><?= count($equipes) ?></span></a></li>
	 	
	 	<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) == 0 && count($joueurs) > 0): ?>
	 		<li class="active">
	 	<?php else: ?>
	 		<li>
	 	<?php endif; ?>
	 		<a href="#joueurs" data-toggle="tab">Joueurs <span class="badge"><?= count($joueurs) ?></span></a></li>
	 	
	 	<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) == 0 && count($joueurs) == 0 && count($selections)): ?>
	 		<li class="active">
	 	<?php else: ?>
	 		<li>
	 	<?php endif; ?>
	 		<a href="#selections" data-toggle="tab">Selections <span class="badge"><?= count($selections) ?></span></a></li>
	</ul>

	<article class="contenu">
		<section class="tab-content">

			<!-- USERS -->
			<?php if (count($users) > 0): ?>
				<section class="tab-pane active" id="users">
			<?php else: ?>
				<section class="tab-pane" id="users">
			<?php endif; ?>
				<?php if(count($users) > 0): ?>
					<div class="row">
						<?php foreach ($users as $user): ?>
							<div class="col-sm-6 col-sm-4">
								<div class="thumbnail">
									<?php if ($user['photo']): ?>
										<img class="lazy" data-original="<?= \Uri::base() . \Config::get('users.photo.path') . $user['photo']->photo ?>" alt="<?= $user['user']->username ?>" width="80">
									<?php else: ?>
										<img class="lazy" data-original="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $user['user']->username ?>" width="80">
									<?php endif; ?>
									<div class="caption">
										<h3 class="h3-search-j"><a href="/profil/view/<?= $user['user']->id ?>" style="color:black;"><?= $user['user']->username ?></a></h3>
										<p><?= count($user['user']['defis_defieur']) + count($user['user']['defis_defier']) ?> matchs</p>
										<p><?php if ($user['defi'] != 0): ?>
											<a data-id-user="<?= \Auth::get('id') ?>" data-id="<?= $user['user']->id ?>" class="btn btn-success btn-defier-tooltip" role="button" disabled="disabled">Défier</a>
										<?php else: ?>
											<a data-id-user="<?= \Auth::get('id') ?>" data-id="<?= $user['user']->id ?>" class="btn btn-success btn-defier" role="button" data-loading-text="Chargement...">Défier</a>
										<?php endif; ?>
										<a href="/profil/view/<?= $user['user']->id ?>" class="btn btn-info" role="button">Profil</a></p>
									</div>
								</div>
							</div>	
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">Pas de résultat pour les membres</div>
				<?php endif; ?>
			</section>

			<?php if (count($users) == 0 && count($championnats) > 0): ?>
				<section class="tab-pane active" id="championnats">
			<?php else: ?>
				<section class="tab-pane" id="championnats">
			<?php endif; ?>
				<?php if(count($championnats) > 0): ?>
					<div class="row">
						<?php foreach ($championnats as $c): ?>
							<div class="col-sm-6 col-sm-4">
								<div class="thumbnail champ-thumbnail">
									<img src="<?= \Uri::base() . \Config::get('upload.championnat.path') . '/' . $c->logo ?>" alt="<?= $c->nom ?>" width="80">
									<div class="caption">
										<a href="/ligue/view/<?= $c->id ?>" style="color:black;"><h3 class="h3-search-j"><?= $c->nom ?></h3></a>
										<p><?= count($c->equipes) ?> équipes</p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">Pas de résultat pour les championnats</div>
				<?php endif; ?>
			</section>

			<!-- EQUIPES -->
			<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) > 0): ?>
				<section class="tab-pane active" id="equipes">
			<?php else: ?>
				<section class="tab-pane" id="equipes">
			<?php endif; ?>
				<?php if(count($equipes) > 0): ?>
					<div class="row">
						<?php foreach ($equipes as $eq): ?>
							<div class="col-sm-6 col-sm-4">
								<div class="thumbnail thumbnail-equipes">
									<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($eq->championnat->nom)) . '/' . $eq->logo ?>" alt="<?= $eq->nom ?>" width="80">
									<div class="caption">
										<a href="/club/view/<?= $eq->id ?>" style="color:black;"><h3 class="h3-search-j"><?= $eq->nom ?></h3></a>
										<p><?= $eq->championnat->nom ?><br><?= count($eq->joueurs) ?> joueurs.<br>
										<?php if (count($eq->equipe1) + count($eq->equipe2) > 0): ?>
											Utilisé dans <?= count($eq->equipe1) + count($eq->equipe2) ?> match(s)
										<?php endif; ?>
										</p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">Pas de résultat pour les équipes</div>
				<?php endif; ?>
			</section>

			<!-- JOUEURS -->
			<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) == 0 && count($joueurs) > 0): ?>
				<section class="tab-pane active" id="joueurs">
			<?php else: ?>
				<section class="tab-pane" id="joueurs">
			<?php endif; ?>
				<?php if(count($joueurs) > 0): ?>
					<div class="row">
						<?php foreach ($joueurs as $j): ?>
							<div class="col-sm-6 col-sm-4">
								<div class="thumbnail div-thumbnail">
									<?php if ($j->photo): ?>
										<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($j->equipe->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($j->equipe->nom)) . '/' . $j->photo ?>" alt="<?= strtoupper($j->nom).' '.lcfirst($j->prenom) ?>" width="80">
									<?php else: ?>
										<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/notfound.png'   ?>" alt="<?= strtoupper($j->nom).' '.lcfirst($j->prenom) ?>">
									<?php endif; ?>
									<div class="caption">
										<div class="row">
											<div class="col-md-6">
												<h3 class="h3-search-j"><?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?></h3>
			                        			<div class="label label-<?= $j->poste->couleur ?>"><?= $j->poste->nom ?></div>
												<?php foreach ($j->pays as $pays): ?>
				                        			<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
				                        		<?php endforeach; ?>
				                        		<?php if ($j->buteurs): ?>
													<?= count($j->buteurs) ?> buts dans les matchs.
												<?php endif; ?>
											</div>
											<div class="col-sm-6 div-j-eq">
												<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($j->equipe->championnat->nom)) . '/' . $j->equipe->logo ?>" alt="<?= $j->equipe->nom ?>" width="60" data-toggle="tooltip" data-placement="top" title="<?= $j->equipe->nom ?>" class="photo-tooltip" />
												<?php if ($j->selection): ?>
				                        			<img src="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $j->selection->logo ?>" alt="<?= $j->selection->nom ?>" width="60" data-toggle="tooltip" data-placement="top" title="Equipe nationale <?= $j->selection->nom ?>" class="photo-tooltip" />
				                        		<?php endif; ?>
											</div>
										</div>		
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">Pas de résultat pour les joueurs</div>
				<?php endif; ?>
			</section>

			<!-- SEELCTIONS -->
			<?php if (count($users) == 0 && count($championnats) == 0 && count($equipes) == 0 && count($joueurs) == 0 && count($selections) > 0): ?>
				<section class="tab-pane active" id="selections">
			<?php else: ?>
				<section class="tab-pane" id="selections">
			<?php endif; ?>
				<?php if(count($selections) > 0): ?>
					<div class="row">
						<?php foreach ($selections as $s): ?>
							<div class="col-sm-6 col-sm-4">
								<div class="thumbnail">
									<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $s->logo ?>" alt="<?= $s->nom ?>">
									<div class="caption">
										<h3 class="h3-search-j"><?= $s->nom ?></h3>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="alert alert-danger">Pas de résultat pour les selections</div>
				<?php endif; ?>
			</section>
		</section>
	</article>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.photo-tooltip').tooltip();

		$("img.lazy").lazyload({
		    effect : "fadeIn"
		});

		$('.btn-defier').on('click', function(){
			btn = $(this);
			btn.button('loading');
			id_defieur = $(this).attr('data-id-user');
			id_defier = $(this).attr('data-id');

			$.ajax({
				url: window.location.origin+'/matchs/api/defier.json',
				data: 'defieur='+id_defieur+'&defier='+id_defier,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						// btn.button('reset');
						btn.html('Défier').attr('disabled', 'disabled');
						alert('Demande transmise. Le joueur défié recevra une notification de défis.');
					} else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});
	});
</script>