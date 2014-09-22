$(document).ready(function() {
	$('#fullpage').hide();
	$("body").append('<div id="wait"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Chargement de la page ...</p></div>');

	$(window).load(function(){
		$('#wait').hide();
		$('#fullpage').fadeIn();
	})


		$('#fullpage').fullpage({
			slidesColor: ['#1bbc9b', '#4BBFC3', '#DE463E', '#32CF9A', '#ccddff', '#46BD37'],
			anchors: ['accueil', 'profil', 'competences', 'projets', 'loisirs', 'contact'],
			menu: '#menu',
			easing: 'easeOutBounce'
		});


		$('.img_projets').on('click', function(){
			$('.modal-body').html('');
			photo = $(this).attr('data-image');
			$('.modal-content').css('width', '980px');
			$('.modal-content').css('margin-left', '-150px');
			$('.modal-body').append(
				'<img src="' + window.location.origin + '/assets/img/projets/'+photo+'_grand.png" width="950px" height="450px" />'
			);
			$('#myModal').show();
		});

		$('.close-modal').on('click', function(){
			console.log('hide');
			$('#myModal').css('display', 'none');
		});

		$(".rslides").responsiveSlides({
			random: true,
			speed : 500,
		});
		
		$('.btn-submit').on('click', function(){
			nom = $('#nom').val();
			mail = $('#email').val();
			sujet = $('#sujet').val();
			message = $('#message').val();
			$.ajax({
				url : window.location.origin + '/welcome/api/sendMail.json',
				data: 'mail='+mail+'&sujet='+sujet+'&message='+message+'&nom='+nom,
				type: 'get',
				dataType: 'json',
				success: function (data){
					return false;
				}, 
				error: function(){
					alert('Une erreur est survenue');
				}
			});
		});

		$('#email').on('blur', function(){
			// bon = validateEmail ($(this).val());
			// console.log(bon);
		});

		// function validateEmail(sEmail) {
		// 	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		// 	if (filter.test(sEmail)) {
		// 		return true;
		// 	} else {
		// 		return false;
		// 	}
		// }​

		$('.cpt').tooltip();
		
		$('.img_foot').css('width', '100px');
		$('.img_foot').css('height', '100px');


		$('.img_music').css('width', '400px');
		$('.img_music').css('height', '100px');

		$('.scrool').on('click', function(){
			$.fn.fullpage.moveTo(2);
		});

	});