function resetAll (nb_element_buteurs, nb_element_tireurs, select_buteurs, select_tireurs){
	for (var i = 1; i <= nb_element_buteurs; i++){
		select = $(select_buteurs+i);
		select.html('');
		select.select2('val', '');
		select.append('<option></option>');
		select.select2({
			placeholder: "Selectionnez un joueur",
			width: '230px'
		});
	}

	for (var i = 1; i <= nb_element_tireurs; i++){
		select = $(select_tireurs+i);
		select.html('');
		select.select2('val', '');
		select.append('<option></option>');
		select.select2({
			placeholder: "Tireur #"+i,
			width: '140px'
		});
	}

	$('input:submit').attr('disabled', true);
}

/**
 * resetButeurs
 * Change le noms des buteurs dans les listes déroulantes
 *
 * @param int nb_element
 * @param int idEquipe
 * @param String select
 */
function resetButeurs (nb_element, idEquipe, select){
	for (var i = 1; i <= nb_element; i++){
		afficherJoueurs(idEquipe, $(select+i), 'but');
	}
}

/**
 * resetTireurs
 * Change les nomrs des tireurs dans les listes déroulantes
 *
 * @param int nb_element
 * @param int idEquipe
 * @param String select
 */
function resetTireurs (nb_element, idEquipe, select){
	for (var i = 1; i <= nb_element; i++){
		afficherJoueurs(idEquipe, $(select+i), 'tir_reset');
	}
}