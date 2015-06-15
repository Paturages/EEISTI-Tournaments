<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<meta charset="utf-8">
	<title><?php echo $_ENV['ORG_NAME']; ?> : Inscriptions</title>
</head>
<style>
body { font-family: Helvetica, sans-serif; width: 80%; margin: auto; padding: 20px;}
br { margin: 10px;}
footer { margin-top: 50px;}
.error { color: red;}
</style>
<body>
<a href="../<?php echo $entry->id_game; ?>">&lt;&lt; Retour</a>

<h1><?php echo $is_delete ? 'Suppress' : 'Confirmat'; ?>ion : <?php echo $entry->name ?></h1>
<?php
	if (!empty(session('errors'))) {
		echo '<p class="error">';
		foreach (session('errors') as $e)
			echo $e.'<br/>';
		echo '</p>';
	}
?>
<form method="POST" action="<?php echo $entry->rowid; ?>">
<input type="hidden" name="id_game" value="<?php echo $entry->id_game ?>" />
<p>Entrer le code fourni par e-mail pour confirmer la <?php echo $is_delete ? 'suppress' : 'confirmat'; ?>ion - Oublié ? <a href="../forgot/<?php echo $entry->rowid; ?>">Renvoi du code par email</a></p>
<label for="password">Code :</label> <input id="password" name="password" type="password" />
<br/><br/>
<button type="submit">Valider</button>
</form>
<footer>&copy; E-EISTI 2015 - La simplicité pour le confort.</footer>
</body>
</html>