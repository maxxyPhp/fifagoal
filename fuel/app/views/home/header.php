<nav class="navbar navbar-inverse navbar-fixed-top " role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand site_title" href="/">FIFAGOAL</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="/"><i class="fa fa-home"></i> Accueil</a></li>
				<li><a href="/classement"><i class="fa fa-list-ol"></i> Classement</a></li>
				<?php if (\Auth::check()): ?>
					<li><a href="/profil"><i class="fa fa-desktop"></i> Profil</a>
					<li><a href="/membre"><i class="fa fa-male"></i> Membres</a></li>
					<?php if ($defis >= 1): ?>
						<li><a href="/defis"><i class="fa fa-gamepad"></i> Défis <span class="badge"><?= $defis ?></span></a></li>
					<?php else: ?> 
						<li><a href="/defis"><i class="fa fa-gamepad"></i> Défis</a></li>
					<?php endif; ?>
				<?php endif; ?>
				<?php $group = \Model\Auth_Group::find(6); ?>
				<?php if (\Auth::check() && \Auth::member($group)): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i> Gérer <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/users"><i class="fa fa-user"></i> Users</a></li>
							<li class="divider"></li>
							<li><a href="/pays"><i class="fa fa-flag"></i> Pays</a></li>
							<li><a href="/championnat"><i class="fa fa-cubes"></i> Championnat</a></li>
							<li><a href="/equipe"><i class="fa fa-cube"></i> Equipes</a></li>
							<li><a href="/joueur/import"><i class="fa fa-male"></i> Import de joueurs</a></li>
							<li><a href="/poste"><i class="fa fa-arrows-alt"></i> Poste</a></li>
							<li><a href="/selection"><i class="fa fa-heart"></i> Selections</a></li>
							<li><a href="/transfert"><i class="fa fa-arrows-h"></i> Transferts</a></li>
							<li class="divider"></li>
							<li><a href="/migrations"><i class="fa fa-exchange"></i> Migrations</a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>

			<form action="/search" method="post" class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" name="search" pattern=".{3,}" class="form-control input-search" placeholder="Recherche" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-content="Tu peux rechercher un membre, un championnat, une équipe, un joueur ou une selection. La recherche doit comporter trois caractères minimum. Elle peut être divisé en plusieurs mots. Ex: Hazard paris sg">
				</div>
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
			</form>

			<ul class="nav navbar-nav navbar-right" style="width:250px;">
				<?php if (\Auth::check()): ?>
					<li class="dropdown menu-notif" data-id="<?= \Auth::get('id') ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if ($news > 0): ?><span class="badge badge-notif"><?= $news ?></span><?php endif; ?> <i class="fa fa-paper-plane"></i> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" style="width: 370px;">
							<?php if (!empty($notifys)): ?>
								<?php foreach ($notifys as $notify): ?>
									<?php if ($notify->new == 1): ?>
										<li class="li-notifs">
											<span>
												<div class="label label-success">NEW</div><?= htmlspecialchars_decode($notify->message) ?>
											</span>
											<i class="fa fa-clock-o"></i> <?= date('m', $notify->created_at) ?>
											<?= strftime("%B,", $notify->created_at) ?>
											<?= date('H:i', $notify->created_at) ?>
											<hr></li>
									<?php else: ?>
										<li class="li-notifs">
											<span><?= htmlspecialchars_decode($notify->message) ?></span>
											<i class="fa fa-clock-o"></i> <?= date('m', $notify->created_at) ?>
											<?= strftime("%B,", $notify->created_at) ?>
											<?= date('H:i', $notify->created_at) ?>
										<hr></li>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else: ?>
								<li class="li-notifs">Pas de notifications pour le moment.</li>
							<?php endif; ?>
						</ul>
					</li>


					<?php if (!empty($photouser)): ?>
						<li><img src="<?= \Uri::base() . \Config::get('users.photo.path') . '/' . $photouser->photo ?>" alt="<?= \Auth::get('username') ?>" width="30" height="30" class="photo_profil_menu"></li>
					<?php else: ?>
						<img src="<?= \Uri::base() . \Config::get('users.photo.path') . '/notfound.png' ?>" alt="<?= \Auth::get('username') ?>" width="30px" class="photo_profil_menu_default" />
					<?php endif; ?>
					<a href="/auth/logout" class="btn btn-primary navbar-btn"><i class="fa fa-sign-out"></i> Déconnexion</a>
				<?php else: ?>
					<a href="/auth/signin" class="btn btn-default navbar-btn">Inscription</a>
					<a href="/auth" class="btn btn-primary navbar-btn">Connexion</a>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>

<script type="text/javascript">
	$(document).ready(function(){
		$('.input-search').popover();

		$('.menu-notif').on('click', function(){
			user = $(this).attr('data-id');
			if ($('.badge-notif').length){
				$.ajax({
					url: window.location.origin + '/notify/api/viewNotify.json',
					data: 'user='+user,
					type: 'get',
					dataType: 'json',
					success: function(data){
						if (data == 'KO') alert('Une erreur est survenue pendant le traitement des données');
					},
					error: function(){
						alert('Une erreur est survenue');
					},
				});
			}
			$('.badge-notif').remove();
		});
	});
</script>