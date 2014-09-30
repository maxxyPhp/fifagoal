<div class="container">
	<h1 class="page-header">Transferts</h1>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>


	<div class="btn-group btn-group-justified">
		<div class="btn-group">
			<a class="btn btn-success" href="/transfert/add">Transférer un joueur</a>
		</div>
		<div class="btn-group">
			<a class="btn btn-info" href="/transfert/import">Importer des transferts</a>
		</div>
	</div>
</div>