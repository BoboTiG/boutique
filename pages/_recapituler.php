<?php
	(int)$nb = count($_SESSION['panier']['id']);
	(array)$articles = array();
	for ( (int)$i = 0; $i < $nb; ++$i ) {
		(array)$infos = explode(':', $_SESSION['panier']['id'][$i]);
		$infos[] = $_SESSION['panier']['quantite'][$i];
		$infos[] = $_SESSION['panier']['prix'][$i];
		$articles[] = $infos;
	}
	sort($articles);
	
	if ( $nb == 0 )
		echo '<h1>Le panier est vide.</h1>';
	else {
?>

<h1>Contenu du panier</h1>
<div class="recap box_shadow">
	<table>
		<tr class="centre">
			<th style="width:25%;">Article</th>
			<th style="width:12%;">Taille</th>
			<th style="width:12%;">Broderie</th>
			<th style="width:12%;">Flocage</th>
			<th style="width:12%;">Quantité</th>
			<th style="width:12%;">Prix</th>
			<th style="width:15%;">Supprimer</th>
		</tr>
		
		<?php
			foreach ( $articles as $article ) {
				// $articles[] = (id, taille, broderie, flocage, quantite, prix)
				//                0   1       2         3        4         5
				(float)$montant = ($article[5] + ($article[2] == 1 ? 3.5 : 0) + ($article[3] == 1 ? 3.5 : 0)) * $article[4];
				(string)$broderie = $article[2] == 1 ? 'avec' : 'sans';
				(string)$flocage  = $article[3] == 1 ? 'avec' : 'sans';
				
				printf(
				'<tr>
					<td style="padding-left: 10px;"><a href="index.php?action=afficher&article=%s">%s</a></td>
					<td class="centre" style="border-left:1px dotted #ddd;">%s</td>
					<td class="centre" style="border-left:1px dotted #ddd;"><img src="images/%s.png" alt="%s broderie" title="%s broderie" /></td>
					<td class="centre" style="border-left:1px dotted #ddd;"><img src="images/%s.png" alt="%s flocage" title="%s flocage" /></td>
					<td class="centre" style="border-left:1px dotted #ddd;">%d</td>
					<td class="droite" style="border-left:1px dotted #ddd;">%s</td>
					<td class="centre" style="border-left:1px dotted #ddd;"><a href="index.php?action=supprimer&id=%s"><img src="images/supprimer.png" title="Supprimer cet article du panier" onClick="if ( ! confirm(\'Suis-je sûr et certain de vouloir retirer cet article  ?\') ) { return false; }" /></a></td>
				</tr>', 
				$article[0], recup_infos($article[0], 'nom'), 
				$article[1], 
				$broderie, $broderie, ucfirst($broderie),
				$flocage, $flocage, ucfirst($flocage),
				$article[4], 
				formater_n($montant), 
				$article[0].':'.$article[1].':'.$article[2].':'.$article[3]
				);
			}
		?>
		
	</table>
</div>
<p>
	<br />
	<br />
	<img src="images/info.png" class="img_flotte_g" /> En cliquant sur <strong>Je souhaite valider cette commande</strong>, un fichier au format PDF sera généré, il vous suffira de l'imprimer et de le donner, obligatoirement accompagn&eacute; du r&egrave;glement, au Club House ou &agrave; un responsable de l'Amicale.
	<br />
	<br />
	<u>Mode de r&egrave;glement</u> : esp&egrave;ces ou ch&egrave;que libell&eacute; &agrave; l'ordre du <strong>RC Metz</strong>.
	<br />
	<br />
</p>
<br />
<p class="centre">
	<img src="images/ajax-loader.gif" title="Veuillez patienter..." id="chargement" /><a href="bdc.php" class="terminer" onClick="this.style.visibility = 'hidden'; document.getElementById('chargement').style.visibility = 'visible';">Je souhaite valider cette commande</a>
	&nbsp;
	<a href="index.php" class="terminer">Je souhaite compl&eacute;ter cette commande</a>
</p>

<?php } ?>
