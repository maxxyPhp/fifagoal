<?php if ($match->defi->match_valider1 == 1 && $match->defi->match_valider2 == 1): ?>
	<div id="fb-root"></div>
	<div class="row">
		<div class="col-md-2">
			<?php if (!$jaime): ?>
				<a class="btn btn-default btn-like" data-match="<?= $match->id ?>" style="margin-left:50px;"><i class="fa fa-thumbs-o-up"></i> J'applaudis</a>
			<?php else: ?>
				<a class="btn btn-default btn-like" data-match="<?= $match->id ?>" disabled="disabled" style="margin-left:50px;"><i class="fa fa-thumbs-o-up"></i> J'applaudis</a>
			<?php endif; ?>
		</div>
		<div class="col-md-10 nb_like" data-like="<?= count($match->like) ?>">
			<a class="btn-jaime" data-toggle="modal" data-target="#myModal"><i class="fa fa-thumbs-up"></i> <?= count($match->like) ?><?php if ($jaime): ?>  Vous applaudissez.<?php endif; ?></a>
			<a href="#panel-commentaires" style="margin-left:30px;"><i class="fa fa-comments"></i> <?= count($commentaires) ?></a>
			<div style="margin-left:20px;" class="fb-share-button" data-href="<?= \Uri::base() ?>matchs/view/<?= $match->id ?>" data-layout="button_count"></div>
			<div class="bouton-twitter">
				<a href="https://twitter.com/share" class="twitter-share-button" data-lang="fr" data-hashtags="FIFAGOAL">Tweeter</a>
			</div>
		</div>
	</div>
	<hr>
<?php endif; ?>