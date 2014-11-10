<?php if ($defis_lances): ?>
	<?php foreach ($defis_lances as $defi): ?>
		<div class="row" style="margin-top:10px;">
			<div class="col-md-8">
				<?php if ($defi['photouser'] == null): ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
				<?php else: ?>
					<img src="<?= \Uri::base() . \Config::get('users.photo.path') . $defi['photouser']->photo ?>" alt="<?= $defi['defi']->defier->username ?>" class="img-thumbnail img-tooltip" width='80px' data-toggle="tooltip" data-placement="top" title="<?= $defi['defi']->defier->username ?>"/>
				<?php endif; ?>
				<a href="/profil/view/<?= $defi['defi']->defier->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir son profil </a>
			</div>

			<div class="col-md-4">
				<?php if ($defi['defi']->status->code == 0): ?>
					<span class="label label-default">En attente</span>
				<?php elseif ($defi['defi']->status->code == 1): ?>
					<span class="label label-success">Accepté</span>
					<a class="btn btn-primary btn-rapport" data-defi="<?= $defi['defi']['id'] ?>">Faire le rapport du match</a>
					<form id="form-rapport-<?= $defi['defi']->id ?>" action="/matchs/add" method="post">
						<input type="hidden" name="defi" value="<?= $defi['defi']->id ?>" />
					</form>
				<?php else: ?>
					<span class="label label-danger">Refusé</span>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="alert alert-warning alert-dismissible">
		<button type="button" class="close" data-dismiss="alert">
			<span aria-hidden="true">&times;</span>
			<span class="sr-only">Close</span>
		</button>
		<i class="fa fa-frown-o"></i> Vous n'avez pas envoyé de défi récemment
	</div>
<?php endif; ?>