<div class="container">
	<h1 class="page-header">
		Profil de <?= \Auth::get_screen_name() ?>
		<div class="club_pref">
			<?php if ($equipe_fav): ?>
				<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($equipe_fav->championnat->nom)) . '/' . $equipe_fav->logo ?>" alt="<?= $equipe_fav->nom ?>" width="100px" class="logo_profil" /> 
			<?php endif; ?>
		</div>
	</h1>

	<?php if (\Messages::any()): ?>
	    <br/>
	    <?php foreach (array('success', 'info', 'warning', 'error') as $type): ?>

	        <?php foreach (\Messages::instance()->get($type) as $message): ?>
	            <div class="alert alert-<?= $message['type']; ?>"><?= $message['body']; ?></div>
	        <?php endforeach; ?>

	    <?php endforeach; ?>
	    <?php \Messages::reset(); ?>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-xs-6 col-md-4 center-block center">
			<div class="profil_photo">
				<div class="section_photo">
					<?php if ($photo_user): ?>
						<img src="<?= \Uri::base().\Config::get('users.photo.path') ?><?= $photo_user->photo ?>" alt="<?= \Auth::get('username') ?>" class="img-circle img-responsive center-block" width="300px" heigth="300px">
					<?php else: ?>
						<img src="<?= \Uri::base().\Config::get('users.photo.path') ?>notfound.png" alt="<?= \Auth::get('username') ?>" class="img-circle img-responsive center-block" width="300px" heigth="300px">
					<?php endif; ?>
				</div>
				<a class="btn btn-info btn-upload center-block">Changer ma photo de profil</a>
				<div class="btn-fileupload" style="display:none;">
					<div id="fileuploader">Upload</div>
				</div>
				<br>
				<a class="btn btn-info btn-equipefav center-block" data-toggle="modal" data-target="#myModal">Définir mon équipe favorite</a>
			</div>
		</div>
		
		<div class="col-xs-6 col-md-6">
			<div class="well">
				<strong>
				<?php if (!\Auth::member(6)): ?>
					Membre
				<?php else: ?>
					Administrateur
				<?php endif; ?>
				</strong><br>
				<hr>
				Dernière connexion : <?= date('d/m/Y à H:i', \Auth::get('last_login')) ?><br>
				Inscription : <?= date('d/m/Y à H:i', \Auth::get('created_at')) ?><br>
			</div>

			<h2 class="page-header"><i class="fa fa-pie-chart"></i> Mes stats</h2>
				<span class="label label-info"><?= $stats['victoires'] + $stats['nuls'] + $stats['defaites'] ?> matchs disputés</span>
				<span class="label label-success"><?= $stats['victoires'] ?> victoires</span>
				<span class="label label-default"><?= $stats['nuls'] ?> matchs nuls</span>
				<span class="label label-danger"><?= $stats['defaites'] ?> défaites</span>
				<hr>
				<h4>Mes derniers matchs :</h4>
				<?php if ($derniers_matchs): ?>
					<?php foreach ($derniers_matchs as $match): ?>
						<div class="row" style="margin-bottom:10px;">
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe1']->championnat->nom)) . '/' . $match['equipe1']->logo ?>" alt="<?= $match['equipe1']->nom ?>" width="50px" >
							</div>
							<div class="col-md-4">
								<a href="/matchs/view/<?= $match['match']['id_match'] ?>"><div class="score_defis score-<?= $match['status'] ?>"><?= $match['match']['score_joueur1'] ?>-<?= $match['match']['score_joueur2'] ?></div></a>
							</div>
							<div class="col-md-4">
								<img src="<?= \Uri::base() . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($match['equipe2']->championnat->nom)) . '/' . $match['equipe2']->logo ?>" alt="<?= $match['equipe2']->nom ?>" width="50px" >
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

			<h2 class="page-header"><i class="fa fa-gear"></i> Fonctionnalités</h2>
			<a href="/users/change/<?= \Auth::get('id') ?>" class="btn btn-warning">Changer mon mot de passe</a>
			<a href="/users/delete/<?= \Auth::get('id') ?>" class="btn btn-danger btn-quit">Me désinscrire du site</a>
		</div>

		<!-- LISTE AMIS -->
		<div class="col-md-2">
			<?php if ($liste_amis): ?>
				<div class="panel panel-default">
					<div class="panel-heading"><h4><?= count($liste_amis) ?> amis</h4></div>
					<div class="panel-body liste-amis">
						<?php foreach ($liste_amis as $friend): ?>
							<?php if (!empty($friend['photouser'])): ?>
								<a href="/profil/view/<?= $friend['users']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . $friend['photouser']->photo ?>" alt="<?= $friend['users']->username ?>" width="50" height="50" class="photo-amis" data-toggle="tooltip" data-placement="top" title="<?= $friend['users']->username ?>"></a>
							<?php else: ?>
								<a href="/profil/view/<?= $friend['users']->id ?>"><img src="<?= \Uri::base() . \Config::get('users.photo.path') . 'notfound.png' ?>" alt="<?= $friend['users']->username ?>" width="50" height="50" class="photo-amis" data-toggle="tooltip" data-placement="top" title="<?= $friend['users']->username ?>"></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h4 class="modal-title">Définir son équipe favorite</h4>
      		</div>
      		<div class="modal-body">
      			<div class="row">
      				<div class="col-md-8">
		      			<!-- Div championnat -->
						<div class="form-group animated fadeInUp">
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
						<br><br>
						<!-- Div équipes -->
						<div class="form-group" style="display:none;" id="div_equipes">
							<div class="col-sm-10">
								<select id="form_equipe" name="id_equipe">
									<option></option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4 equipe_fav">

					</div>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        		<a class="btn btn-primary btn-save">Sauvegarder</a>
      		</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script type="text/javascript">
	$(document).ready(function(){
		$('.photo-amis').tooltip();

		$('.btn-quit').on('click', function(){
			if (!confirm("Etes vous sur de vouloir vous désinscrire du site ?")){
				return false;
			}
		});

		/* Uploader une image de profil */
		$('.btn-upload').on('click', function(){
			$('.btn-fileupload').show();
		});

		$("#fileuploader").uploadFile({
			url:window.location.origin+'/users/uploadPhoto',
			fileName:"myfile",
			onSuccess: function(files, data, xhr){
				$('.img-circle').attr('src', '<?= \Uri::base() . \Config::get("users.photo.path") ?>'+data);
			}
		});

		/* Choisir équipe favorite */
		$('#form_championnat').select2({
			placeholder: "Selectionnez un championnat",
			width: '300px'
		});

		$('#form_equipe').select2({
			placeholder: "Selectionnez une équipe",
			width: '300px'
		});

		$('#form_championnat').on('change', function(){
			id_championnat = $(this).val();
			afficherEquipes(id_championnat, $('#form_equipe'), $('#div_equipes'));
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
						afficher.addClass('animated fadeInUp').show();
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		}

		$('#form_equipe').on('change', function(){
			clickEquipe ($(this).val(), $('.equipe_fav'), 'logo_club');
		});

		/**
		 * ClickEquipe
		 * Affiche le logo de l'équipe sélectionnée
		 *
		 * @param id_equipe : l'id de l'équipe sélectionnée
		 * @param div_equipe : la div où l'image doit apparaître
		 * @param classImg : la classe donnée à l'image
		 */
		function clickEquipe (id_equipe, div_equipe, classImg){
			$('.'+classImg).remove();
			$.ajax({
				url: window.location.origin + '/equipe/api/getEquipe.json',
				data: 'id_equipe=' + id_equipe,
				dataType: 'json',
				type: 'get',
				success : function(data){
					div_equipe.append(
						'<img src="'+window.location.origin+'/upload/equipes/'+data[7]+'/'+data[2]+'" alt="'+data[0]+'" width="100px" class="'+classImg+'" />'
					);

					if ($('#form_equipe_defieur').val() != 0 && $('#form_equipe_defier').val() != 0){
						$('.score').show();
						$('input:submit').attr('disabled', false);
					}
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		}

		$('.btn-save').on('click', function(){
			equipe = $('#form_equipe').val();
			$.ajax({
				url: window.location.origin + '/profil/api/equipefav.json',
				data: 'equipe='+equipe,
				type: 'get',
				dataType: 'json',
				success: function(data){
					console.log(data);
					$('#myModal').modal('hide');
					if ($('.logo_profil').length != 0){
						$('.logo_profil').remove();
					}
					if (data != 'KO'){
						$('.club_pref').append(
							'<img src="'+window.location.origin+'/upload/equipes/'+data['championnat']+'/'+data['logo']+'" alt="'+data['nom']+'" width="100px" class="logo_profil" />'
						);
					}
					
				},
				error: function(){
					alert('Une erreur est survenue');
				},
			});
		});
	});
</script>