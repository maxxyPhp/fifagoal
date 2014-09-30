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

	<h1 class="page-header">Liste des selections</h1>

	<a href="/selection/add" class="btn btn-success">Ajouter une nouvelle selection</a>
	<a href="/selection/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
	
	<?php if ($selections): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<th>ID</th>
					<th>Nom</th>
					<th>Logo</th>
					<th>Pays</th>
					<th>Actions</th>
				</thead>

				<tbody>
					<?php foreach ($selections as $selection): ?>
						<tr>
							<td><?= $selection->id ?></td>
							<td><?= strtoupper($selection->nom) ?></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $selection->logo ?>" alt="<?= $selection->nom ?>" width="80px" /></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $selection->pays->drapeau ?>" alt="<?= $selection->pays->nom ?>" width="80px" data-toggle="tooltip" data-placement="top" title="<?= $selection->pays->nom ?>" /></td>
							<td>	
								<a href="/selection/add/<?= $selection->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/selection/delete/<?= $selection->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas de selection pour le moment</div>
	<?php endif; ?>

	<a href="/selection/add" class="btn btn-success">Ajouter une nouvelle selection</a>
	<a href="/selection/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 