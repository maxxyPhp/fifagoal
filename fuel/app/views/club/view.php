<div class="container">
	<ol class="breadcrumb">
	<li><a href="/">Accueil</a></li>
	<li><a href="/ligue">Ligues</a></li>
	<li><a href="/ligue/view/<?= $equipe->championnat->id ?>"><?= $equipe->championnat->nom ?></a></li>
	<li><a href="#"><?= $equipe->nom ?></a></li>
	</ol>

	<h1 class="page-header h3-search-j"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . $equipe->logo ?>" alt="<?= $equipe->nom ?>" width="80"> <?= $equipe->nom ?></h1>

	<div class="row">
		<?php if ($equipe->isSelection == 0): ?>
			<?php foreach ($equipe->joueurs as $j): ?>
				<div class="col-sm-6 col-sm-4">
					<div class="thumbnail club-thumbnail">
						<?php if ($j->photo): ?>
							<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($equipe->nom)) . '/' . $j->photo ?>" alt="<?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?>" width="60">
						<?php else: ?>
							<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/notfound.png' ?>" alt="<?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?>" width="60"> 
						<?php endif; ?>
						<div class="caption">
							<h3 class="h3-search-j"><?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?></h3>
							<p>
								<div class="label label-<?= $j->poste->couleur ?>"><?= $j->poste->nom ?></div>
								<?php foreach ($j->pays as $pays): ?>
	                    			<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
	                    		<?php endforeach; ?>
	                    		<?php if ($j->buteurs): ?>
									<?= count($j->buteurs) ?> buts dans les matchs.
								<?php endif; ?>
							
								<?php if ($j->selection): ?>
	                    			<a href="/club/view/<?= $j->selection->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($j->selection->championnat->nom)) . '/' . $j->selection->logo ?>" alt="<?= $j->selection->nom ?>" width="40" data-toggle="tooltip" data-placement="top" title="Equipe nationale <?= $j->selection->nom ?>" class="photo-tooltip" style="float:right;margin-top:-30px;" /></a>
	                    		<?php endif; ?>
							</p>
						</div><!-- .caption -->
					</div><!-- .thumbnail -->
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php foreach ($equipe->selectionne as $j): ?>
				<div class="col-sm-6 col-sm-4">
					<div class="thumbnail club-thumbnail">
						<?php if ($j->photo): ?>
							<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($j->equipe->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($j->equipe->nom)) . '/' . $j->photo ?>" alt="<?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?>" width="60">
						<?php else: ?>
							<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/notfound.png' ?>" alt="<?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?>" width="60"> 
						<?php endif; ?>
						<div class="caption">
							<h3 class="h3-search-j"><?= strtoupper($j->nom).' '.ucfirst($j->prenom) ?></h3>
							<p>
								<div class="label label-<?= $j->poste->couleur ?>"><?= $j->poste->nom ?></div>
								<?php foreach ($j->pays as $pays): ?>
	                    			<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
	                    		<?php endforeach; ?>
	                    		<?php if ($j->buteurs): ?>
									<?= count($j->buteurs) ?> buts dans les matchs.
								<?php endif; ?>
							
								
	                    		<a href="/club/view/<?= $j->equipe->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($j->equipe->championnat->nom)) . '/' . $j->equipe->logo ?>" alt="<?= $j->equipe->nom ?>" width="40" data-toggle="tooltip" data-placement="top" title="<?= $j->equipe->nom ?>" class="photo-tooltip" style="float:right;margin-top:-30px;" /></a>
	                    		
							</p>
						</div><!-- .caption -->
					</div><!-- .thumbnail -->
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.photo-tooltip').tooltip();

		$("img.lazy").lazyload({
		    effect : "fadeIn"
		});
	});
</script>