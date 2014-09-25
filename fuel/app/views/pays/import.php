<section class="container">
	<h1 class="page-header">Import de pays</h1>
	<section class="form">
		<form action="/pays/import" method="post" class="form-horizontal" enctype="multipart/form-data">
		
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