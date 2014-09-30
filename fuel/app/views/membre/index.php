<div class="container">
	<div class="row">
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
								<a data-id-user="<?= \Auth::get('id') ?>" data-id="<?= $user['id'] ?>" class="btn btn-success btn-defier" role="button">Défier</a>
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
			id_defieur = $(this).attr('data-id-user');
			id_defier = $(this).attr('data-id');

			$.ajax({
				url: window.location.origin+'/matchs/api/defier.json',
				data: 'defieur='+id_defieur+'&defier='+id_defier,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
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