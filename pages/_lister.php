<h1 class="hide_mobile">Boutique du RC Metz</h1>
<table>
<?php
	(array)$articles = liste_articles(NULL);
	(int)$j = 0;
	(int)$par_ligne = is_mobile() === TRUE ? 1 : 4;
	
	foreach ( $articles as $article ) {
		if ( $j == 0 )
			echo '<tr>';
		elseif ( $j == $par_ligne ) {
			echo '</tr><tr>';
			$j = 0;
		}
		printf(
			'<td>
				<div class="view view-theone">
					<img src="images/miniatures/%s.jpg" alt="%s.jpg" width="160" height="194" />
					<div class="mask">
						<h2>%s</h2>
						<p>', $article['id'], $article['id'], $article['nom']);
							if ( $article['taille_unique'] == 1 )
								printf('Prix unique : <strong>%d &euro;</strong>', 
									$article['prix_enfant'] > 0 ? 
										$article['prix_enfant'] : 
										$article['prix_adulte']);
							else {
								if ( $article['prix_enfant'] > 0 )
									printf('Prix enfant : <strong>%d &euro;</strong>', $article['prix_enfant']);
								if ( $article['prix_adulte'] > 0 ) {
									if ( $article['prix_enfant'] > 0 )
										echo '<br />';
									printf('Prix adulte : <strong>%d &euro;</strong>', $article['prix_adulte']);
								}
							}
						printf('</p>
						<a href="index.php?action=afficher%s&article=%s" class="info">D&eacute;tails</a>
					</div>
				</div>
			</td>', is_mobile() === TRUE ? '-mobile' : '', $article['id']);
		++$j;
	}
	echo '</tr>';
?>
</table>
