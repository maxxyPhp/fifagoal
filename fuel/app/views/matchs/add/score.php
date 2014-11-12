<!-- Logos equipes -->
<div class="row">
	<div class="col-md-4 col-xs-6 club club_defieur"></div>
	<div class="col-md-4 col-xs-6" style="text-align:center;"><h1 style="display:inline-block;">vs</h1></div>
	<div class="col-md-4 col-xs-6 club club_defier"></div>
</div>

<div class="score" style="display:none;">
	<div class="row"><h2 class="center-block center">Score</h2></div>
	<div class="row">
		<div class="col-md-6">
			<input type="number" class="form-control" id="score_joueur1" name="score_joueur_1" min="0" max="20" value="0">
		</div>
		<div class="col-md-6">
			<input type="number" class="form-control" id="score_joueur2" name="score_joueur_2" min="0" max="20" value="0">
		</div>
	</div>

	<div class="center-block center" style="margin-top:10px;">
		<input type="checkbox" name="prolongation" id="prolong">
	</div>

	<div class="center-block center" style="margin-top:10px;">
		<input type="checkbox" name="tab" id="tab">
	</div>

	<div id="rapp_tab" class="center-block center" style="display:none;margin-top:10px;">
		<div class="row"><h3 class="center-block center">Tirs aux buts</h3></div>
		<a class="btn btn-primary btn-lg btn-score-tab" data-mode="score"><i class="fa fa-tag fa-2x pull-left"></i> Score</a>
		<a class="btn btn-primary btn-lg btn-score-tab" data-mode="detaille"><i class="fa fa-newspaper-o fa-2x pull-left"></i> Descriptif détaillé</a>

		<input type="hidden" id="mode_tab" name="mode_tab">
		<!-- SCORE -->
		<div class="row tab-score" style="display:none;">
			<div class="col-md-6">
				<input type="number" class="form-control" id="tab_joueur1" name="tab_joueur_1" min="3" max="20" placeholder="Score J1">
			</div>
			<div class="col-md-6">
				<input type="number" class="form-control" id="tab_joueur2" name="tab_joueur_2" min="3" max="20" placeholder="Score J2">
			</div>
		</div>

		<!-- DETAILLE -->
		<div class="row tab-detaille" style="display:none;">
			<div class="col-md-6">
				<input type="number" class="form-control" id="score_tab_joueur1" name="score_tab_joueur_1" min="3" max="20" placeholder="Nb tirs" data-toggle="popover" data-trigger="focus" title="Attention" data-content="Les cases des TAB permettent d'indiquer le nombre de tirs de chaque équipe, et non le score. Vous pourrez ensuite indiquer si certains joueurs ont loupés leurs tirs ou non. Il faut minimum trois tirs pour gagner une séance de TAB.">
			</div>
			<div class="col-md-6">
				<input type="number" class="form-control" id="score_tab_joueur2" name="score_tab_joueur_2" min="3" max="20" placeholder="Nb tirs">
			</div>
		</div>

		<div class="row tab-tireurs-detail" style="margin-top:10px;">
			<div class="col-md-6">
				<div class="list-tireurs-defieur" style="display:none;"></div>
			</div>
			<div class="col-md-6">
				<div class="list-tireurs-defier" style="display:none;"></div>
			</div>
		</div>
	</div>
</div>


<div class="row valid_match">
	<input type="submit" name="add" value="Valider le match" class="btn btn-primary btn-lg btn-block" disabled="disabled">
</div>