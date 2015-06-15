<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<meta charset="utf-8">
	<title><?php echo $_ENV['ORG_NAME']; ?>: Inscriptions</title>
</head>
<style>body { font-family: Helvetica, sans-serif; width: 80%; margin: auto;}
* { padding: 15px; }</style>
<body>
<a href=".">&lt;&lt; Retour</a>
<h1><?php echo $game->name; ?></h1>
<?php
	if (!empty(session('message')))
		echo '<p>'.session('message').'</p>';
?>
<a href="create/<?php echo $game->rowid; ?>">S'inscrire ?</a>
<br/><br/>
<h3>Aucune entrée</h3>
</body>
</html>