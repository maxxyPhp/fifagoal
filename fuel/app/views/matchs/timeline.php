<div class="row" style="margin-top:20px;">
	<div id="ss-links" class="ss-links">
		<a href="#match">M</a>
		<?php if ($match->prolongation == 1): ?>
			<a href="#prolong">Pr</a>
		<?php endif; ?>

		<?php if ($match->id_tab != 0): ?>
			<a href="#tab">TAB</a>
		<?php endif; ?>
	</div>

	<div id="ss-container" class="ss-container">
		<div class="ss-row">
            <div class="ss-left">
                <h2 id="match">Début</h2>
            </div>
            <div class="ss-right">
                <h2>du match</h2>
            </div>
    	</div>

    	<div class="ss-row ss-medium">
            <div class="ss-left">
               <div class="ss-circle">
               		<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" alt="<?= $match->equipe1->nom ?>" width="90" class="timeline_logo_club">
               </div>
            </div>
            <div class="ss-right">
                 <div class="ss-circle">
                 	<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" alt="<?= $match->equipe2->nom ?>" width="90" class="timeline_logo_club">
                 </div>
            </div>
    	</div>
    	<?php $b = $buteurs; ?>
    	<?php $i = 0; ?>
		<?php foreach ($buteurs as $buteur):  ?>
			<?php $i++; ?>

			<?php if ($i > 1 && $buteur->minute > 90 && !($b->minute > 90) || ($i == 1 && $buteur->minute > 90)): ?>
				<?php if ($buteur->minute > 90): ?>
					<div class="ss-row">
	                    <div class="ss-left">
	                        <h2 id="prolong">Début de la</h2>
	                    </div>
	                    <div class="ss-right">
	                        <h2>prolongation</h2>
	                    </div>
                	</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php $b = $buteur; ?>

			<?php if ($buteur->joueur->equipe->id == $match->equipe1->id): ?>
				<div class="ss-row ss-medium">
					<div class="ss-left">
                        <div class="ss-circle">
                        	<?php if ($buteur->joueur->photo): ?>
                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe1->nom)) . '/' . $buteur->joueur->photo ?>" width="100"/><br>
                        	<?php else: ?>
                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
                        	<?php endif; ?>
                        	<a href="/club/view/<?= $match->equipe1->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . $match->equipe1->logo ?>" width="50" class="logo_buteurs"><br></a>
                        	<?php foreach ($buteur->joueur->pays as $pays): ?>
                        		<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
                        	<?php endforeach; ?>
                        	<div class="label label-<?= $buteur->joueur->poste->couleur ?>"><?= $buteur->joueur->poste->nom ?></div>
                        </div>
                    </div>
                    <div class="ss-right">
                        <h3>
                            <span><i class="fa fa-futbol-o"></i> GOAL</span>
                            <div class="buteurs_view"><?= ucfirst($buteur->joueur->prenom) .' '. strtoupper($buteur->joueur->nom). ' ! '?><small><?= '(' . $buteur->minute .'ème)' ?></small></div>
                        </h3>
                    </div>
				</div>
			<?php else: ?>
				<div class="ss-row ss-medium">
					<div class="ss-left">
						<h3>
                            <span>GOAL <i class="fa fa-futbol-o"></i></span>
                            <div class="buteurs_view"><?= ucfirst($buteur->joueur->prenom) .' '. strtoupper($buteur->joueur->nom). ' ! '?><small><?= '(' . $buteur->minute .'ème)' ?></small></div>
                        </h3>
                    </div>
                    <div class="ss-right">
                        <div class="ss-circle">
                        	<?php if ($buteur->joueur->photo): ?>
	                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe2->nom)) . '/' . $buteur->joueur->photo ?>" width="100"/><br>
	                        <?php else: ?>
                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
                        	<?php endif; ?>
	                        <a href="/club/view/<?= $match->equipe2->id ?>"><img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . $match->equipe2->logo ?>" width="50" class="logo_buteurs"><br></a>
	                       	<?php foreach ($buteur->joueur->pays as $pays): ?>
	                        	<img src="<?= \Uri::base() . \Config::get('upload.pays.path') . '/' . $pays->drapeau ?>" width="30"><br>
	                        <?php endforeach; ?>
	                        <div class="label label-<?= $buteur->joueur->poste->couleur ?>"><?= $buteur->joueur->poste->nom ?></div>
                   		</div>
                    </div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ($match->tab): ?>
			<div class="ss-row">
                <div class="ss-left">
                    <h2 id="tab">Tirs aux</h2>
                </div>
                <div class="ss-right">
                    <h2>buts</h2>
                </div>
        	</div>
        	<?php $i = 0; ?>
        	<?php foreach ($tireurs as $i => $tireur): ?>
        		<?php $i++; ?>
        		<?php if ($i == 11): ?>
        			<div class="ss-row">
	                    <div class="ss-left">
	                        <h2>Mort</h2>
	                    </div>
	                    <div class="ss-right">
	                        <h2>subite</h2>
	                    </div>
                	</div>
        		<?php endif; ?>
        			<div class="ss-row ss-small">
        			<?php if ($tireur->joueur->equipe->id == $match->id_equipe1): ?>
	                    <div class="ss-left">
	                    	<?php if ($tireur->reussi == 1): ?>
	                    		<div class="ss-circle">
	                    	<?php else: ?>
	                    		<div class="ss-circle">
	                    	<?php endif; ?>
	                        	<?php if ($tireur->joueur->photo): ?>
		                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe1->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe1->nom)) . '/' . $tireur->joueur->photo ?>" width="100"/><br>
		                        <?php else: ?>
	                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
	                        	<?php endif; ?>
	                   		</div>
	                    </div>
	                    <div class="ss-right">
	                    	<h3>
	                    		<div class="row">
	                    			<div class="col-md-1">
		                    			<?php if ($tireur->reussi == 1): ?>
		                    				<div class="tab-circle-ok"></div>
		                    			<?php else: ?>
		                    				<div class="tab-circle-rate"></div>
		                    			<?php endif; ?>
	                    			</div>
	                    			<div class="col-md-10"><div class="buteurs_view"><?= ucfirst($tireur->joueur->prenom) .' '. strtoupper($tireur->joueur->nom) ?></div></div>
	                    		</div>
	                    	</h3>
	                    </div>
               	 	<?php else: ?>
               	 		<div class="ss-left">
               	 			<h3>
               	 				<div class="row">
               	 					<div class="col-md-11"><div class="buteurs_view"><?= ucfirst($tireur->joueur->prenom) .' '. strtoupper($tireur->joueur->nom) ?></div></div>
	                    			<div class="col-md-1">
		                    			<?php if ($tireur->reussi == 1): ?>
		                    				<div class="tab-circle-ok"></div>
		                    			<?php else: ?>
		                    				<div class="tab-circle-rate"></div>
		                    			<?php endif; ?>
	                    			</div>
	               	 			</div>
               	 			</h3>
               	 		</div>
	                    <div class="ss-right">
	                        <div class="ss-circle">
	                        	<?php if ($tireur->joueur->photo): ?>
		                        	<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($match->equipe2->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($match->equipe2->nom)) . '/' . $tireur->joueur->photo ?>" width="100"/><br>
		                        <?php else: ?>
	                        		<img src="<?= \Uri::base() . \Config::get('upload.joueurs.path') . '/' . 'notfound.png' ?>" width="100"/><br>
	                        	<?php endif; ?>
	                   		</div>
	                    </div>
	                <?php endif; ?>				            
            	</div>
        	<?php endforeach; ?>
        	<div class="ss-row">
                <div class="ss-left">
                	<?php if ($match->tab->score_joueur1 > $match->score_joueur2): ?>
                		<h2><?= $match->equipe1->nom ?> l'emporte</h2>
                	<?php else: ?>
                    	<h2><?= $match->equipe2->nom ?> l'emporte</h2>
                    <?php endif; ?>
                </div>
                <div class="ss-right">
                    <h2><?= $match->tab->score_joueur1 ?> t.a.b. à <?= $match->tab->score_joueur2 ?></h2>
                </div>
        	</div>
		<?php endif; ?>
		<div class="ss-row">
            <div class="ss-left">
                <h2 id="november">Fin du</h2>
            </div>
            <div class="ss-right">
                <h2>match</h2>
            </div>
    	</div>
	</div>
</div><!-- end timeline -->