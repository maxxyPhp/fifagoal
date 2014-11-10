<div class="container">
	<ol class="breadcrumb">
		<li><a href="/">Accueil</a></li>
		<li><a href="/ligue">Ligues</a></li>
		<li><a href="#"><?= $championnat->nom ?></a></li>
	</ol>

	<h1 class="page-header h3-search-j"><img src="<?= \Uri::base() . \Config::get('upload.championnat.path') . '/' . $championnat->logo ?>" alt="<?= $championnat->nom ?>" width="80"> <?= $championnat->nom ?></h1>
	<div class="row">
		<?php foreach ($championnat->equipes as $equipe): ?>
			<?php if ($equipe->actif == 1): ?>
				<div class="col-sm-6 col-sm-4">
					<div class="thumbnail ligue-thumbnail">
						<a href="/club/view/<?= $equipe->id ?>" style="color:black;"><img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . $equipe->logo ?>" alt="<?= $equipe->nom ?>" width="80" /></a>
						<div class="caption">
							<a href="/club/view/<?= $equipe->id ?>" style="color:black;"><h3 class="h3-search-j"><?= $equipe->nom ?></h3></a>
							<p>
								<?php if ($equipe->isSelection == 0): ?>
									<?= count($equipe->joueurs) ?> joueurs.
								<?php else: ?>
									<?= count($equipe->selectionne) ?> joueurs.
								<?php endif; ?>
							</p>
							<p>
								<?php if (count($equipe->equipe1) + count($equipe->equipe2) > 0): ?>
									Utilisé dans <?= count($equipe->equipe1) + count($equipe->equipe2) ?> match(s)
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("img.lazy").lazyload({
		    effect : "fadeIn"
		});
	});
</script>