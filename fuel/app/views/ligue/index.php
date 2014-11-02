<div class="container">
	<h1 class="page-header">Ligues</h1>
	<div class="row">
		<?php foreach ($championnats as $c): ?>
			<div class="col-sm-6 col-sm-4">
				<div class="thumbnail champ-thumbnail">
					<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.championnat.path') . '/' . $c->logo ?>" alt="<?= $c->nom ?>" width="80">
					<div class="caption">
						<a href="/ligue/view/<?= $c->id ?>" style="color:black;"><h3 class="h3-search-j"><?= $c->nom ?></h3></a>
						<p>
							<?php if($c->pays): ?>
								<img class="lazy" data-original="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $c->pays->drapeau ?>" alt="<?= $c->pays->nom ?>" width="50">
							<?php endif; ?></p>
							<?= count($c->equipes) ?> équipes.
					</div>					
				</div>
			</div>
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