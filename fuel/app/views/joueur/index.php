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
							<td>
								<?php if($joueur->pays): ?>
									<?php foreach ($joueur->pays as $pays): ?>
										<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" alt="<?= $pays->nom ?>" width="20px" height="20px" />
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td><?= strtoupper($joueur->nom) ?> - <?= ucfirst($joueur->prenom) ?></td>
							<td>
								<div class="label label-<?= $joueur->poste->couleur ?>"><?= $joueur->poste->nom ?></div>
							</td>
							<td>
								<?php if ($joueur->photo): ?>
									<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', lcfirst($joueur->equipe->championnat->nom)) . '/' . str_replace(' ', '_', $joueur->equipe->nom) . '/' . $joueur->photo ?>" alt="<?= $joueur->nom ?> <?= $joueur->prenom ?>" width="60px" height="60px" />
								<?php else: ?>
									<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/notfound.png' ?>" alt="Not found" width="60px" />
								<?php endif; ?>
							</td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($joueur->equipe->championnat->nom)) . '/' . $joueur->equipe->logo ?>" alt="<?= $joueur->equipe->nom ?>" width="60px" data-toggle="tooltip" data-placement="top" title="<?= $joueur->equipe->nom ?>" /></td>
							<td>
								<?php if ($joueur->selection): ?>
									<img src="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $joueur->selection->logo ?>" alt="<?= $joueur->selection->nom ?>" width="60px" data-toggle="tooltip" data-placement="top" title="<?= $joueur->selection->nom ?>" />
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