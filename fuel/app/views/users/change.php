<div class="container">

	<h1 class="page-header">Changement du mot de passe</h1>

	<form class="form-horizontal" action="/users/change/<?= \Auth::get('id') ?>" method="post" role="form">
		<div class="form-group">
			<label for="oldpass" class="control-label col-sm-2">Mot de passe actuel</label>
			<div class="col-sm-10">
				<input type="password" required="required" class="form-control" value="" id="oldpass" name="oldpass">
			</div>
		</div>

		<div class="form-group">
			<label for="newpass" class="control-label col-sm-2">Nouveau mot de passe</label>
			<div class="col-sm-10">
				<input type="password" required="required" class="form-control" value="" id="newpass" name="newpass">
			</div>
		</div>

		<div class="form-group">
			<label for="confirmnewpass" class="control-label col-sm-2">Confirmez</label>
			<div class="col-sm-10">
				<input type="password" required="required" class="form-control" value="" id="confirmnewpass" name="confirmnewpass">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset col-sm-10">
				<input type="submit" class="btn btn-primary" value="Changer le mot de passe" name="changer">
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#confirmnewpass').on('keyup', function(){
			pass = $('#newpass').val();
			confirm = $(this).val();
			input = $('#confirmnewpass');
			if (pass == confirm){
				input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
				input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
				$('.help-confirm').remove();
				$("input[type=submit]").attr('disabled', false);
			} else {
				input.parent().parent().addClass('has-error has-feddback').removeClass('has-success');
				input.parent().find('span').addClass('glyphicon glyphicon-remove form-control-feedback').removeClass('glyphicon-ok');
				if (!$('.help-confirm').length){
					input.parent().append('<span class="help-block help-confirm">Les mot de passe ne correspondent pas</span>');
				}
				$("input[type=submit]").attr("disabled", "disabled");
			}
		})
	});
</script>