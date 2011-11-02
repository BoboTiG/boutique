<?php
	echo "La boutique n'est plus en service depuis septembre 2012.";
	exit;
?>

<?php
	error_reporting(0);
	date_default_timezone_set('Europe/Paris');
	session_start();
	
	require '_fonctions.php';
	require '_fonctions-bdd.php';
	require '_fonctions-panier.php';
	require '_variables.php';
	
	// Initialisation du panier
	init_panier();
	
	(string)$action = 
		isset($_GET['action']) 
		&& in_array($_GET['action'], $actions) 
		&& file_exists('pages/_'.$_GET['action'].'.php') ? $_GET['action'] : NULL;
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>.: Boutique du RC Metz-Moselle :.</title>
		<meta charset="utf-8" />
		<link rel="icon" href="images/logo.png" type="image/png" />
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" media="all" type="text/css" href="scripts/all.css" />
		<?php
			if ( $action === NULL ) {
				echo '<link rel="stylesheet" type="text/css" href="scripts/style_common.css" />';
				printf('<link rel="stylesheet" type="text/css" href="scripts/style%d.css" />', rand(1, 8));
			}
		?>
	</head>
	
	<body>
		
		<div class="barre-haute hide_mobile"></div>
		
		<div class="panier">
			<a href="index.php" class="accueil" title="Accueil de la boutique"></a>
			Mon panier : <span class="montant"><?php echo formater_n(panier()); ?> €</span>
			<?php
				if ( panier() > 0 ) {
					echo '<a href="index.php?action=recapituler" class="valider" title="Valider ma commande">Consulter</a>';
					echo '<a href="index.php?action=vider" class="vider" title="Vider le panier" onClick="if ( ! confirm(\'Suis-je sûr et certain de vouloir vider mon panier ?\') ) { return false; }"></a>';
				} else {
					echo '<span class="rien" style="float: right; margin-right: 30px;"></span>';
				}
			?>
		</div>
		
		<?php
			if ( $action !== NULL )
				include 'pages/_'.$action.'.php';
			else
				include 'pages/_lister.php';
		?>
		
		<br />
		<div class="centre">
			<hr />
			Boutique du Rugby Club Metz-Moselle
			<?php if ( $action === NULL ) { echo visiteurs(); } ?>
			| contact : <span class="courriel">moc.liamg@222.gnipool</span>
			<br />
			<br />
		</div>
	</body>
</html>
