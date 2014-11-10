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