<div class="container">
	<div class="row">
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert">
				<span aria-hidden="true">&times;</span>
				<span class="sr-only">Close</span>
			</button>
			<span class="fa-stack fa-lg">
				<i class="fa fa-circle fa-stack-2x"></i>
				<i class="fa fa-info fa-stack-1x fa-inverse"></i>
			</span>
			Vous ne pouvez pas défier des joueurs qui n'ont pas encore répondu, favorablement ou non, à vos anciens défis.
		</div>
		<?php if ($users): ?>
			<?php foreach ($users as $user): ?>
				<div class="col-sm-6 col-md-4">
					<div class="thumbnail">
						<?php if($user['photo']): ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $user['photo'] ?>" alt="<?= $user['username'] ?>" width="200px">
						<?php else: ?>
							<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $user['username'] ?>" width="200px">
						<?php endif; ?>
						<div class="caption">
							<h3><?= $user['username'] ?></h3>
							<p>
								<?php if ($user['defis'] != 0): ?>
									<a data-id-user="<?= \Auth::get('id') ?>" data-id="<?= $user['id'] ?>" class="btn btn-success btn-defier-tooltip" role="button" disabled="disabled">Défier</a>
								<?php else: ?>
									<a data-id-user="<?= \Auth::get('id') ?>" data-id="<?= $user['id'] ?>" class="btn btn-success btn-defier" role="button" data-loading-text="Chargement...">Défier</a>
								<?php endif; ?>
								<a href="/profil/view/<?= $user['id'] ?>" class="btn btn-info" role="button">Profil</a>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
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