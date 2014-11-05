<div class="container">
	<div class="panel panel-default panel-home">
		<div class="panel-body">
			<h1 class="site_title"><i class="fa fa-futbol-o"></i> <?= $title ?></h1>
			<p class="paragraphe-home">Tu es un joueur de FIFA, un joueur qui aime gagner, qui produit du jeu ou au contraire qui joue le contre ?<br>
			Prendre des grandes équipes comme le Bayern ou le Real, c'est bien. Mais sais tu gagner en prenant des équipes plus modestes du style Evian TG ou Getafe ?<br>
			Viens donc nous le prouver, et fais toi un nom dans la communauté des fans de FIFA.</p>
			<h2 style="font-family: 'Rock Salt', cursive;">The future depends on you !*</h2><br>
			<p>
				<a class="btn btn-default btn-lg btn-block btn-connect" data-toggle="modal" data-target="#modalConnect">Rentre sur le terrain</a>
				<a class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modalInsc">Rejoins la communauté</a>
			</p>

			<p class="trad_acc">*Le futur dépend de toi</p>
		</div>
	</div>
</div>
	

<div id="modalConnect" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    			<h4><i class="fa fa-user"></i> Se connecter</h4>
    		</div>
    		<div class="modal-body">
		        <form class="form-horizontal" action="/auth" accept-charset="utf-8" method="post" role="form">
		        	<div class="col-sm-2"></div>
		        	<div class="col-sm-8">
			        	<div class="input-group margin-bottom-sm">
			        		<span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
			        		<input type="text" class="form-control" id="form_mail" name="username" required="required" placeholder="Adresse email" />
			        	</div>
			        	<br>
			        	<div class="input-group">
			        		<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
			        		<input type="password" class="form-control" id="form_password" name="password" required="required" placeholder="Mot de passe" />
			        	</div>
		        	</div>


		            <div class="form-group">
		                <div class="col-sm-offset-2 col-sm-10">
		                    <div class="checkbox">
		                        <label>
		                            <input type="checkbox" value="1" id="form_remember_me">Se souvenir de moi
		                        </label>
		                    </div>
		                </div>
		            </div>

		            <div class="form-group">
		                <div class="col-sm-offset-2 col-sm-10">
		                    <input type="submit" class="btn btn-primary btn-lg" id="form_login" name="login" value="Se connecter" />
		                </div>
		            </div>
		        </form>
			</div>
    	</div>
  	</div>
</div>

<div id="modalInsc" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    			<h4><i class="fa fa-futbol-o"></i> Nous rejoindre</h4>
    		</div>
    		<div class="modal-body">
    			<form class="form-horizontal" role="form" id="form_register" action="/auth/signin" method="post">
					<div class="form-group">
						<label id="label_username" for="form_username" class="control-label col-sm-2">Pseudo</label>
						<div class="col-sm-8">
							<input type="text" required="required" id="form_username" name="username" value="" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label id="label_naissance" for="form_naissance" class="col-sm-2 control-label">Naissance</label>
						<div class="col-sm-8">
							<input type="text" required="required" id="form_naissance" name="naissance" value="" class="form-control datepicker" />
						</div>
					</div>

					<div class="form-group">
						<label id="label_email" for="form_email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-8">
							<input type="text" required="required" id="form_email" name="email" value="" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						<label id="label_password" for="form_password_register" class="col-sm-2 control-label">Mot de passe</label>
						<div class="col-sm-8">
							<input type="password" required="required" id="form_password_register" name="password" value="" class="form-control" />
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
    	</div>
  	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('body').on('blur', '#form_username', function(){
			input = $(this);
			console.log(input);
			username = $(this).val();
			$.ajax({
				url : window.location.origin + '/users/api/verifyUsername.json',
				data: 'username='+username,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data == false){
						console.log("false");
						input.parent().parent().addClass('has-success has-feedback').removeClass('has-error');
						input.parent().append('<span class="feedback-username glyphicon glyphicon-ok form-control-feedback"></span>');
						// input.parent().find('span').addClass('glyphicon glyphicon-ok form-control-feedback').removeClass('glyphicon-remove');
						$('.help-username').remove();
						$("input[type=submit]").attr('disabled', false);
					} else {
						console.log("true");
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
			pass = $('#form_password_register').val();
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

		$('#form_naissance').datepicker({
			format: 'dd/mm/yyyy',
			todayBtn: true,
			todayHightlight: true,
			language: 'fr-FR',
			autoclose: true,
			endDate: 'today',
		});

		// $('#form_naissance').on('click', function(){
		// 	$('.datepicker-dropdown').css('z-index', 1050);
		// });
	});
</script>
<?= \Asset::js('datepicker/bootstrap-datepicker.js'); ?>