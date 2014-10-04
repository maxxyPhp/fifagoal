<div class="container">
	<h1 class="page-header">S'inscrire</h1>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>\n
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    \Messages::reset();
	<?php endif; ?>
	
	<form class="form-horizontal" role="form" id="form_register" action="/auth/signin" method="post">
		<div class="form-group">
			<label id="label_username" for="form_username" class="control-label col-sm-2">Pseudo</label>
			<div class="col-sm-8">
				<input type="text" required="required" id="form_username" name="username" value="" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label id="label_name" for="form_fullname" class="control-label col-sm-2">Nom</label>
			<div class="col-sm-8">
				<input type="text" required="required" id="form_fullname" name="fullname" value="" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label id="label_email" for="form_email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-8">
				<input type="text" required="required" id="form_email" name="email" value="" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label id="label_password" for="form_password" class="col-sm-2 control-label">Mot de passe</label>
			<div class="col-sm-8">
				<input type="password" required="required" id="form_password" name="password" value="" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label id="label_confirm" for="form_confirm" class="col-sm-2 control-label">Confirmez</label>
			<div class="col-sm-8">
				<input type="password" required="required" id="form_confirm" name="confirm" value="" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary btn-lg" id="form_register" name="register" value="S'inscrire" />
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_username').on('blur', function(){
			username = $(this).val(),
			$.ajax({
				url : window.location.origin + '/users/api/verifyUsername.json',
				data: 'username='+username,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					input = $('#form_username');
					if (data == false){
						input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
						input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
						// input.parent().find('span').addClass('glyphicon glyphicon-ok form-control-feedback').removeClass('glyphicon-remove');
						$('.help-username').remove();
						$("input[type=submit]").attr('disabled', false);
					} else {
						input.parent().parent().addClass('has-error has-feddback').removeClass('has-success');
						input.parent().find('span').addClass('glyphicon glyphicon-remove form-control-feedback').removeClass('glyphicon-ok');
						if (!$('.help-username').length){
							input.parent().append('<span class="help-block help-username">Ce pseudo est déjà utilisé</span>');
						}
						$("input[type=submit]").attr('disabled', 'disabled');
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		});

		$('#form_fullname').on('blur', function(){
			name = $(this).val(),
			$.ajax({
				url : window.location.origin + '/users/api/verifyName.json',
				data: 'name='+name,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					input = $('#form_fullname');
					if (data != "false"){
						input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
						input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
						// input.parent().find('span').addClass('glyphicon glyphicon-ok form-control-feedback').removeClass('glyphicon-remove');
						$('.help-name').remove();
						$("input[type=submit]").attr('disabled', false);
					} else {
						input.parent().parent().addClass('has-error has-feddback').removeClass('has-success');
						input.parent().find('span').addClass('glyphicon glyphicon-remove form-control-feedback').removeClass('glyphicon-ok');
						if (!$('.help-username').length){
							input.parent().append('<span class="help-block help-name">Le nom doit être une suite de lettre</span>');
						}
						$("input[type=submit]").attr('disabled', 'disabled');
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		});

		$('#form_email').on('blur', function(){
			email = $(this).val();
			input = $('#form_email');
			if (mailValide(email)){
				input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
				input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
				$('.help-mail').remove();
				$("input[type=submit]").attr('disabled', false);
			} else {
				input.parent().parent().addClass('has-error has-feddback').removeClass('has-success');
				input.parent().find('span').addClass('glyphicon glyphicon-remove form-control-feedback').removeClass('glyphicon-ok');
				if (!$('.help-mail').length){
					input.parent().append('<span class="help-block help-mail">Ce mail n\'est pas valide</span>');
				}
				$("input[type=submit]").attr("disabled", "disabled");
			}
		});

		function mailValide(email){
    		var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
 			if(reg.test(email)){
				return(true);
      		} else {
				return(false);
      		}
		}

		$('#form_confirm').on('keyup', function(){
			pass = $('#form_password').val();
			confirm = $(this).val();
			input = $('#form_confirm');
			if (pass == confirm){
				input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
				input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
				$('.help-confirm').remove();
				$("input[type=submit]").attr('disabled', false);
			} else {
				input.parent().parent().addClass('has-error has-feddback').removeClass('has-success');
				input.parent().find('span').addClass('glyphicon glyphicon-remove form-control-feedback').removeClass('glyphicon-ok');
				if (!$('.help-confirm').length){
					input.parent().append('<span class="help-block help-confirm">Les mots de passe ne correspondent pas</span>');
				}
				$("input[type=submit]").attr("disabled", "disabled");
			}
		});
	});
</script>