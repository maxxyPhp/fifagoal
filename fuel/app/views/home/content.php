<div class="jumbotron">
	<div class="container">
		<?php if (\Auth::check()): ?>
			<h1><i class="fa fa-futbol-o fa-2x"></i> Salut <?= \Auth::get_screen_name() ?></h1>
		<?php else: ?>
			<h1><i class="fa fa-futbol-o fa-2x"></i> Bienvenue <?= $username ?></h1>
		<?php endif; ?>
		<p>Voici FIFAGOAL, l'appli qui te permet de gérer tes résultats FIFA ! </p>
	</div>
</div>