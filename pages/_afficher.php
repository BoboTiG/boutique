<?php
	(string)$id_article = isset($_GET['article']) && !empty($_GET['article']) ? $_GET['article'] : NULL;
	(array)$article = recup_infos($id_article, '*');
	
	if ( ! empty($article) ) {
		(array)$infos    = explode('::', $article['infos']);
		(string)$tailles = '';
		
		/*
		 * Gestion des tailles
		 */
		if ( $article['taille_unique'] == 0 ) {
			$tailles .= 'Du <select name="taille">';
			if ( $article['prix_enfant'] > 0 ) {
				foreach ( $tailles_enfant as $taille ) {
					$tailles .= sprintf('<option value="%s">%s</option>', $taille, $taille);
				}
			}
			if ( $article['prix_adulte'] > 1 ) {
				foreach ( $tailles_adulte as $taille ) {
					$tailles .= sprintf('<option value="%s">%s</option>', $taille, $taille);
				}
			}
			$tailles .= '</select> fera l\'affaire.';
		} else
			$tailles .= '<input type="hidden" name="taille" value="unique"/>';
		
		/*
		 * Affichage des infos et du formulaire
		 */ 
		echo 
		'<h1>'.$article['nom'].'</h1>
		<table>
			<tr class="bloc normal box_shadow">
				<td style="padding:20px; vertical-align:top;">
					<u>Description</u> : 
					<ul class="description">';
						foreach ( $infos as $info ) {
							echo '<li>'.$info.'</li>';
						}
		echo
					'</ul>
					<br />
					<form action="index.php?action=ajouter" method="post">
						<input type="hidden" name="id" value="'.$article['id'].'"/>
						<u>Monologue interne</u> : 
						<br />
						<br />
						<div class="monologue">
							&laquo; Il me faudrait c\'te '.strtolower($article['nom']).' !
							<br />
							'.$tailles.'
							<ul style="list-style-type:circle;">
							<li>Je vais en prendre <input type="text" class="quantite" name="quantite" value="1" required pattern="[0-9]{1,2}" /> ;</li>';
							if ( $article['broderie'] == 1 ) {
								echo 
									'<li>
										<select name="broderie">
											<option value="oui">avec</option>
											<option value="non">sans</option>
										</select>
										 <a href="" class="broderie_hover">broderie <sup>?</sup><img src="images/broderie.png" class="broderie" /></a> ;
									</li>';
							} else
								echo '<input type="hidden" name="borderie" value="non"/>';
							if ( $article['flocage'] == 1 ) {
								echo 
									'<li>
										<select name="flocage">
											<option value="non">sans</option>
											<option value="oui">avec</option>
										</select> <a href="" class="flocage_hover">flocage <sup>?</sup><img src="images/flocage.png" class="flocage" /></a> ;
									</li>';
							} else
								echo '<input type="hidden" name="flocage" value="non"/>';
		echo				'</ul>
							Et si tout est bon... &raquo
						</div>
						<p class="centre" style="margin-top:20px;"><input type="submit" value="Je valide cet article" /></p>
					</form>';
		echo 
				'</td>
				<td><img src="images/normal/'.$article['id'].'.jpg" alt="'.$article['id'].'.jpg" width="374" height="585" style="float:right;" /></td>
			</tr>
		</table>';
	} else
		header('Location: index.php');
?>
