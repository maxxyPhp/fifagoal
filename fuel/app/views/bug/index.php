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

	
	<h1 class="page-header"><i class="fa fa-bug"></i> Signaler un bug</h1>

	<form action="/bug/add" method="post" class="form-horizontal" role="form">
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
			<label for="form-file" class="col-sm-2 control-label">Capture d'écran ou autres</label>
			<div class="col-sm-10">
				<div id="fileuploader">Upload</div>
			</div>
		</div>

		<input type="hidden" name="image" id="form-image">

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="Travaille donc, administrateur" name="add">
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#fileuploader").uploadFile({
			url:window.location.origin+'/bug/uploadImage',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('#form-image').attr('value', data);
			}
		});
	});
</script>