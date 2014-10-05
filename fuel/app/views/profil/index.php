<div class="container">
	<h1 class="page-header">Profil de <?= \Auth::get_screen_name() ?></h1>

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
				<a class="btn btn-info btn-upload center-block">Changer ma photo de profil</a>
				<div class="btn-fileupload" style="display:none;">
					<div id="fileuploader">Upload</div>
				</div>
			</div>
		</div>
		
		<div class="col-xs-6 col-md-6">
			<div class="well">
				<strong>
				<?php if (!\Auth::member(6)): ?>
					Membre
				<?php else: ?>
					Administrateur
				<?php endif; ?>
				</strong><br>
				<hr>
				Dernière connexion : <?= date('d/m/Y à H:i', \Auth::get('last_login')) ?><br>
				Inscription : <?= date('d/m/Y à H:i', \Auth::get('created_at')) ?><br>
			</div>

			<h2 class="page-header">Mes stats</h2>
				<span class="label label-info"><?= $stats['victoires'] + $stats['nuls'] + $stats['defaites'] ?> matchs disputés</span>
				<span class="label label-success"><?= $stats['victoires'] ?> victoires</span>
				<span class="label label-default"><?= $stats['nuls'] ?> matchs nuls</span>
				<span class="label label-danger"><?= $stats['defaites'] ?> défaites</span>
				<hr>
				<h4>Mes derniers matchs :</h4>
				<?php if ($derniers_matchs): ?>
					<?php foreach ($derniers_matchs as $match): ?>
						<div class="row" style="margin-bottom:10px;">
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe1']->championnat->nom)) . '/' . $match['equipe1']->logo ?>" alt="<?= $match['equipe1']->nom ?>" width="50px" >
							</div>
							<div class="col-md-4">
								<div class="score_defis score-<?= $match['status'] ?>"><?= $match['score1'] ?>-<?= $match['score2'] ?></div>
							</div>
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe2']->championnat->nom)) . '/' . $match['equipe2']->logo ?>" alt="<?= $match['equipe2']->nom ?>" width="50px" >
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

			<h2 class="page-header">Fonctionnalités</h2>
			<a href="/users/change/<?= \Auth::get('id') ?>" class="btn btn-warning">Changer mon mot de passe</a>
			<a href="/users/delete/<?= \Auth::get('id') ?>" class="btn btn-danger btn-quit">Me désinscrire du site</a>
		</div>

	
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.btn-quit').on('click', function(){
			if (!confirm("Etes vous sur de vouloir vous désinscrire du site ?")){
				return false;
			}
		});

		$('.btn-upload').on('click', function(){
			$('.btn-fileupload').show();
		});

		$("#fileuploader").uploadFile({
			url:window.location.origin+'/users/uploadPhoto',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('.img-circle').attr('src', '<?= \Uri::base() . \Config::get("users.photo.path") ?>'+data);
			}
		});
	});
</script>