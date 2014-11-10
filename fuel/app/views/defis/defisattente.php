<?php if ($defis): ?>
	<?php if ($new > 0): ?>
		<div class="alert alert-success"><i class="fa fa-smile-o"></i> <?= $new ?> nouveaux défis !</div>
	<?php endif; ?>
	<?php foreach ($defis as $defi): ?>
		<div class="row">
			<div class="col-md-8">
				<?php if ($defi['defi']->updated_at == 0): ?>
					<span class="label label-success label-new label-<?= $defi['defi']->id ?>">NEW</span>
				<?php else: ?>
					<span class="label label-default label-new label-<?= $defi['defi']->id ?>">Attendre</span>
				<?php endif; ?>

				<?php if ($defi['photouser'] == null): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>"/>
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defieur->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defieur->username ?>"/>
				<?php endif; ?>
				<a href="/profil/view/<?= $defi['defi']->defieur->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
				<?= $defi['defi']->defieur->username ?> vous défis
			</div>	

			<div class="col-md-4 btn-<?= $defi['defi']->id ?>">
				<a class="btn btn-success btn-accepte btn-acp-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-check"></i> Accepter</a>
				<a class="btn btn-default btn-attendre btn-att-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-ellipsis-h"></i> Attendre</a>
				<a class="btn btn-danger btn-refuse btn-ref-<?= $defi['defi']->id ?>" data-defi="<?= $defi['defi']->id ?>" data-loading-text="Chargement..."><i class="fa fa-close"></i> Refuser</a>
			</div>
		</div>
	<?php endforeach ?>
<?php else: ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert">
			<span arua-hidden="true">&times;</span>
			<span class="sr-only">Close</span>
		</button>
		<i class="fa fa-frown-o"></i> Pas de défis pour le moment
	</div>
<?php endif; ?>