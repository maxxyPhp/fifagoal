<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<?php if ($notifs > 0): ?>
	<title><?= '['.$notifs.'] '?><?php echo $title; ?></title>
<?php else: ?>
	<title><?php echo $title; ?></title>
<?php endif; ?>
<meta name="description" content="<?php echo $description; ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 

<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?= \Asset::css('style.css'); ?>
<?= \Asset::css('bootstrap/bootstrap.min.css'); ?>
<?= \Asset::css('font-awesome/css/font-awesome.min.css') ?>
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.css">
<?= \Asset::css('select2/select2.css'); ?>
<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans' rel='stylesheet' type='text/css'>
<?= \Asset::css('animate.css'); ?>
<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Hammersmith+One' rel='stylesheet' type='text/css'>
<?= \Asset::css('redactor/redactor.css'); ?>
<?= \Asset::css('uploadFile/uploadfile.css'); ?>
<?= \Asset::css('timeline/style.css'); ?>
<link href='http://fonts.googleapis.com/css?family=Exo' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<?= \Asset::css('bootstrap-switch/bootstrap-switch.min.css'); ?>
<link href='http://fonts.googleapis.com/css?family=Rock+Salt' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Fugaz+One' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>

<?= \Asset::js('jquery/jquery-2.1.1.min.js') ?>
<?= \Asset::js('bootstrap/bootstrap.min.js') ?>
<?= \Asset::js('timeline/modernizr.custom.11333.js') ?>


