<div class="container">
	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>

	<h1 class="page-header">Liste des championnats</h1>

	<a href="/championnat/add" class="btn btn-success">Ajouter un nouveau championnat</a>
	<a href="/championnat/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

	<?php if ($championnats): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<tr>
						<th></th>
						<th>Nom</th>
						<th>Logo</th>
						<th>Pays</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($championnats as $championnat): ?>
						<tr>
							<td>
								<?php if ($championnat->actif == 1): ?>
									<i class="fa fa-check fa-2x"></i>
								<?php else: ?>
									<i class="fa fa-close fa-2x"></i>
								<?php endif; ?>
							</td>
							<td><?= $championnat->nom ?></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.championnat.path') . '/' . $championnat->logo ?>" alt="<?= $championnat->nom ?>" width="80px" /></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $championnat->pays->drapeau ?>" alt="<?= $championnat->pays->nom ?>" width="80px" data-toggle="tooltip" data-placement="top" title="<?= $championnat->pays->nom ?>" class="img_pays" /></td>
							<td>
								<a href="/championnat/activate/<?= $championnat->id ?>" class="btn btn-primary">
									<?php if ($championnat->actif == 1): ?>
										<i class="fa fa-close"></i> Désactiver
									<?php else: ?>
										<i class="fa fa-check"></i> Activer
									<?php endif; ?>
								</a>
								<a href="/championnat/activateall/<?= $championnat->id ?>" class="btn btn-primary"><i class="fa fa-check"></i> Tout activer</a>
								<a href="/championnat/view/<?= $championnat->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir les équipes</a>
								<a href="/championnat/add/<?= $championnat->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/championnat/delete/<?= $championnat->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas de championnat pour le moment</div>
	<?php endif; ?>

	<a href="/championnat/add" class="btn btn-success">Ajouter un nouveau championnat</a>
	<a href="/championnat/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable(); 

		$('.img_pays').tooltip();
	});
</script> 