<div class="container">
	<h1 class="page-header">Vos défis</h1>

	<?php if ($defis): ?>
		<?php if ($new > 0): ?>
			<div class="alert alert-success"><i class="fa fa-smile-o"></i> <?= $new ?> nouveaux défis !</div>
		<?php endif; ?>
		<?php foreach ($defis as $defi): ?>
			<div class="row">
				<div class="col-md-6">
					<?php if ($defi['defi']['updated_at'] == 0): ?>
						<span class="label label-success label-new label-<?= $defi['defi']['id'] ?>">NEW</span>
					<?php else: ?>
						<span class="label label-default label-new label-<?= $defi['defi']['id'] ?>">Attendre</span>
					<?php endif; ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser'] ?>" alt="<?= $defi['defieur']['username'] ?>" class="img-thumbnail" width='80px' />
						<a href="/profil/view/<?= $defi['defieur']['id'] ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
						<?= $defi['defieur']['username'] ?> vous défis
						
				</div>

				<div class="col-md-6">
					<a class="btn btn-success btn-accepte" data-defi="<?= $defi['defi']['id'] ?>"><i class="fa fa-check"></i> Accepter</a>
					<a class="btn btn-default btn-attendre" data-defi="<?= $defi['defi']['id'] ?>"><i class="fa fa-ellipsis-h"></i> Attendre</a>
					<a class="btn btn-danger btn-refuse" data-defi="<?= $defi['defi']['id'] ?>"><i class="fa fa-close"></i> Refuser</a>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div class="alert alert-warning"><i class="fa fa-frown-o"></i> Pas de défis pour le moment</div>
	<?php endif; ?>

	<h1 class="page-header">Vos défis acceptés</h1><a class="btn btn-default btn-acp" data-action="1">Voir les défis acceptés</a>
	<div class="defis_acceptes animated" style="display:none;">
		<?php if ($defis_acp): ?>
			<?php foreach ($defis_acp as $defi): ?>
				<div class="row" style="margin-top:10px;">
					<div class="col-md-6">
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser'] ?>" alt="<?= $defi['defieur']['username'] ?>" class="img-thumbnail" width='80px' />
						<a href="/profil/view/<?= $defi['defieur']['id'] ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
						Vous avez accepté le défi de <?= $defi['defieur']['username'] ?>
					</div>
				</div>
			<?php endforeach ?>
		<?php else: ?>
			<div class="alert alert-warning" style="margin-top:10px;"><i class="fa fa-frown-o"></i> Vous n'avez pas encore accepté de défis</div>
		<?php endif; ?>
	</div>

	<h1 class="page-header">Vos défis refusés</h1><a class="btn btn-default btn-ref" data-action="1">Voir les défis refusés</a>
	<div class="defis_refuses animated" style="display:none;">
		<?php if ($defis_ref): ?>
			<?php foreach ($defis_ref as $defi): ?>
				<div class="row" style="margin-top:10px;">
					<div class="col-md-6">
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser'] ?>" alt="<?= $defi['defieur']['username'] ?>" class="img-thumbnail" width='80px' />
						<a href="/profil/view/<?= $defi['defieur']['id'] ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
						Vous avez refusé le défi de <?= $defi['defieur']['username'] ?>
					</div>
				</div>
			<?php endforeach ?>
		<?php else: ?>
			<div class="alert alert-warning" style="margin-top:10px;"><i class="fa fa-smile-o"></i> Vous n'avez pas encore refusé de défis</div>
		<?php endif; ?>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		// METTRE UN DEFI EN ATTENTE
		$('.btn-attendre').on('click', function(){
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/attendre.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
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
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/accepter.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
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
		$('.btn-accepte').on('click', function(){
			defi = $(this).attr('data-defi');
			$.ajax({
				url: window.location.origin + '/defis/api/refuser.json',
				data: 'defis='+defi,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						alert('Défi refusé. Votre défieur va recevoir une notification');
						$('.label-'+defi).html('Refusé').addClass('label-danger').removeClass('label-default').removeClass('label-success');
					} else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

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
	});
</script>