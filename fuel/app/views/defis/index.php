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

	<?= render('helper/messages'); ?>
	
	<!-- DEFIS EN ATTENTE -->
	<h1 id="new-defis" class="page-header">Vos défis</h1>
	<?= render('defis/defisattente', array('defis' => $defis)); ?>

	<!-- DEFIS ENVOYES -->
	<h1 id="defis-env" class="page-header">Vos défis envoyés</h1>
	<?= render('defis/defisenv', array('defis_lances' => $defis_lances)); ?>

	
	<!-- DEFIS ACCEPTES -->
	<h1 id="defis-acp" class="page-header">Vos défis acceptés</h1><a class="btn btn-default btn-acp" data-action="1">Voir les défis acceptés</a>
	<div class="defis_acceptes animated" style="display:none;">
		<?= render('defis/defisacp', array('defis_acp' => $defis_acp)); ?>
	</div>

	<!-- DEFIS A VALIDER -->
	<h1 id="defis_avalider" class="page-header">Vos défis à valider</h1><a class="btn btn-default btn-avalider" data-action="1"><span class="badge"><?= count($defis_avalider) ?></span> Voir les défis à valider</a>
	<div class="defis_avalider animated" style="display:none;"?>
		<?= render('defis/defisavalider', array('defis_avalider' => $defis_avalider)); ?>
	</div>


	<!-- DEFIS REFUSES -->
	<h1 id="defis-ref" class="page-header">Vos défis refusés</h1><a class="btn btn-default btn-ref" data-action="1">Voir les défis refusés</a>
	<div class="defis_refuses animated" style="display:none;">
		<?= render('defis/defisrefuses', array('defis_ref' => $defis_ref)); ?>
	</div>


	<!-- DEFIS TERMINES -->
	<h1 id="defis-ter" class="page-header">Vos défis terminés</h1>
	<a class="btn btn-default btn-ter" data-action="1">Voir les défis terminés</a>
	<div class="defis_termines animated" style="display:none;">
		<?= render('defis/matchstermines', array('defis_termines' => $defis_termines)); ?>	
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