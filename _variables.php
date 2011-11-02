<?php
	(array)$actions = array(
		'afficher',        // afficher un article
		'afficher-mobile', // afficher un article (pour mobile)
		'ajouter',         // ajouter un article au panier
		'recapituler',     // lister les articles du panier
		'supprimer',       // supprimer un article du panier
		'vider',           // vider le panier
	);
	(array)$tailles_adulte = array('S', 'M', 'L', 'XL', '2XL');
	(array)$tailles_enfant = array('6A', '8A', '10A', '12A', 'XS');
	(array)$tailles = array_merge($tailles_adulte, $tailles_enfant, array('unique'));
	(array)$reponses = array('oui', 'non');
	(array)$rep_bool = array('oui' => 1, 'non' => 0);
?>
