<?php if(\Messages::any()): ?>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<br/>
<?php endif; ?>

<?php foreach(array('error', 'warning', 'success', 'info') as $type): ?>
    <?php foreach(\Messages::instance()->get($type) as $message): ?>
		<?php $type == 'error' and $message['type'] = 'danger'; ?>
        <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php if(\Messages::any()): ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php \Messages::reset(); ?>