<div class="row">
    <div class="span6">
        <div class="legend">
            <legend class=""><?= __('login.form.login') ?></legend>
        </div>

        <form class="form-horizontal" action="/auth" accept-charset="utf-8" method="post" role="form"> 
            <div class="form-group">
                <label id='label-username' for="form_username" class="col-sm-2 control-label">Pseudo</label>
                <div class="col-sm-8">
                    <input type="text" required="required" id="form_username" name="username" value="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label id="label_password" for="form_password" class="col-sm-2 control-label">Mot de passe</label>
                <div class="col-sm-8">
                    <input type="password" id="form_password" name="password" value="" class="form-control" />
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
                    <input type="submit" class="btn btn-primary" id="form_login" name="login" value="Se connecter" />
                </div>
            </div>
        </form>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#form-register').slideUp(0);
        $('body').on('click', '.toggle-register-form', function() {
            if ($(this).find('.toggle-register-state').html() == '-') {
                $(this).find('.toggle-register-state').html('+');
                $('#form-register').slideUp(200);
            } else {
                $(this).find('.toggle-register-state').html('-');
                $('#form-register').slideDown(200);
            }
            return false;
        });
    });
</script>