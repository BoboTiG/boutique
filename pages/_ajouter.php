<?php
	(string)$id       = isset($_POST['id']) && existe_article($_POST['id']) ? $_POST['id'] : NULL;
	(string)$taille   = isset($_POST['taille']) && in_array($_POST['taille'], $tailles) ? $_POST['taille'] : NULL;
	(string)$quantite = isset($_POST['quantite']) && $_POST['quantite'] > 0 && $_POST['quantite'] < 100 ? $_POST['quantite'] : 1;
	(string)$broderie = isset($_POST['broderie']) && in_array($_POST['broderie'], $reponses) ? $rep_bool[$_POST['broderie']] : 0;
	(string)$flocage  = isset($_POST['flocage']) && in_array($_POST['flocage'], $reponses) ? $rep_bool[$_POST['flocage']] : 0;
	
	if ( $id === NULL || $taille === NULL ) {
		unset($_POST);
		die('<p>Une erreur est survenue, veuillez <a href="index.php">réessayer</a>.</p>');
	}
	
	(array)$infos  = recup_infos($id, 'prix_adulte, prix_enfant, taille_unique');
	(array)$select = array(
		'id'       => $id.':'.$taille.':'.$broderie.':'.$flocage,
		'quantite' => $quantite,
		'prix'     => 
			in_array($taille, $tailles_enfant) 
			|| ($infos['taille_unique'] == 1 && $infos['prix_enfant'] > 0) 
			? $infos['prix_enfant'] : $infos['prix_adulte']
	);
	
	ajout_panier($select);
	header('Location: index.php');
?>
