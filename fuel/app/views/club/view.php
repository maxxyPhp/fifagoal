<div class="container">
	<h1 class="page-header h3-search-j"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . $equipe->logo ?>" alt="<?= $equipe->nom ?>" width="80"> <?= $equipe->nom ?></h1>

	<div class="row">
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
                    			<img src="<?= \Uri::base() . \Config::get('upload.selections.path') . '/' . $j->selection->logo ?>" alt="<?= $j->selection->nom ?>" width="40" data-toggle="tooltip" data-placement="top" title="Equipe nationale <?= $j->selection->nom ?>" class="photo-tooltip" style="float:right;margin-top:-30px;" />
                    		<?php endif; ?>
						</p>
					</div><!-- .caption -->
				</div><!-- .thumbnail -->
			</div>
		<?php endforeach; ?>
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