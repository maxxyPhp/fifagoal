<div class="container">
	<div class="menu-defis">
		<ul class="list-unstyled">
			<li class="col-md-2"><strong><?= $new + count($defis_lances) + count($defis_acp) + count($defis_ref) + count($defis_termines) ?></strong> défis</li>
			<li class="col-md-2"><a href="#new-defis"><strong><?= $new ?></strong> nouveaux</a></li>
			<li class="col-md-2"><a href="#defis-env"><strong><?= count($defis_lances) ?></strong> envoyés</a></li>
			<li class="col-md-2"><a href="#defis-acp"><strong><?= count($defis_acp) ?></strong> acceptés</a></li>
			<li class="col-md-2"><a href="#defis-ref"><strong><?= count($defis_ref) ?></strong> refusés</a></li>
			<li class="col-md-2"><a href="#defis-ter"><strong><?= count($defis_termines) ?></strong> terminés</a></li>
		</ul>
	</div>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'danger') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>

	<!-- DEFIS EN ATTENTE -->
	<h1 id="new-defis" class="page-header">Vos défis</h1>
	<?php if ($defis): ?>
		<?php if ($new > 0): ?>
			<div class="alert alert-success"><i class="fa fa-smile-o"></i> <?= $new ?> nouveaux défis !</div>
		<?php endif; ?>
		<?php foreach ($defis as $defi): ?>
			<div class="row">
				<div class="col-md-8">
					<?php if ($defi['defi']->updated_at == 0): ?>
						<span class="label label-success label-new label-<?= $defi['defi']->id ?>">NEW</span>
					<?php else: ?>
						<span class="label label-default label-new label-<?= $defi['defi']->id ?>">Attendre</span>
					<?php endif; ?>

					<?php if ($defi['photouser'] == null): ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>"/>
					<?php else: ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>"/>
					<?php endif; ?>
					<a href="/profil/view/<?= $defi['defi']->defieur->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
					<?= $defi['defi']->defieur->username ?> vous défis
				</div>	

				<div class="col-md-4 btn-<?= $defi['defi']->id ?>">
					<a class="btn btn-success btn-accepte btn-acp-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-check"></i> Accepter</a>
					<a class="btn btn-default btn-attendre btn-att-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-ellipsis-h"></i> Attendre</a>
					<a class="btn btn-danger btn-refuse btn-ref-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-close"></i> Refuser</a>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div class="alert alert-warning alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert">
				<span arua-hidden="true">&times;</span>
				<span class="sr-only">Close</span>
			</button>
			<i class="fa fa-frown-o"></i> Pas de défis pour le moment
		</div>
	<?php endif; ?>



	<!-- DEFIS ENVOYES -->
	<h1 id="defis-env" class="page-header">Vos défis envoyés</h1>
	<?php if ($defis_lances): ?>
		<?php foreach ($defis_lances as $defi): ?>
			<div class="row" style="margin-top:10px;">
				<div class="col-md-8">
					<?php if ($defi['photouser'] == null): ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
					<?php else: ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
					<?php endif; ?>
					<a href="/profil/view/<?= $defi['defi']->defier->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
				</div>

				<div class="col-md-4">
					<?php if ($defi['defi']->status->code == 0): ?>
						<span class="label label-default">En attente</span>
					<?php elseif ($defi['defi']->status->code == 1): ?>
						<span class="label label-success">Accepté</span>
						<a class="btn btn-primary btn-rapport" data-defi="<?= $defi['defi']['id'] ?>">Faire le rapport du match</a>
						<form id="form-rapport-<?= $defi['defi']->id ?>" action="/matchs/add" method="post">
							<input type="hidden" name="defi" value="<?= $defi['defi']->id ?>" />
						</form>
					<?php else: ?>
						<span class="label label-danger">Refusé</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="alert alert-warning alert-dismissible">
			<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">&times;</span>
				<span class="sr-only">Close</span>
			</button>
			<i class="fa fa-frown-o"></i> Vous n'avez pas envoyé de défi récemment
		</div>
	<?php endif; ?>

	
	<!-- DEFIS ACCEPTES -->
	<h1 id="defis-acp" class="page-header">Vos défis acceptés</h1><a class="btn btn-default btn-acp" data-action="1">Voir les défis acceptés</a>
	<div class="defis_acceptes animated" style="display:none;">
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
	</div>

	<!-- DEFIS A VALIDER -->
	<h1 id="defis_avalider" class="page-header">Vos défis à valider</h1><a class="btn btn-default btn-avalider" data-action="1">Voir les défis à valider</a>
	<div class="defis_avalider animated" style="display:none;"?>
		<?php if ($defis_avalider): ?>
			<?php foreach ($defis_avalider as $defi): ?>
				<div class="row" style="margin-top:10px;">
					<div class="col-md-6">
						<?php if ($defi['photouser']): ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
						<?php else: ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
						<?php endif; ?>
						<a href="/profil/view/<?= $defi['defi']->defier->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>

						<?php if (($defi['defi']->id_joueur_defier == \Auth::get('id') && $defi['defi']->match_valider2 == 0) || ($defi['defi']->id_joueur_defieur == \Auth::get('id') && $defi['defi']->match_valider1 == 0)): ?>
							<a href="/matchs/view/<?= $defi['defi']->match->id ?>" class="btn btn-warning"><i class="fa fa-exclamation-triangle"></i> N'oubliez pas de valider le match</a>
						<?php endif; ?>
					</div>

					<div class="col-md-4 div_resultat">
						<div class="row">
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe1->championnat->nom)) . '/' . $defi['defi']->match->equipe1->logo ?>" alt="<?= $defi['defi']->match->equipe1->nom ?>" width="30px" />
							</div>
							<div class="col-md-4">
								<a href="/matchs/view/<?= $defi['defi']->match->id ?>"><div class="score_defis"><?= $defi['defi']->match->score_joueur1 ?> - <?= $defi['defi']->match->score_joueur2 ?></div></a>
							</div>
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe2->championnat->nom)) . '/' . $defi['defi']->match->equipe2->logo ?>" alt="<?= $defi['defi']->match->equipe2->nom ?>" width="30px" />
							</div>
						</div>
					</div>

					<div class="col-md-2 div_resultat">
						<?php if ($defi['defi']->match->score_joueur1 > $defi['defi']->match->score_joueur2): ?>
							<span class="label label-success">Victoire</span>
						<?php elseif ($defi['defi']->match->score_joueur1 == $defi['defi']->match->score_joueur2): ?>
							<span class="label label-default">Nul</span>
						<?php else: ?>
							<span class="label label-danger">Défaite</span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="alert alert-warning" style="margin-top:10px;">Pas de défis à valider !</div>
		<?php endif; ?>
	</div>


	<!-- DEFIS REFUSES -->
	<h1 id="defis-ref" class="page-header">Vos défis refusés</h1><a class="btn btn-default btn-ref" data-action="1">Voir les défis refusés</a>
	<div class="defis_refuses animated" style="display:none;">
		<?php if ($defis_ref): ?>
			<?php foreach ($defis_ref as $defi): ?>
				<div class="row" style="margin-top:10px;">
					<div class="col-md-6">
						<?php if ($defi['photouser'] == null): ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
						<?php else: ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
						<?php endif; ?>
						<a href="/profil/view/<?= $defi['defi']->defieur->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
						Vous avez refusé le défi de <?= $defi['defi']->defieur->username ?>
					</div>
				</div>
			<?php endforeach ?>
		<?php else: ?>
			<div class="alert alert-warning" style="margin-top:10px;"><i class="fa fa-smile-o"></i> Vous n'avez pas encore refusé de défis</div>
		<?php endif; ?>
	</div>


	<!-- DEFIS TERMINES -->
	<h1 id="defis-ter" class="page-header">Vos défis terminés</h1>
	<a class="btn btn-default btn-ter" data-action="1">Voir les défis terminés</a>
	<div class="defis_termines animated" style="display:none;">
		<?php if ($defis_termines): ?>
			<?php foreach ($defis_termines as $defi): ?>
				<div class="row" style="margin-top:10px;">
					<div class="col-md-6">
						<?php if ($defi['photouser']): ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
						<?php else: ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>" />
						<?php endif; ?>
						<a href="/profil/view/<?= $defi['defi']->defier->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>

						<?php if (($defi['defi']->id_joueur_defier == \Auth::get('id') && $defi['defi']->match_valider2 == 0) || ($defi['defi']->id_joueur_defieur == \Auth::get('id') && $defi['defi']->match_valider1 == 0)): ?>
							<a href="/matchs/view/<?= $defi['defi']->id_match ?>" class="btn btn-warning"><i class="fa fa-exclamation-triangle"></i> N'oubliez pas de valider le match</a>
						<?php endif; ?>
					</div>

					<div class="col-md-4 div_resultat">
						<div class="row">
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe1->championnat->nom)) . '/' . $defi['defi']->match->equipe1->logo ?>" alt="<?= $defi['defi']->match->equipe1->nom ?>" width="30px" />
							</div>
							<div class="col-md-4">
								<a href="/matchs/view/<?= $defi['defi']->match->id ?>"><div class="score_defis"><?= $defi['defi']->match->score_joueur1 ?> - <?= $defi['defi']->match->score_joueur2 ?></div></a>
							</div>
							<div class="col-md-4 defis_club">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($defi['defi']->match->equipe2->championnat->nom)) . '/' . $defi['defi']->match->equipe2->logo ?>" alt="<?= $defi['defi']->match->equipe2->nom ?>" width="30px" />
							</div>
						</div>
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
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		// METTRE UN DEFI EN ATTENTE
		$('.btn-attendre').on('click', function(){
			btn = $(this);
			btn.button('loading');
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/attendre.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						btn.button('reset');
						$('.btn-att-'+defi).remove();
						$('.btn-ref-'+defi).remove();
						$('.btn-acp-'+defi).remove();
						alert('Défi mis en attente');
						$('.label-'+defi).html('Attendre').addClass('label-default').removeClass('label-success');
					} else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		// ACCEPTER UN DEFI
		$('.btn-accepte').on('click', function(){
			btn = $(this);
			btn.button('loading');
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/accepter.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						btn.button('reset');
						$('.btn-att-'+defi).remove();
						$('.btn-ref-'+defi).remove();
						$('.btn-acp-'+defi).remove();
						$('.btn-'+defi).append(
							'<a class="btn btn-primary btn-rapport" data-defi="'+defi+'">Faire le rapport du match</a>'
							+'<form id="form-rapport-'+defi+'" action="/matchs/add" method="post">'
								+'<input type="hidden" name="defi" value="'+defi+'" />'
							+'</form>'
						);
						alert('Défi accepté. Votre défieur va recevoir une notification');
						$('.label-'+defi).html('Accepté').addClass('label-success').removeClass('label-default');
					} else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		// REFUSER UN DEFI
		$('.btn-refuse').on('click', function(){
			btn = $(this);
			btn.button('loading');
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/refuser.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						btn.button('reset');
						$('.btn-att-'+defi).remove();
						$('.btn-ref-'+defi).remove();
						$('.btn-acp-'+defi).remove();
						alert('Défi refusé.');
						$('.label-'+defi).html('Refusé').addClass('label-danger').removeClass('label-default').removeClass('label-success');
					} else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		/* DEFIS ACCEPTES */
		$('.btn-acp').on('click', function(){
			action = $(this).attr('data-action');
			// On montre les acceptés
			if (action == 1){
				$('.defis_acceptes').show();
				$('.defis_acceptes').addClass('fadeInUp').removeClass('fadeOutDown');
				$('.btn-acp').attr('data-action', 0);
				$('.btn-acp').html('Cacher les défis acceptés');
			} 
			// On cache les acceptés
			else {
				$('.defis_acceptes').addClass('fadeOutDown').removeClass('fadeInUp');
				$('.btn-acp').attr('data-action', 1);
				$('.btn-acp').html('Voir les défis acceptés');
				$('.defis_acceptes').hide();
			}
		});

		/* DEFIS REFUSES */
		$('.btn-ref').on('click', function(){
			action = $(this).attr('data-action');
			// On montre les refusés
			if (action == 1){
				$('.defis_refuses').addClass('fadeInUp').removeClass('fadeOutDown').show();
				$('.btn-ref').attr('data-action', 0).html('Cacher les défis refusés');
			} 
			// On cache les refusés
			else {
				$('.defis_refuses').addClass('fadeOutDown').removeClass('fadeInUp').hide();
				$('.btn-ref').attr('data-action', 1).html('Voir les défis refusés');
			}
		});

		// CLIC SUR FAIRE UN RAPPORT DE MATCH
		$('body').on('click', '.btn-rapport', function(){
			defi = $(this).attr('data-defi');
			$('#form-rapport-'+defi).submit();
		});

		$('.img-tooltip').tooltip();

		/* DEFIS TERMINES */
		$('.btn-ter').on('click', function(){
			action = $(this).attr('data-action');
			// On montre les refusés
			if (action == 1){
				$('.defis_termines').addClass('fadeInUp').removeClass('fadeOutDown').show();
				$('.btn-ter').attr('data-action', 0).html('Cacher les défis terminés');
			} 
			// On cache les refusés
			else {
				$('.defis_termines').addClass('fadeOutDown').removeClass('fadeInUp').hide();
				$('.btn-ter').attr('data-action', 1).html('Voir les défis terminés');
			}
		});

		/* DEFIS A VALIDER */
		$('.btn-avalider').on('click', function(){
			action = $(this).attr('data-action');
			// On montre les à valider
			if (action == 1){
				$('.defis_avalider').addClass('fadeInUp').removeClass('fadeOutDown').show();
				$('.btn-avalider').attr('data-action', 0).html('Cacher les défis à valider');
			} 
			// On cache les à valider
			else {
				$('.defis_avalider').addClass('fadeOutDown').removeClass('fadeInUp').hide();
				$('.btn-avalider').attr('data-action', 1).html('Voir les défis à valider');
			}
		});
	});
</script>