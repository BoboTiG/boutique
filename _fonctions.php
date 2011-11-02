<?php


/**********************************************************************/
// Fonction : is_mobile
// Objectif : détection de l'usage d'une mobile
// Entrée   : auncune
// Sortie   : (bool) oui ou non
// MàJ      : 20120210
/**********************************************************************/
function is_mobile() {
	(bool)$mobile = FALSE;
	(string)$accept = $_SERVER['HTTP_ACCEPT'];
	
	if ( isset($_SERVER['HTTP_X_WAP_PROFILE']) || 
		 isset($_SERVER['HTTP_PROFILE']) ) {
		$mobile = TRUE;
	} elseif ( strpos($accept, 'text/vnd.wap.wml') > 0 || 
			   strpos($accept, 'application/vnd.wap.xhtml+xml') > 0) {
		$mobile = TRUE;
	}
	return $mobile;
} //fin is_mobile ------------------------------------------------------
?>
