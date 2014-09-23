<!DOCTYPE html>
<html lang="fr">
    <!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
    <!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
    <!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
    <!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
        <head>
            <?= \Theme::instance()->view('_templates/head'); ?>
        </head>
        
        <body <?= ($use_wordpress) ? body_class() : ''; ?>>  
            <?= \Theme::instance()->view('_templates/header'); ?>

                    
                    <?= \Theme::instance()->view('_templates/messages'); ?>
                    <?php if(isset($partials['content'])): ?>
                        <?= $partials['content']; ?>
                    <?php endif; ?>

                </section>

            <?php endif; ?>

            <?= \Theme::instance()->view('_templates/footer'); ?>
        </body>
    </html>