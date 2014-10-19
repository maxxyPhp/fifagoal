<div class="container">
	<h1 class="page-header">Classement des players</h1>

	<p>Chaque victoire rapporte trois points, un nul en rapporte un, une défaite zéro.<br>
	Une victoire par trois buts d'écarts donne un point bonus, inversement, une défaite par trois buts d'écarts enlève un point de malus.</p>
	<section class="table-responsive">
		<table id="myTab" class="table table-hover table-striped table-classement">
			<thead>
				<tr>
					<th>#</th>
					<th>Player</th>
					<th>Points</th>
					<th>Victoires</th>
					<th>Nuls</th>
					<th>Défaites</th>
					<th>Bonus</th>
					<th>Malus</th>
					<th>Buts marqués</th>
					<th>Buts encaissés</th>
					<th>Diff.</th>
				</tr>
			</thead>

			<tbody>
				<?php $i = 1; ?>
				<?php foreach ($users as $user): ?>
					<?php if ($i == 1): ?>
						<tr class="success">
							<td class="numero"><?= $i ?><i class="fa fa-trophy fa-3x" style="margin-left:10px;"></i></td>
					<?php elseif ($i == 2 || $i == 3): ?>
						<tr class="info">
							<td class="numero"><?= $i ?></td>
					<?php else: ?>
						<tr>
							<td class="numero"><?= $i ?></td>
					<?php endif; ?>

						<td class="classement_pseudo">
							<?php if ($user['photo']): ?>
								<a href="/profil/view/<?= $user['user']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $user['photo']->photo ?>" alt="<?= $user['user']->username ?>" width="60" /></a>
							<?php else: ?>
								<a href="/profil/view/<?= $user['user']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $user['user']->username ?>" width="60" /></a>
							<?php endif; ?>
							<a href="/profil/view/<?= $user['user']->id ?>" style="margin-left:10px;"><strong><?= $user['user']->username ?></strong></a>
						</td>
						<td><?= $user['points'] ?></td>
						<td><?= $user['victoires'] ?></td>
						<td><?= $user['nuls'] ?></td>
						<td><?= $user['defaites'] ?></td>
						<td>
							<?php if ($user['bonus'] > 0): ?>
								<strong><?= $user['bonus'] ?></strong>
							<?php else: ?>
								<?= $user['bonus'] ?>
							<?php endif; ?>
						</td>
						<td>
							<?php if ($user['malus'] > 0): ?>
								<strong><?= $user['malus'] ?></strong>
							<?php else: ?>
								<?= $user['malus'] ?>
							<?php endif; ?>
						</td>
						<td><?= $user['butsm'] ?></td>
						<td><?= $user['butse'] ?></td>
						<td><?= $user['butsm'] - $user['butse'] ?></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script>