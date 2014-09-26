<section class="container">
	<h1 class="page-header">Import de championnat</h1>

	<div class="alert alert-info alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<span class="fa-stack fa-lg">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-info fa-stack-1x fa-inverse"></i>
		</span>
		<strong>Info sur le fichier CSV :</strong><br>
		Il doit contenir une colonne "Nom", une colonne "Logo", et une colonne Pays contenant respectivement le nom du championnat sans caractères accentués, l'url des logo et le nom du pays associé à ce championnat.
	</div>

	<section class="form">
		<form action="/championnat/import" method="post" class="form-horizontal" enctype="multipart/form-data">
		
			<article class="form-group">
				<label id="label_file" for="file" class="control-label col-lg-2">Fichier : </label>
				<section class=" col-xs-4">
					<input name="file" type="file" data-toggle="file-input" title="Choissisez un fichier" class="form-control btn btn-info btn-file" />
				</section>
			</article>

			<input type="submit" value="Envoyer" class="btn btn-primary" id="submit_import_csv" data-loading-text="Chargement..."/>
		</form>
	</section>
</section>

<script>
	$('#submit_import_csv').attr('disabled', true);

	$('.btn-file').on('click', function(){
		$('#submit_import_csv').attr('disabled', false);
	});

	  $('#submit_import_csv').click(function () {
	  		$(this).button('loading');
	  });

</script>