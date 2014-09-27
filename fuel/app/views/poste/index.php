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

	<h1 class="page-header">Liste des postes</h1>

	<a href="/poste/add" class="btn btn-success">Ajouter un nouveau poste</a>
	<a href="/poste/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
	
	<?php if ($postes): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<th>ID</th>
					<th>Nom</th>
					<th>Actions</th>
				</thead>

				<tbody>
					<?php foreach ($postes as $poste): ?>
						<tr>
							<td><?= $poste->id ?></td>
							<td><?= strtoupper($poste->nom) ?></td>
							<td>	
								<a href="/poste/add/<?= $poste->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/poste/delete/<?= $poste->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas de poste pour le moment</div>
	<?php endif; ?>

	<a href="/poste/add" class="btn btn-success">Ajouter un nouveau poste</a>
	<a href="/poste/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 