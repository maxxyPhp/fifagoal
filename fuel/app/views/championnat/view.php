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

	<h1 class="page-header">Liste des équipes de <?= strtoupper($championnat->nom) ?></h1>

	<a href="/equipe/add" class="btn btn-success">Ajouter une nouvelle équipe</a>
	<a href="/equipe/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
	
	<?php if ($equipes): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<th></th>
					<th>Nom</th>
					<th>Logo</th>
					<th>Championnat</th>
					<th>Actions</th>
				</thead>

				<tbody>
					<?php foreach ($equipes as $equipe): ?>
						<tr>
							<td>
								<?php if ($equipe->actif == 1): ?>
									<i class="fa fa-check fa-2x"></i>
								<?php else: ?>
									<i class="fa fa-close fa-2x"></i>
								<?php endif; ?>
							</td>
							<td><?= strtoupper($equipe->nom) ?> - <?= strtoupper($equipe->nom_court) ?></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . $equipe->logo ?>" alt="<?= $equipe->nom ?>" width='60px' /></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.championnat.path') . '/' . $equipe->championnat->logo ?>" alt="<?= $equipe->championnat->logo ?>" width="60px" data-toggle="tooltip" data-placement="top" title="<?= $equipe->championnat->nom ?>" /></td>
							<td>
								<a href="/equipe/activate/<?= $equipe->id ?>" class="btn btn-primary">
									<?php if ($equipe->actif == 1): ?>
										<i class="fa fa-close"></i> Désactiver
									<?php else: ?>
										<i class="fa fa-check"></i> Activer
									<?php endif; ?>
								</a>
								<a href="/equipe/view/<?= $equipe->id ?>" class="btn btn-info"><i class="fa fa-eye"></i> Voir les joueurs</a>
								<a href="/equipe/add/<?= $equipe->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/equipe/delete/<?= $equipe->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas d'équipe pour le moment</div>
	<?php endif; ?>

	<a href="/equipe/add" class="btn btn-success">Ajouter une nouvelle équipe</a>
	<a href="/equipe/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 