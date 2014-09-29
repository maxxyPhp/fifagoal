<div class="container">
	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>

	<h1 class="page-header">Faire un transfert</h1>
		<?= \Form::open(array('class' => 'form-horizontal')) ?>
		<div class="row">

			<!-- CLUB VENDEUR -->
			<div class="col-md-6">
				<h2>Club vendeur</h2>
				<div class="form-group">
					<div class="col-sm-10">
						<select id="form_championnat">
							<option></option>
							<?php foreach ($pays as $pay): ?>
								<optgroup label="<?= $pay->nom ?>">
								<?php foreach ($championnats as $championnat): ?>
									<?php if ($championnat->id_pays == $pay->id): ?>
										<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group" style="display:none;" id="div_equipes">
					<div class="col-sm-10">
						<select id="form_equipe" name="id_equipe">
							<option></option>
						</select>
					</div>
				</div>

				<div class="form-group" style="display:none;" id="div_joueurs">
					<div class="col-sm-10">
						<select id="form_joueurs" name="id_joueur">
							<option></option>
						</select>
					</div>
				</div>
			</div>

			<!-- CLUB ACHETEUR -->
			<div class="col-md-6">
				<h2>Club acheteur</h2>
				<div class="form-group">
					<div class="col-sm-10">
						<select id="form_championnat_acheteur">
							<option></option>
							<?php foreach ($pays as $pay): ?>
								<optgroup label="<?= $pay->nom ?>">
								<?php foreach ($championnats as $championnat): ?>
									<?php if ($championnat->id_pays == $pay->id): ?>
										<option value="<?= $championnat->id ?>"><?= $championnat->nom ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group" style="display:none;" id="div_equipes_acheteur">
					<div class="col-sm-10">
						<select id="form_equipe_acheteur" name="id_equipe">
							<option></option>
						</select>
					</div>
				</div>
			</div>

			
		</div>

		<div class="row">
			<div class="col-md-4 club_vendeur"></div>
			<div class="col-md-4">
				<i class="fa fa-arrow-right fa-5x" style="display:none;"></i><br>
				<a class="btn btn-primary btn-lg btn-submit" style="margin-top:5px;" disabled="disabled">Transférer</a>
				<section id="search_progress" style="display: none;">
					<div class="panel panel-default">
						<div class="panel-body">
							<i class="fa fa-spinner fa-spin fa-2x"></i> <br>
						</div>
					</div>
				</section>
			</div>

			<div class="col-md-4 club_acheteur"></div>
		</div>
		

		<?= \Form::close(); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#form_championnat').select2({
			placeholder: "Selectionnez un championnat",
			allowClear: true,
			width: '300px'
		});

		$('#form_equipe').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});

		$('#form_joueurs').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});

		$('#form_championnat_acheteur').select2({
			placeholder: "Selectionnez un championnat",
			allowClear: true,
			width: '300px'
		});

		$('#form_equipe_acheteur').select2({
			placeholder: "Selectionnez une équipe",
			allowClear: true,
			width: '300px'
		});

		$('#form_championnat').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe'), $('#div_equipes'));
		});

		$('#form_championnat_acheteur').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe_acheteur'), $('#div_equipes_acheteur'));
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
						select.html('');
						equipe = data;
						for (var i in equipe){
							select.append('<option value="'+equipe[i]['id']+'">'+equipe[i]['nom']+'</option>');
						}
						afficher.show();
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}

		// Clic sur l'équipe vendeuse
		$('#form_equipe').on('change', function(){
			id_equipe = $(this).val();
			$.ajax({
				url: window.location.origin + '/equipe/api/getJoueurs.json',
				data: 'id_equipe=' + id_equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					if (data != 'KO'){
						joueur = data;
						for (var i in joueur){
							$('#form_joueurs').append('<option value="'+joueur[i]['id']+'">'+joueur[i]['nom'].toUpperCase()+' '+joueur[i]['prenom'].charAt(0).toUpperCase()+joueur[i]['prenom'].substring(1).toLowerCase()+'</option>');
						}
						$('#div_joueurs').show();
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		});

		// Clic sur les joueurs
		$('#form_joueurs').on('change', function(){
			$('.info_joueur').remove();
			id_joueur = $(this).val();
			$.ajax({
				url: window.location.origin + '/joueur/api/getInfo.json',
				data: 'id_joueur=' + id_joueur,
				type: 'get',
				dataType : 'json',
				success: function(data){
					if (data != 'KO'){
	
						joueur = data;
						var pays = '';
						for (var i in joueur[2]){
							pays += '<img src="'+window.location.origin + '/upload/pays/'+joueur[2][i]+'" width=30px" style="margin-left:5px;" />';
						}

						$('.club_vendeur').append(
							'<div class="info_joueur">'
								+'<h3>'+joueur[3].toUpperCase()+' '+joueur[4].charAt(0).toUpperCase()+joueur[4].substring(1).toLowerCase()+'</h3>'
								+'<img src="'+window.location.origin + '/upload/joueurs/'+joueur[1]+'/'+joueur[13]+'/'+joueur[6]+'" alt="'+joueur[3]+'" width="80px" />'
								+'<img src="'+window.location.origin + '/upload/equipes/'+joueur[1]+'/'+joueur[13]+'.png" alt="'+joueur[13]+'" width="50px" /><br>'
								+'<span class="label label-'+joueur[0]+'">'+joueur[12] +'</span>'+ pays
							+'</div>'	
						);

						$('.fa-arrow-right').show();

						if ($('#form_equipe_acheteur').val() != 0){
							$('.btn-submit').attr('disabled', false);
						}
					}
					else alert('Une erreur est survenue');
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});

		// Clic club acheteur
		$('#form_equipe_acheteur').on('change', function(){
			id_equipe = $(this).val();
			$('.logo_acheteur').remove();
			$.ajax({
				url: window.location.origin + '/equipe/api/getEquipe.json',
				data: 'id_equipe=' + id_equipe,
				dataType: 'json',
				type: 'get',
				success : function(data){
					$('.club_acheteur').append(
						'<img src="'+window.location.origin+'/upload/equipes/'+data[7]+'/'+data[2]+'" alt="'+data[0]+'" width="100px" class="logo_acheteur" />'
					);

					if ($('#form_joueurs').val() != 0 ){
						$('.btn-submit').attr('disabled', false);
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});


		// Validation
		$('.btn-submit').on('click', function(){
			$('#search_progress').show();
			id_joueur = $('#form_joueurs').val();
			id_equipe = $('#form_equipe_acheteur').val();
			$.ajax({
				url: window.location.origin+'/transfert/api/transferer.json',
				data: 'joueur='+id_joueur+'&equipe='+id_equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					$('#search_progress').hide();
					if (data){
						$('.btn-submit').attr('disabled', 'disabled');
						alert('Transfert effectué.');
						location.reload();
					}
					else alert('Une erreur est survenue pendant le traitement');
				},
				error: function(){
					$('#search_progress').hide();
					alert('Une erreur est survenue');
				},
			});
		});
	});
</script>