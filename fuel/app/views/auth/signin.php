<div class="row">
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
				<input type="submit" class="btn btn-primary" id="form_register" name="register" value="S'inscrire" />
			</div>
		</div>
	</form>
</div>