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
		<div class="col-xs-6 col-md-4">
			<div class="profil_photo">
				<div class="section_photo">
					<?php if ($photo_user): ?>
						<img src="<?= \Uri::base().\Config::get('users.photo.path') ?><?= $photo_user->photo ?>" alt="<?= \Auth::get('username') ?>" class="img-circle" width="300px" heigth="300px">
					<?php else: ?>
						<i class="fa fa-user fa-5x"></i><br>
					<?php endif; ?>
				</div>
				<input type="file" id="file_photo" name="file" class="form-control btn btn-info" data-toggle="file-input" title="Changer ma photo de profil">
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

		$('#file_photo').uploadify({
			'buttonText' : 'Changer ma photo',
			'buttonClass' : 'btn btn-info btn-upload-photo',
			'swf' : window.location.origin+'/assets/js/uploadify/uploadify.swf',
			'uploader' : window.location.origin+'/users/uploadPhoto',
			'fileDesc' : 'Image Files',
			'fileExt' : '*.jpg;*.jpeg;*.png;*.gif;*.bmp;*.pdf',
			'onUploadSuccess' : function(file, data, response){
				location.reload();
			}
		});
		$('#file_photo-button').removeClass('uploadify-button');
		$('#file_photo-button').css('height', '');
	});
</script>