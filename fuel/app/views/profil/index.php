<div class="container">
	<h1 class="page-header">Profil de <?= \Auth::get_screen_name() ?></h1>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>\n
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-xs-6 col-md-4">
			<div class="profil_photo">
				<i class="fa fa-user fa-5x"></i><br>
				<a href="/users/photo/<?= \Auth::get('id') ?>" class="btn btn-info">Changer ma photo de profil</a>
			</div>
		</div>
		
		<div class="col-xs-6 col-md-6">
			<strong>
			<?php if (!\Auth::member(6)): ?>
				Membre
			<?php else: ?>
				Administrateur
			<?php endif; ?>
			</strong>
			-
			Dernière connexion : <?= date('d F Y à H:i', \Auth::get('last_login')) ?><br>
			Inscription : <?= date('d F Y à H:i', \Auth::get('created_at')) ?><br>

			<h2 class="page-header">Mes stats</h2>

			<h2 class="page-header">Fonctionnalités</h2>
			<a href="/users/change/<?= \Auth::get('id') ?>" class="btn btn-warning">Changer mon mot de passe</a>
			<a href="/users/delete/<?= \Auth::get('id') ?>" class="btn btn-danger">Me désinscrire du site</a>
		</div>

	
	</div>
</div>