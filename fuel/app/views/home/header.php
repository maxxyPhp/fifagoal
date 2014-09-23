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
				<li class="active"><a href="#">Accueil</a></li>
				<li><a href="#">Statistiques</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Gérer <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Users</a></li>
						<li class="divider"></li>
						<li><a href="#">Equipes</a></li>
						<li><a href="#">Joueurs</a></li>
						<li><a href="#">Transferts</a></li>
					</ul>
				</li>
			</ul>>

			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Recherche">
				</div>
				<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
			</form>

			<ul class="nav navbar-nav navbar-right">
				<button type="button" class="btn btn-default navbar-btn">Inscription</button>
				<button type="button" class="btn btn-primary navbar-btn">Connexion</button>
			</ul>
		</div>
	</div>
</nav>

<div class="logo"></div>
<div class="logo_text"><?php echo $site_title; ?></div>