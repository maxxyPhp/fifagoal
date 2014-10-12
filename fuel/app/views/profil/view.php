<div class="container">
	<h1 class="page-header">Profil de <?= $user->username ?></h1>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-xs-6 col-md-4 center-block center">
			<div class="profil_photo">
				<div class="section_photo">
					<?php if ($photo_user): ?>
						<img src="<?= \Uri::base().\Config::get('users.photo.path') ?><?= $photo_user->photo ?>" alt="<?= \Auth::get('username') ?>" class="img-circle" width="300px" heigth="300px">
					<?php else: ?>
						<img src="<?= \Uri::base().\Config::get('users.photo.path') ?>notfound.png" alt="<?= \Auth::get('username') ?>" class="img-circle" width="300px" heigth="300px">
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-xs-6 col-md-6">
			<div class="well">
				<strong>
				<?php if ($user->group_id != 6): ?>
					Membre
				<?php else: ?>
					Administrateur
				<?php endif; ?>
				</strong>

				<?php if ($ami_inverse == 1): ?>
					<a class="btn btn-primary btn-acp-ami" data-user="<?= $user->id ?>"><i class="fa fa-plus"></i> Accepter la demande d'ami</a>
				<?php elseif ($ami === false): ?>
					<a class="btn btn-primary btn-ami" data-user="<?= $user->id ?>"><i class="fa fa-users"></i> Demander en ami</a>
				<?php elseif ($ami == 0): ?>
					<a class="btn btn-primary btn-ami" disabled="disabled">Demande d'ami en attente</a>
				<?php elseif ($ami == 1): ?>
					<a class="btn btn-success btn-ami" disabled="disabled"><i class="fa fa-beer"></i> Ami</a>	
				<?php endif; ?>
				<br>
				<hr>
				Dernière connexion : <?= date('d/m/Y à H:i', \Auth::get('last_login')) ?><br>
				Inscription : <?= date('d/m/Y à H:i', \Auth::get('created_at')) ?><br>
			</div>

			<h2 class="page-header"><i class="fa fa-pie-chart"></i> Ses stats</h2>
				<span class="label label-info"><?= $stats['victoires'] + $stats['nuls'] + $stats['defaites'] ?> matchs disputés</span>
				<span class="label label-success"><?= $stats['victoires'] ?> victoires</span>
				<span class="label label-default"><?= $stats['nuls'] ?> matchs nuls</span>
				<span class="label label-danger"><?= $stats['defaites'] ?> défaites</span>
				<hr>
				
				<?php if ($derniers_matchs): ?>
					<h4><i class="fa fa-futbol-o"></i> Ses derniers matchs :</h4>
					<?php foreach ($derniers_matchs as $match): ?>
						<div class="row" style="margin-bottom:10px;">
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe1']->championnat->nom)) . '/' . $match['equipe1']->logo ?>" alt="<?= $match['equipe1']->nom ?>" width="50px" >
							</div>
							<div class="col-md-4">
								<a href="/matchs/view/<?= $match['id'] ?>"><div class="score_defis score-<?= $match['status'] ?>"><?= $match['score1'] ?>-<?= $match['score2'] ?></div></a>
							</div>
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe2']->championnat->nom)) . '/' . $match['equipe2']->logo ?>" alt="<?= $match['equipe2']->nom ?>" width="50px" >
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
		</div>

		<!-- LISTE AMIS -->
		<div class="col-md-2">
			<?php if ($liste_amis): ?>
				<div class="panel panel-default">
					<div class="panel-heading"><h4 class="nb_amis" data-nb="<?= count($liste_amis) ?>"><i class="fa fa-users"></i> <?= count($liste_amis) ?> amis</h4></div>
					<div class="panel-body liste-amis">
						<?php foreach ($liste_amis as $friend): ?>
							<?php if (!empty($friend['photouser'])): ?>
								<a href="/profil/view/<?= $friend['users']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $friend['photouser']->photo ?>" alt="<?= $friend['users']->username ?>" width="50" height="50" class="photo-amis" data-toggle="tooltip" data-placement="top" title="<?= $friend['users']->username ?>"></a>
							<?php else: ?>
								<a href="/profil/view/<?= $friend['users']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $friend['users']->username ?>" width="50" height="50" class="photo-amis" data-toggle="tooltip" data-placement="top" title="<?= $friend['users']->username ?>"></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else: ?>
				<div class="panel panel-default panel-amis" style="display:none;">
					<div class="panel-heading"><h4 class="nb_amis" data-nb="0"></h4></div>
					<div class="panel-body liste-amis"></div>
				</div>
			<?php endif; ?>
		</div>

	
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.photo-amis').tooltip();

		$('.btn-ami').on('click', function(){
			user = $(this).attr('data-user');
			$.ajax({
				url: window.location.origin + '/membre/api/addFriend.json',
				data: 'user='+user,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == 'OK'){
						$('.btn-ami').text('Demande envoyée');
					} else alert('Une erreur est survenue pendant le processus d\'ajout d\'ami');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		$('.btn-acp-ami').on('click', function(){
			user = $(this).attr('data-user');
			console.log(user);
			$.ajax({
				url: window.location.origin + '/membre/api/validFriend.json',
				data: 'user='+user,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					if (data != 'KO'){
						if (data != null){
							photo = '<a href="/profil/view/'+data.id_users+'"><img src="<?= \Uri::base() . \Config::get('users.photo.path') ?>'+ data.photo +'" width="50" height="50" class="photo-amis"></a>';
						} else photo = '<a href="/profil/view/'+data.id_users+'"><img src="<?= \Uri::base() . \Config::get("users.photo.path") . 'notfound.png' ?>" width="50" height="50" class="photo-amis"></a>';
						
						$('.liste-amis').append(photo);

						nb_amis = $('.nb_amis').attr('data-nb');
						$('.nb_amis').text(parseFloat(nb_amis)+1 +' amis');

						$('.panel-amis').show();

						$('.btn-acp-ami').text('Ami').addClass('btn-success').removeClass('btn-primary').attr('disabled', 'disabled');
					} else alert('Une erreur est survenue pendant le processus d\'ajout d\'ami');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});
	});
</script>