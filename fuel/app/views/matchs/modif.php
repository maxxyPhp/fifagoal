<div class="container">
	<h1 class="center-block center">MODIFICATION D'UN RAPPORT DE MATCH</h1>
	<div class="row rapport-match">
		<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<input type="hidden" name="defi" value="<?= $match->defi->id ?>">
		<input type="hidden" name="match" value="<?= $match->id ?>">
		<input type="hidden" name="modifieur" value="<?= \Auth::get('id') ?>">

		<!-- DEFIEUR -->
		<div class="col-md-4">
			<?= render('matchs/modif/defieur', array('photo_defieur' => $photo_defieur, 'pays' => $pays, 'championnats' => $championnats, 'match' => $match, 'buteurs' => $buteurs)); ?>
		</div>

		<!-- SCORE -->
		<div class="col-md-4">
			<?= render('matchs/modif/score', array('match' => $match, 'tireurs' => $tireurs, 'nb_tireurs_dom' => $nb_tireurs_dom, 'nb_tireurs_ext' => $nb_tireurs_ext)); ?>
		</div>


		<!-- DEFIER -->
		<div class="col-md-4">
			<?= render('matchs/modif/defier', array('photo_defier' => $photo_defier, 'pays' => $pays, 'championnats' => $championnats, 'match' => $match, 'buteurs' => $buteurs)); ?>
		</div>

		<?= \Form::close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var max_minute;

		$('#score_tab_joueur1').popover({
			animation: true,
			placement: 'top',
		});
		
		if (<?= $match->prolongation ?> == 1){
			max_minute = 120;
		} else max_minute = 90;

		$('.buteurs').select2({
			width: '240px'
		});

		$('.tireurs').select2({
			width: '140px'
		})

		$('#prolong').bootstrapSwitch({
			size: 'normal',
			onText: 'Oui',
			offText: 'Non',
			labelText: 'Prolong',
			onSwitchChange: function (event, state){
				if (state){
					max_minute = 120;
					$(':input[type="number"]').attr('max', 120);
				} else {
					max_minute = 90;
					$(':input[type="number"]').attr('max', 90);
					$(':input[type="number"]').each(function(index){
						if ($(this).val() > 90){
							$(this).val(90);
						}
					});
				} 
			}
		});

		// Switch TAB
		$('#tab').bootstrapSwitch({
			size: 'normal',
			onText: 'Oui',
			offText: 'Non',
			labelText: 'TAB ?',
			onSwitchChange: function (event, state){
				//OUI
				if (state){
					if ($('#score_joueur1').val() == $('#score_joueur2').val()){
						$('#rapp_tab').show();
					} else {
						alert('Pour effectuer une séance de tirs aux buts, le score doit être nul.');
						$('#tab').bootstrapSwitch('state', false);
					}
				} else {
					$('#rapp_tab').hide();
				} 
			}
		});

		if ($('#mode_tab').val() == 'detaille'){
			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == 'detaille'){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		} else {
			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == 'score'){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		}


		$('#form_championnat_defieur, #form_championnat_defier').select2({
			placeholder: "Selectionnez un championnat",
			width: '300px'
		});

		$('#form_equipe_defieur, #form_equipe_defier').select2({
			placeholder: "Selectionnez une équipe",
			width: '300px'
		});

		$('#form_championnat_defieur').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defieur'), $('#div_equipes_defieur'));
			$('.logo_club_defieur').remove();
			resetAll ($('.list-buteurs-defieur > div').length, $('.list-tireurs-defieur > div').length, '#buteurs-dom-', '#tireurs-dom-');
		});


		$('#form_championnat_defier').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_defier'), $('#div_equipes_defier'));
			$('.logo_club_defier').remove();
			resetAll ($('.list-buteurs-defier > div').length, $('.list-tireurs-defier > div').length, '#buteurs-ext-', '#tireurs-ext-');
		});


		/**
		 * afficherEquipes
		 * Affiche le nom des équipes d'un championnat
		 *
		 * @param int id_championnat
		 * @param Noeud select : le select contenant le nom des équipes
		 * @param Noeud afficher : la div contenant le select
		 */
		function afficherEquipes (id_championnat, select, afficher){
			$.ajax({
				url : window.location.origin + '/equipe/api/getEquipes.json',
				data: 'id_championnat='+id_championnat,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						select.select2('val', '');
						select.html('');
						equipe = data;
						for (var i in equipe){
							select.append('<option value="'+equipe[i]['id']+'">'+equipe[i]['nom']+'</option>');
						}
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}

		/**
		 *
		 * CHOIX D'UNE EQUIPE
		 *
		 */
		$('#form_equipe_defieur').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defieur'), 'logo_club_defieur', 'list-buteurs-defieur', $('#score_joueur1').val(), 'buteurs-domicile', 'dom', 'defieur');
			resetButeurs ($('.list-buteurs-defieur > div').length, $('#form_equipe_defieur').val(), '#buteurs-dom-');
			resetTireurs($('.list-tireurs-defieur > div').length, $('#form_equipe_defieur').val(), '#tireurs-dom-');
		});

		$('#form_equipe_defier').on('change', function(){
			clickEquipe ($(this).val(), $('.club_defier'), 'logo_club_defier', 'list-buteurs-defier', $('#score_joueur2').val(), 'buteurs-exterieur', 'ext', 'defier');
			resetButeurs ($('.list-buteurs-defier > div').length, $('#form_equipe_defier').val(), '#buteurs-ext-');
			resetTireurs($('.list-tireurs-defier > div').length, $('#form_equipe_defier').val(), '#tireurs-ext-');
		});

		/**
		 * ClickEquipe
		 * Affiche le logo de l'équipe sélectionnée
		 *
		 * @param id_equipe : l'id de l'équipe sélectionnée
		 * @param div_equipe : la div où l'image doit apparaître
		 * @param classImg : la classe donnée à l'image
		 */
		function clickEquipe (id_equipe, div_equipe, classImg, listButeurs, score, classButeurs, ordre, joueur){
			$('.'+classImg).remove();
			$.ajax({
				url: window.location.origin + '/equipe/api/getEquipe.json',
				data: 'id_equipe=' + id_equipe,
				dataType: 'json',
				type: 'get',
				success : function(data){
					div_equipe.append(
						'<img src="'+window.location.origin+'/upload/equipes/'+data[9]+'/'+data[2]+'" alt="'+data[0]+'" width="100px" class="'+classImg+'" />'
					);

					if ($('#form_equipe_defieur').val() != 0 && $('#form_equipe_defier').val() != 0){
						$('.score').show();
						$('input:submit').attr('disabled', false);
					}

					$('.'+listButeurs).html('');
					actionScore(score, $('.'+listButeurs+' > div').length, listButeurs, classButeurs, ordre, $('#form_equipe_'+joueur).val());
					// $('.list-buteurs-defieur').html('');
					
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		}

		/**
		 *
		 * GESTION DES BUTEURS
		 *
		 */
		$('#score_joueur1').on('click blur', function(){
			actionScore($(this).val(), $('.list-buteurs-defieur > div').length, 'list-buteurs-defieur', 'buteurs-domicile', 'dom', $('#form_equipe_defieur').val());
		});

		$('#score_joueur2').on('click blur', function(){
			actionScore($(this).val(), $('.list-buteurs-defier > div').length, 'list-buteurs-defier', 'buteurs-exterieur', 'ext', $('#form_equipe_defier').val());
		});

		/**
		 * actionScore
		 * Détermine l'action à effectuer pendant évenement score
		 *
		 * @param int score
		 * @param int nb_element : le nombre de select déjà présente
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int idEquipe : l'id de l'équipe sélectionnée
		 */
		function actionScore (score, nb_element, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score == nb_element){
				return false;
			} else if (score < nb_element){
				for (var i = nb_element; i > score; i--){
					$('.'+listeJoueur).children().each(function(){
						if ($(this).hasClass('buteurs-'+ordre+'-'+i) || i == 1){
							$(this).remove();
						}
					});
				}
			} else {
				for (var i = nb_element+1; i <= score; i++){
					afficherChoixButeurs(i, listeJoueur, nomDivGen, ordre, idEquipe);
				}
			}
		}
				


		/**
		 * afficherChoixButeurs
		 * Affiche des listes déroulantes avec le nom des joueurs de l'équipes sélectionnée
		 *
		 * @param int score
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int selectEquipe : l'id de l'équipe sélectionnée
		 */
		function afficherChoixButeurs (score, listeJoueur, nomDivGen, ordre, selectEquipe){
			if (score > 0){
				$('.'+listeJoueur).append(
					'<div class="form-group '+nomDivGen+' buteurs-'+ordre+'-'+score+' animated fadeInUp" style="display:none;">'
						+'<div class="col-sm-8">'
							+'<select id="buteurs-'+ordre+'-'+score+'" name="buteurs-'+ordre+'['+score+']" class="buteurs">'
								+'<option></option>'
							+'</select>'
						+'</div>'
						+'<div class="col-sm-4">'
							+'<input type="number" name="minute_'+ordre+'_buteur['+score+']" min="1" max="'+max_minute+'" class="form-control buteurs-'+ordre+'-'+score+'" placeholder="Minute" style="display:none;">'
						+'</div>'
					+'</div>'
				);

				afficherJoueurs(selectEquipe, $('#buteurs-'+ordre+'-'+score));

				$('#buteurs-'+ordre+'-'+score).select2({
					placeholder: "Selectionnez un joueur",
					width: '230px'
				});

				if ($('.'+listeJoueur+' > h4').length == 0){
					$('.'+listeJoueur).prepend('<h4>Liste des buteurs :</h4>');
				}

				$('.'+nomDivGen).show();
				$('.'+listeJoueur).show();
			}
		}


		/**
		 * afficherJoueur
		 * Fais une requête pour obtenir les joueurs d'une équipe
		 *
		 * @param int id_equipe
		 * @param element select : le select qui contiendra les noms des joueurs
		 */
		function afficherJoueurs (id_equipe, select, context){
			$.ajax({
				url : window.location.origin + '/joueur/api/getJoueurs.json',
				data: 'id_equipe='+id_equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						select.html('');
						
						if (context == 'but'){
							select.select2('val', '');
							select.append('<option></option>');
							select.select2({
								placeholder: "Selectionnez un joueur",
								width: '230px'
							});
						}
						else if (context == 'tir_reset'){
							select.select2('val', '');
							select.append('<option></option>');
							select.select2({
								placeholder: "Tireur",
								width: '140px'
							});
						}
						joueur = data;
						for (var i in joueur){
							select.append('<option value="'+joueur[i]['id']+'">'+joueur[i]['nom'].toUpperCase()+' '+joueur[i]['prenom'].charAt(0).toUpperCase() + joueur[i]['prenom'].substring(1).toLowerCase()+'</option>');
						}
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}

		// Choix d'un buteur, focus sur la minute
		$('body').on('change', '.buteurs', function(){
			id = $(this).attr('id');
			$('.'+id).show().focus();
		});

		/**
		 *
		 * GESTION DES TAB
		 *
		 */
		 // Choix du mode de TAB
		$('.btn-score-tab').on('click', function(){
			mode = $(this).attr('data-mode');
			btn = $(this);
			if (mode == 'score'){
				$('.tab-detaille').hide();
				$('.tab-tireurs-detail').hide();
				$('.tab-score').show();
				$('#mode_tab').attr('value', 'score');
			} else {
				$('.tab-score').hide();
				$('.tab-detaille').show();
				$('.tab-tireurs-detail').show();
				$('#mode_tab').attr('value', 'detaille');
			}

			$('.btn-score-tab').each(function(){
				if ($(this).attr('data-mode') == mode){
					$(this).addClass('btn-success').removeClass('btn-primary');
				} else $(this).addClass('btn-primary').removeClass('btn-success');
			});
		});

		$('#score_tab_joueur1').on('click blur', function(){
			actionTAB($(this).val(), $('.list-tireurs-defieur > div').length, 'list-tireurs-defieur', 'tireurs-domicile', 'dom', $('#form_equipe_defieur').val());
		});

		$('#score_tab_joueur2').on('click blur', function(){
			actionTAB($(this).val(), $('.list-tireurs-defier > div').length, 'list-tireurs-defier', 'tireurs-exterieur', 'ext', $('#form_equipe_defier').val());
		});

		/**
		 * actionTAB
		 * Affiche ou supprime des listes déroulantes pour indiquer le tireur de pénalty
		 *
		 * @param int score
		 * @param int nb_element
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int idEquipe : l'id de l'équipe selectionnée
		 */
		function actionTAB (score, nb_element, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score == nb_element){
				return false;
			} else if (score < nb_element){
				for (var i = nb_element; i > score; i--){
					$('.'+listeJoueur).children().each(function(){
						if ($(this).hasClass('tireurs-'+ordre+'-'+i) || i == 1){
							$(this).remove();
						}
					});
				}
			} else {
				for (var i = nb_element+1; i <= score; i++){
					afficherChoixTireurs(i, listeJoueur, nomDivGen, ordre, idEquipe);
				}
			}
		}


		/**
		 * afficherChoixTireurs
		 * Affiche des listes déroulantes permettant d'indiquer le nom du tireur de péno
		 *
		 * @param int score
		 * @param String listeJoueur : la div qui contiendra les listes déroulantes
		 * @param String nomDivGen : la classe de la div qui contient un select
		 * @param String ordre : 'dom' ou 'ext'
		 * @param int idEquipe : l'id de l'équipe selectionnée
		 */
		function afficherChoixTireurs (score, listeJoueur, nomDivGen, ordre, idEquipe){
			if (score > 0){
				$('.'+listeJoueur).append(
					'<div class="form-group '+nomDivGen+' tireurs-'+ordre+'-'+score+' animated fadeInUp" style="display:none;">'
						+'<div class="col-sm-10">'
							+'<select id="tireurs-'+ordre+'-'+score+'" name="tireurs-'+ordre+'['+score+']" class="tireurs">'
								+'<option></option>'
							+'</select>'
						+'</div>'
						+'<div class="col-sm-2">'
							+'<input type="checkbox" name="tireurs_'+ordre+'_reussite['+score+']" class="tireurs-'+ordre+'_'+score+'">'
						+'</div>'
					+'</div>'
				);

				afficherJoueurs(idEquipe, $('#tireurs-'+ordre+'-'+score));

				$('#tireurs-'+ordre+'-'+score).select2({
					placeholder: "Tireur #"+score,
					width: '140px'
				});

				$('.'+nomDivGen).show();
				$('.'+listeJoueur).show();
			}
		}
	});
</script>
<?= \Asset::js('helper/matchs/resetAll.js'); ?>