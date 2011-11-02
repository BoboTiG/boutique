<?php
	supprimer_article(isset($_GET['id']) ? $_GET['id'] : NULL);
	header('Location: index.php?action=recapituler');
?>
