<div class="panel panel-default">
	<div class="panel-body">
		<?php echo $title; ?>
		&copy; Copyright <?php echo date('Y');?> 
		

		<a href="http://www.easports.com/fr/fifa/features" class="footer-link partenaires" target="_blank"><i class="fa fa-soccer-ball-o"></i> FIFA 15</a>
		<a href="http://sofifa.com" class="footer-link partenaires" target="_blank"><i class="fa fa-soccer-ball-o"></i> Plus de stats joueurs</a>
	
		<a href="/bug" class="footer-link contact"><i class="fa fa-bug"></i> Signaler un bug</a>
		<a href="/contact" class="footer-link contact"><i class="fa fa-edit"></i> Contacter <?= $title ?></a>
	</div>
</div>

<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<?= \Asset::js('select2/select2.min.js'); ?>
<?= \Asset::js('redactor/redactor.js'); ?>
<?= \Asset::js('uploadFile/jquery.uploadfile.min.js'); ?>
<?= \Asset::js('timeline/jquery.easing.1.3.js'); ?>
<?= \Asset::js('bootstrap-switch/bootstrap-switch.min.js'); ?>
<?= \Asset::js('datepicker/bootstrap-datepicker.js'); ?>
<?= \Asset::js('lazyloading/lazyloading.min.js'); ?>
