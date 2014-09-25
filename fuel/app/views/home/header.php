<nav class="navbar navbar-inverse" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">FIFAGOAL</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#"><i class="fa fa-home"></i> Accueil</a></li>
				<li><a href="#"><i class="fa fa-line-chart"></i> Statistiques</a></li>
				<?php if (\Auth::check()): ?>
					<li><a href="/profil"><i class="fa fa-desktop"></i> Profil</a>
				<?php endif; ?>
				<?php $group = \Model\Auth_Group::find(6); ?>
				<?php if (\Auth::check() && \Auth::member($group)): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i> Gérer <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/users"><i class="fa fa-user"></i> Users</a></li>
							<li class="divider"></li>
							<li><a href="/pays"><i class="fa fa-flag"></i> Pays</a></li>
							<li><a href="#"><i class="fa fa-male"></i> Equipes</a></li>
							<li><a href="#"><i class="fa fa-group"></i> Joueurs</a></li>
							<li><a href="#"><i class="fa fa-futbol-o"></i> Matchs</a></li>
							<li><a href="#"><i class="fa fa-arrows-h"></i> Transferts</a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>

			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Recherche">
				</div>
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
			</form>

			<ul class="nav navbar-nav navbar-right">
				<?php if (\Auth::check()): ?>
					<a href="/auth/logout" class="btn btn-primary navbar-btn">Déconnexion</a>
				<?php else: ?>
					<a href="/auth/signin" class="btn btn-default navbar-btn">Inscription</a>
					<a href="/auth" class="btn btn-primary navbar-btn">Connexion</a>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>