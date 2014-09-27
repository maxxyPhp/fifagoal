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

	<h1 class="page-header">Liste des joueurs</h1>

	<a href="/joueur/add" class="btn btn-success">Ajouter un nouveau joueur</a>
	<a href="/joueur/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
	
	<?php if ($joueurs): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<th>ID</th>
					<th>Nom - Prénom</th>
					<th>Poste</th>
					<th>Photo</th>
					<th>Equipe</th>
					<th>Selection</th>
					<th>Actions</th>
				</thead>

				<tbody>
					<?php foreach ($joueurs as $joueur): ?>
						<tr>
							<td><?= $joueur->id ?></td>
							<td><?= strtoupper($joueur->nom) ?> - <?= $joueur->prenom ?></td>
							<td><?= $joueur->poste->nom ?></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . $joueur->photo ?>" alt="<?= $joueur->nom ?> <?= $joueur->prenom ?>" width="75px" height="100px" /></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . $joueur->equipe->logo ?>" alt="<?= $joueur->equipe->nom ?>" width="75px" height="75px" data-toggle="tooltip" data-placement="top" title="<?= $joueur->equipe->nom ?>" /></td>
							<td>
								<?php if ($joueur->id_selection): ?>
									<img src="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $joueur->selection->logo ?>" alt="<?= $joueur->selection->nom ?>" width="75px" height="100px" data-toggle="tooltip" data-placement="top" title="<?= $joueur->selection->nom ?>" />
								<?php endif; ?>
							</td>
							<td>	
								<a href="/joueur/add/<?= $joueur->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/joueur/delete/<?= $joueur->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas de joueurs pour le moment</div>
	<?php endif; ?>

	<a href="/joueur/add" class="btn btn-success">Ajouter un nouveau joueur</a>
	<a href="/joueur/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 