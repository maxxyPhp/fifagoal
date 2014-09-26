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

	<h1 class="page-header">Liste des pays</h1>

	<a href="/pays/add" class="btn btn-success">Ajouter un nouveau pays</a>
	<a href="/pays/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>
	
	<?php if ($pays): ?>
		<section class="table-responsive">
			<table id="myTab" class="table table-hover table-striped">
				<thead>
					<th>ID</th>
					<th>Nom</th>
					<th>Drapeau</th>
					<th>Actions</th>
				</thead>

				<tbody>
					<?php foreach ($pays as $pay): ?>
						<tr>
							<td><?= $pay->id ?></td>
							<td><?= strtoupper($pay->nom) ?></td>
							<td><img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pay->drapeau ?>" alt="<?= $pay->nom ?>" width="100px" height="75px" /></td>
							<td>	
								<a href="/pays/add/<?= $pay->id ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Modifier</a>
								<a href="/pays/delete/<?= $pay->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
	<?php else: ?>
		<div class="alert alert-danger" role="alert"><i class="fa fa-warning"></i> Pas de pays pour le moment</div>
	<?php endif; ?>

	<a href="/pays/add" class="btn btn-success">Ajouter un nouveau pays</a>
	<a href="/pays/import" class="btn btn-info"><i class="fa fa-upload"></i> Import CSV</a>

</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 