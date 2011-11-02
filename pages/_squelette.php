<style>
	table          { border-collapse: collapse; width: 100%; }
	.elbat th, 
	.elbat td      { border: 1px solid black; }
	table table td { margin-left: 30%; }
	table table th { margin-left: 30%; }
	th, td         { height: 25px; }
	.centre, h1    { text-align: center; }
	.droite        { text-align: right; padding-right: 10px; }
	.total         { background-color: #ddd; font-weight: bold; }
</style>

<page style="font-size:: 14px; font-family: DejaVuSans;">
	<!-- On est chez nous ! -->
	<table>
		<tr>
			<td><img style="width: 100%;" src="images/entete.png"></td>
		</tr>
	</table>
	
	<!-- La date -->
	<table style="text-align: left; font-size: 8pt;">
		<tr>
			<td>Le, <?php echo date('d/m/Y'); ?>.</td>
		</tr>
	</table>
	
	<!-- Informations sur le client et logos -->
	<table style="width: 100%; text-align: center;">
		<tr>
			<td style="width: 60%;">
				<table style="text-align: left;">
					<tr>
						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nom</th>
						<td><input type="text"/></td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pr&eacute;nom</th>
						<td><input type="text"/></td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cat&eacute;gorie&nbsp;&nbsp;&nbsp;</th>
						<td>
							<select>
								<option value="- de 7 ans" selected="selected">- de 7 ans</option>
								<option value="- de 9 ans">- de 9 ans</option>
								<option value="- de 11 ans">- de 11 ans</option>
								<option value="- de 13 ans">- de 13 ans</option>
								<option value="- de 15 ans">- de 15 ans</option>
								<option value="- de 17 ans">- de 17 ans</option>
								<option value="- de 19 ans">- de 19 ans</option>
								<option value="+ de 19 ans">+ de 19 ans</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 40%;">
				<img style="width: 100%;" src="images/duarig.png">
			</td>
		</tr>
	</table>
	
	<br />
	<br />
	<br />
	<br />
	<h1>Bon de commande</h1>
	<br />
	<br />
	
	<!-- Liste des articles -->
	<table class="elbat">
		<tr class="centre">
			<th style="width:50%;">Article</th>
			<th style="width:16%;">Taille</th>
			<th style="width:16%;">Quantité</th>
			<th style="width:18%;">Prix</th>
		</tr>
		
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
			
			foreach ( $articles as $article ) {
				// $articles[] = (id, taille, broderie, flocage, quantite, prix)
				//                0   1       2         3        4         5
				(float)$montant = ($article[5] + ($article[2] == 1 ? 3.5 : 0) + ($article[3] == 1 ? 3.5 : 0)) * $article[4];
				(string)$broderie = $article[2] == 1 ? 'avec' : 'sans';
				(string)$flocage  = $article[3] == 1 ? 'avec' : 'sans';
				printf(
					'<tr>
						<td style="padding-left: 10px;">%s - %s broderie - %s flocage</td>
						<td class="centre" style="border-left:1px dotted #ddd;">%s</td>
						<td class="centre" style="border-left:1px dotted #ddd;">%d</td>
						<td class="droite" style="border-left:1px dotted #ddd;">%s &euro;</td>
					</tr>',
					recup_infos($article[0], 'nom'), $broderie, $flocage,
					$article[1],
					$article[4],
					formater_n($montant)
				);
			}
		?>
		
		<tr>
			<td class="centre total" colspan="3">Total de la commande</td>
			<td class="droite" style="border-left:1px dotted #ddd;"><?php echo formater_n(panier()); ?> &euro;</td>
		</tr>
	</table>
	
	<br />
	<br />
	
	<!-- Mode de paiement -->
	<table style="text-align: left;">
		<tr>
			<th>Mode de r&egrave;glement&nbsp;&nbsp;</th>
			<td>
				<select>
					<option value="esp&egrave;ces" selected="selected">esp&egrave;ces</option>
					<option value="ch&egrave;que">ch&egrave;que</option>
				</select>
			</td>
		</tr>
	</table>
</page>
