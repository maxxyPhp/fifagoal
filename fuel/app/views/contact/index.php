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

	
	<h1 class="page-header"><i class="fa fa-edit"></i> Contacter FIFAGOAL</h1>

	<form action="/contact/add" method="post" class="form-horizontal" role="form">
		<div class="form-group">
			<label for="form-sujet" class="col-sm-2 control-label">Sujet</label>
			<div class="col-sm-10">
				<input type="text" id="form-sujet" class="form-control" required="required">
			</div>
		</div>

		<div class="form-group">
			<label for="form-message" class="col-sm-2 control-label">Message</label>
			<div class="col-sm-10">
				<textarea class="form-control" name="message"></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="Envoyer message" name="add">
			</div>
		</div>
	</form>
</div>