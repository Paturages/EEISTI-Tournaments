<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<meta charset="utf-8">
	<title><?php echo $_ENV['ORG_NAME']; ?> : Inscriptions</title>
</head>
<style>
body { font-family: Helvetica, sans-serif; width: 80%; margin: auto; padding: 20px;}
table { border: 1px solid #2c2c2c;}
td { padding: 10px; border: 1px solid #2c2c2c;}
th { padding: 10px; border: 2px solid #2c2c2c;}
footer { margin-top: 50px;}
</style>
<body>
<a href=".">&lt;&lt; Retour</a>
<h1><?php echo $game->name; ?></h1>
<?php
	if (!empty(session('message')))
		echo '<p>'.session('message').'</p>';
?>
<a href="create/<?php echo $game->rowid; ?>">S'inscrire ?</a>
<br/><br/>
<table>
<thead>
<tr>
<th>Nom</th>
<th><?php echo $game->nickname_field; ?></th>
<?php if ($game->multicampus > 0) {
	echo '<th>Campus</th>';
} ?>
<th>Date d'inscription</th>
<th></th>
<th></th>
</tr>
</thead>
<tbody>
<?php
foreach ($entries as $entry) {
	echo '<tr>';
	echo '<td>'.$entry->real_name.'</td>';
	echo '<td>'.$entry->name.'</td>';
	if ($game->multicampus > 0) {
		echo '<td>'.$entry->campus.'</td>';
	}
	echo '<td>'.date('Y-m-d H:i', $entry->time).'</td>';
	echo '<td><a href="edit/' . $entry->rowid . '"">Modifier</a></td>';
	echo '<td><a href="delete/' . $entry->rowid . '"">Supprimer</a></td>';
	echo '</tr>';
}
?>
</tbody>
</table>
<footer>&copy; E-EISTI 2015 - La simplicité pour le confort.</footer>
</body>
</html>