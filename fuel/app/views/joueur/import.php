<section class="container">
	<h1 class="page-header">Import de joueurs</h1>

	<div class="alert alert-info alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<span class="fa-stack fa-lg">
			<i class="fa fa-circle fa-stack-2x"></i>
			<i class="fa fa-info fa-stack-1x fa-inverse"></i>
		</span>
		<strong>Info sur le fichier CSV :</strong><br>
		<p>Il doit contenir huits colonnes :</p>
		<ul>
			<li>"Nom" : le nom du joueur, sans caractères accentués</li>
			<li><span class="label label-info">Optionnel</span> "Prenom" : le prénom du joueur, sans caractères accentués. Peut être vide.</li>
			<li>"Poste" : le poste du joueur (MDC, AT, BU, etc)</li>
			<li>"Photo" : l'URL de la photo du joueur</li>
			<li>"Championnat" : le nom du championnat dans lequel évolue son équipe</li>
			<li>"Equipe" : le nom de l'équipe pour laquelle il joue</li>
			<li><span class="label label-info">Optionnel</span> "Selection" : le nom de la selection qu'il représente. Peut être vide</li>
			<li>"Nationalité" : la nationalité du joueur. Si plusieurs, les séparés par des |</li>
		</ul>
	</div>

	<section class="form">
		<form action="/joueur/import" method="post" class="form-horizontal" enctype="multipart/form-data">
		
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