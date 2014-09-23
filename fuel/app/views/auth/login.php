HELLOOOOOO
<div class="row-fluid">
<div class="span6">
    <div class="legend">
        <legend class=""><?= __('login.form.login') ?></legend>
    </div>

    <?= \Form::open(array('class' => 'form-horizontal')); ?>
    <?= $login_form->field('username')->set_attribute(array('class' => 'form-control')); ?>
    <?= $login_form->field('password')->set_attribute(array('class' => 'form-control')); ?>
    <?= $login_form->field('remember_me'); ?>
    <?= $login_form->field('login'); ?>
    <?= \Form::close(); ?>
</div>

<?php if($oauthList): ?>
<div class="span6">
    <div class="legend">
        <legend class=""><?= __('login.social-network') ?> :</legend>
    </div>

    <?= __('login.login-with') ?>:
    <ul>
    <?php foreach($oauthList as $strategy_name => $value): ?>
            <li><a href="<?= \Uri::get('auth_oauth', array('provider' => strtolower($strategy_name))); ?>"><?= $strategy_name ?></a></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
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