<?php if (\Messages::any()): ?>
    <br/>
    <?php foreach (array('success', 'info', 'warning', 'danger') as $type): ?>

        <?php foreach (\Messages::instance()->get($type) as $message): ?>
            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
        <?php endforeach; ?>

    <?php endforeach; ?>
    <?php \Messages::reset(); ?>
<?php endif; ?>