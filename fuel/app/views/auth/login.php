<div class="container">
    <h1 class="page-header">Se connecter</h1>

        <?php if (\Messages::any()): ?>
            <br/>
            <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

                <?php foreach (\Messages::instance()->get($type) as $message): ?>
                    <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>\n
                <?php endforeach; ?>

            <?php endforeach; ?>
            <?php \Messages::reset(); ?>
        <?php endif; ?>

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
                    <input type="submit" class="btn btn-primary btn-lg" id="form_login" name="login" value="Se connecter" />
                </div>
            </div>
        </form>
</div>