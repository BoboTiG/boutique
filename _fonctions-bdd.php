<?php

try {
	(object)$bdd = new PDO('sqlite:db/boutique.db');
} catch ( PDOException $e ) {
	echo $e->getMessage();
}


/*
 * Valeurs du champs 'etat' :
 * 		0 : article désactivé
 * 		1 : article en relation avec l'équipementier DUARIG
 * 		2 : article en relation avec le cinquantenaire
*/


/**********************************************************************/
// Fonction : ajouter_facture
// Objectif : ajouter une facture dans la BDD
// Entrée   : auncune
// Sortie   : void
// MàJ      : 20111116
/**********************************************************************/
function ajouter_facture() {
	global $bdd;
	if ( $_SESSION['panier']['verrou'] === TRUE ) {
		(string)$id = $_SERVER['REMOTE_ADDR'].':'.date('U');
		(string)$texte_sql = 
			sprintf("INSERT INTO 'factures' (id, objet) VALUES (%s, %s)",
				$bdd->quote(serialize_perso($id), PDO::PARAM_STR),
				$bdd->quote(serialize_perso($_SESSION), PDO::PARAM_STR)
			);
		$bdd->query($texte_sql);
	}
} //fin ajouter_facture ------------------------------------------------


/**********************************************************************/
// Fonction : ajouter_visiteur
// Objectif : ajouter/mettre à jour un visiteur dans la BDD
// Entrée   : auncune
// Sortie   : void
// MàJ      : 20111129
/**********************************************************************/
function ajouter_visiteur() {
	// Éviter de compter mes propres visites...
	if ( preg_match('/(^(192.168.0|127.0.0)|localhost)/', 
					$_SERVER['REMOTE_ADDR'])
	) { return; }
	
	global $bdd;
	(string)$ip = hash('sha1', $_SERVER['REMOTE_ADDR'].'inc');
			$ip = $bdd->quote($ip, PDO::PARAM_STR);
	(int)$rep = 0;
	(string)$texte_sql = 
		"SELECT COUNT(ip) AS n FROM 'visiteurs'
		WHERE ip = $ip";
	
	foreach ( $bdd->query($texte_sql) as $un ) { $rep += $un['n']; }
	if ( $rep == 0 ) {
		// Ajouter le nouveau visiteur
		$bdd->query("INSERT INTO 'visiteurs' (ip, n) VALUES ($ip, '1')");
	} else {
		// Mettre à jour le nombre de connexions du visiteur
		(int)$n = 0;
		$texte_sql = "SELECT * from 'visiteurs' WHERE ip = $ip";
		foreach ( $bdd->query($texte_sql) as $un ) { $n += $un['n']; }
		++$n;
		$texte_sql = "UPDATE 'visiteurs' SET n = '$n' WHERE ip = $ip";
		$bdd->query($texte_sql);
	}
} //fin ajouter_visiteur -----------------------------------------------


/**********************************************************************/
// Fonction : existe_article
// Objectif : tester la présence d'un article dans la BDD
// Entrée   : (string) ID
// Sortie   : (bool) existant
// MàJ      : 20111129
/**********************************************************************/
function existe_article($id = NULL) {
	global $bdd;
	(int)$rep = 0;
	$id = $bdd->quote($id, PDO::PARAM_STR);
	(string)$texte_sql = 
		"SELECT COUNT(id) AS n FROM 'articles'
		WHERE id = $id AND etat > 0";
	foreach ( $bdd->query($texte_sql) as $un ) { $rep += $un['n']; }
	if ( $rep == 0 ) { return FALSE; }
	return TRUE;
} //fin existe_article -------------------------------------------------


/**********************************************************************/
// Fonction : liste_articles
// Objectif : liste les informations des articles
// Entrée   : (string) id de l'article
// Sortie   : (array) les informations
// MàJ      : 20111129
/**********************************************************************/
function liste_articles($id = NULL) {
	global $bdd;
	(array)$articles = array();
	(string)$texte_sql = "SELECT * FROM 'articles' WHERE etat > 0";
	
	if ( $id !== NULL && existe_article($id) ) {
		$id = $bdd->quote($id, PDO::PARAM_STR);
		$texte_sql .= " WHERE id = $id";
	}
	foreach ( $bdd->query($texte_sql) as $donnees ) {
		array_push($articles, $donnees);
	}
	sort($articles);
	return $articles;
} //fin liste_articles -------------------------------------------------


/**********************************************************************/
// Fonction : recup_infos
// Objectif : récupérer des informations précises d'un article
// Entrée   : 
//		(string) id
//		(string) quoi
// Sortie   : (mixed) infos
// MàJ      : 20111129
/**********************************************************************/
function recup_infos($id = NULL, $quoi) {
	global $bdd;
	(array)$infos = array();
	
	if ( $id !== NULL && existe_article($id) ) {
		$id = $bdd->quote($id, PDO::PARAM_STR);
		(string)$texte_sql = 
			"SELECT $quoi FROM 'articles'
			WHERE id = $id AND etat > 0";
		foreach ( $bdd->query($texte_sql) as $donnees ) {
			$infos = $donnees;
		}
	}
	if ( $quoi != '*' && ! preg_match('/,/', $quoi) )
		return $infos[0];
	return $infos;
} //fin recup_infos ----------------------------------------------------


/**********************************************************************/
// Fonction : serialize_perso
// Objectif : linéariser un objet
// Entrée   : (mixed) objet à linéariser
// Sortie   : (string) valeur linéarisée et compressée
// MàJ      : 20111107
/**********************************************************************/
function serialize_perso($obj) {
	return base64_encode(gzcompress(serialize($obj)));
} //fin serialize_perso ------------------------------------------------


/**********************************************************************/
// Fonction : unserialize_perso
// Objectif : récupérer un objet à partir d'une valeur linéarisée
// Entrée   : (string) valeur linéarisée et compressée
// Sortie   : (mixed) objet de départ
// MàJ      : 20111107
/**********************************************************************/
function unserialize_perso($txt) {
	return unserialize(gzuncompress(base64_decode($txt)));
} //fin unserialize_perso ----------------------------------------------


/**********************************************************************/
// Fonction : visiteurs
// Objectif : afficher le nombre de visites
// Entrée   : aucune
// Sortie   : (string) nombre de visites
// MàJ      : 20111129
/**********************************************************************/
function visiteurs() {
	global $bdd;
	(int)$unique       = 0;
	(int)$total        = 0;
	(string)$texte_sql = "SELECT COUNT(ip) AS n FROM 'visiteurs'";
	
	ajouter_visiteur();
	foreach ( $bdd->query($texte_sql) as $un ) { $unique += $un['n']; }
	foreach ( $bdd->query("SELECT * FROM 'visiteurs'") as $donnees ) {
		$total += $donnees['n'];
	}
	return isset($_GET['visites']) ? ' | '.$unique.':'.$total : '';
} //fin visiteurs ------------------------------------------------------

?>
