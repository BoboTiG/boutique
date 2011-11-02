<?php


/**********************************************************************/
// Fonction : ajout_panier
// Objectif : ajouter un article au panier
// Entrée   : (array) article
// Sortie   : void
// MàJ      : 20111102
/**********************************************************************/
function ajout_panier($select) {
	if ( $_SESSION['panier']['verrou'] === FALSE ) {
		if ( ! verif_panier($select['id']) ) {
			array_push($_SESSION['panier']['id'],       $select['id']);
			array_push($_SESSION['panier']['quantite'], $select['quantite']);
			array_push($_SESSION['panier']['prix'],     $select['prix']);
		} else {
			modif_quantite($select['id'], $select['quantite']);
		}
	}
} //fin ajout_panier ---------------------------------------------------


/**********************************************************************/
// Fonction : formater_n
// Objectif : formater un nombre
// Entrée   : (int/float) nombre
// Sortie   : (string) nombre formaté
// MàJ      : 20111105
/**********************************************************************/
function formater_n($str) {
	return number_format($str, 2, ',', ' ');
} //fin formater_n -----------------------------------------------------


/**********************************************************************/
// Fonction : init_panier
// Objectif : création du panier
// Entrée   : aucune
// Sortie   : void
// MàJ      : 20111102
/**********************************************************************/
function init_panier() {
	if ( ! isset($_SESSION['panier']) ) {
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id']       = array(); // id:taille:broderie:flocage => 'polo:S:1:0'
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['prix']     = array();
		$_SESSION['panier']['verrou']   = FALSE;
	}
} //fin init_panier ----------------------------------------------------


/**********************************************************************/
// Fonction : modif_quantite
// Objectif : modification de la quantité d'un article du panier
// Entrée   : 
//		(string) id
//		(int) quantité
// Sortie   : void
// MàJ      : 20111102
/**********************************************************************/
function modif_quantite($id, $quantite) {
	if ( $_SESSION['panier']['verrou'] === FALSE ) {
		
		(int)$nb_actuel = nombre_article($id);
		if ( $nb_actuel > -1 && $nb_actuel != $quantite ) {
			
			(int)$nb = count($_SESSION['panier']['id']);
			for ( (int)$i = 0; $i < $nb; ++$i ) {
				if ( $id == $_SESSION['panier']['id'][$i] ) {
					$_SESSION['panier']['quantite'][$i] = $quantite;
					break;
				}
			}
		}
	}
} //fin modif_quantite -------------------------------------------------


/**********************************************************************/
// Fonction : nombre_article
// Objectif : connaitre le nombre d'articles du panier
// Entrée   : (string) id
// Sortie   : (int) nombre
// MàJ      : 20111102
/**********************************************************************/
function nombre_article($id) {
	(int)$nombre = -1;
	(int)$nb = count($_SESSION['panier']['id']);
	
	for ( (int)$i = 0; $i < $nb; ++$i ) {
		if ( $_SESSION['panier']['id'][$i] == $id ) {
			$nombre = $_SESSION['panier']['quantite'][$i];
			break;
		}
	}
	return $nombre;
} //fin nombre_article -------------------------------------------------


/**********************************************************************/
// Fonction : panier
// Objectif : montant total du panier
// Entrée   : aucune
// Sortie   : (float) montant
// MàJ      : 20111105
/**********************************************************************/
function panier() {
	(float)$montant = 0.0;
	(int)$nb = count($_SESSION['panier']['id']);
	
	for ( (int)$i = 0; $i < $nb; ++$i ) {
		(array)$id = explode(':', $_SESSION['panier']['id'][$i]);
		(float)$prix = 0.0;
		if ( $id[2] == 1 )
			$prix += 3.5;
		if ( $id[3] == 1 )
			$prix += 3.5;
		$montant += $_SESSION['panier']['quantite'][$i] * ($prix + $_SESSION['panier']['prix'][$i]);
	}
	return $montant;
} //fin panier ---------------------------------------------------------


/**********************************************************************/
// Fonction : supprimer_article
// Objectif : supprimer un article du panier
// Entrée   : (string) id
// Sortie   : void
// MàJ      : 20111105
/**********************************************************************/
function supprimer_article($id = NULL) {
	if ( $_SESSION['panier']['verrou'] === FALSE ) {
		if ( nombre_article($id) != -1 ) {
			(array)$panier_tmp = array(
				'id'       => array(),
				'quantite' => array(),
				'prix'     => array(),
				'verrou'  => FALSE
			);
			(int)$nb = count($_SESSION['panier']['id']);
			
			for ( (int)$i = 0; $i < $nb; ++$i ) {
				if ( $_SESSION['panier']['id'][$i] != $id ) {
					array_push($panier_tmp['id'],       $_SESSION['panier']['id'][$i]);
					array_push($panier_tmp['quantite'], $_SESSION['panier']['quantite'][$i]);
					array_push($panier_tmp['prix'],     $_SESSION['panier']['prix'][$i]);
				}
			}
			$_SESSION['panier'] = $panier_tmp;
			unset($panier_tmp);
		}
	}
} //fin supprimer_article ----------------------------------------------


/**********************************************************************/
// Fonction : verif_panier
// Objectif : vérifier la présence d'un article dans le panier
// Entrée   : aucune
// Sortie   : void
// MàJ      : 20111102
/**********************************************************************/
function verif_panier($id) {
	(bool)$present = FALSE;
	
	if ( count($_SESSION['panier']['id']) > 0
		 && array_search($id, $_SESSION['panier']['id']) !== FALSE
	) {
		$present = TRUE;
	}
	return $present;
} //fin verif_panier ---------------------------------------------------


/**********************************************************************/
// Fonction : vider_panier
// Objectif : vider le panier
// Entrée   : aucune
// Sortie   : void
// MàJ      : 20111102
/**********************************************************************/
function vider_panier() {
	if ( $_SESSION['panier']['verrou'] === FALSE )
		unset($_SESSION['panier']);
} //fin vider_panier ---------------------------------------------------


?>
