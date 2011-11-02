<?php
	error_reporting(0);
	session_start();
	
	require '_fonctions-bdd.php';
	require '_fonctions-panier.php';
	
	(int)$nb = count($_SESSION['panier']['id']);
	if ( $nb == 0 ) {
		header('Location: index.php');
	} else {
		$_SESSION['panier']['verrou'] = TRUE;
		ajouter_facture();
		
		// el PDF
		ob_start();
		include 'pages/_squelette.php';
		$content = ob_get_clean();
		require 'html2pdf/html2pdf.class.php';
		try {
			(string)$nom = 'bon-de-commande-'.uniqid().'.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
			$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			
			$fh = fopen('tmp/'.$nom, 'xb');
			if ( $fh )
				if ( fwrite($fh, $html2pdf->Output($nom, 'S')) )
					fclose($fh);
				else
					$html2pdf->Output($nom, 'D');
			else
				$html2pdf->Output($nom, 'D');
			
			$_SESSION['panier']['verrou'] = FALSE;
			vider_panier();
		} catch ( HTML2PDF_exception $e ) {
			echo $e;
		}
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>.: Boutique du RC Metz-Moselle :.</title>
		<meta charset="utf-8" />
		<link rel="icon" href="../logo.png" type="image/png" />
		<link rel="stylesheet" media="all" type="text/css" href="scripts/all.css" />
	</head>
	<body>
		<div class="barre-haute"></div>
		<div class="panier">
			<a href="index.php" class="accueil" title="Accueil de la boutique"></a>
			Mon panier : <span class="montant">0,00 €</span>
			<span class="rien" style="float: right; margin-right: 30px;"></span>
		</div>
		
		<h1>Merci pour votre achat !</h1>
		<div class="centre">
			<a href="tmp/<?php echo $nom; ?>" target="_blank" onClick="document.getElementById('accueil').style.display = 'inline';" title="T&eacute;l&eacute;charger le PDF"><img src="images/telecharger.png" /></a>
			<br />
			<br />
			<a href="index.php" class="terminer" id="accueil" style="display: none;">Retourner &agrave; l'accueil</a>
		</div>
		<br />
		<br />
		<div class="centre"><hr />Boutique du Rugby Club Metz-Moselle<br /><br /></div>
	</body>
</html>
