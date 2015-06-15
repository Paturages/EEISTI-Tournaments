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
<a href="../<?php echo $game->rowid; ?>">&lt;&lt; Retour</a>

<h1><?php echo $game->name; ?></h1>
<?php
	if (!empty(session('errors'))) {
		$entry = session('entry');
		echo '<p class="error">';
		foreach (session('errors') as $e)
			echo $e.'<br/>';
		echo '</p>';
	}
?>

<form method="POST" action="<?php echo ($mode == 'create') ? $game->rowid : $entry['id']; ?>">
<label for="name">Nom de l'équipe :</label>
<input id="name" name="name" type="text" <?php if (!empty($entry)) { echo 'value="'.$entry['name'].'"'; } ?> /><br/>
<?php
if ($game->multicampus > 0) {
	if (!empty($entry)) {
		if ($entry['campus'] == 'Cergy') {
			$check = ['checked="checked"',''];
		} else {
			$check = ['','checked="checked"'];
		}
	} else {
		$check = ['',''];
	}
	echo '<p>Campus :</p><input id="cergy" name="campus" type="radio" value="Cergy" '.$check[0].' /><label for="cergy">Cergy</label><input id="pau" name="campus" type="radio" value="Pau" '.$check[1].' /><label for="pau">Pau</label><br/>';
} else {
	echo '<input type="hidden" name="campus" value="Cergy" />';
}

if ($mode == 'edit') {
	echo '<p>Entrer le code fourni par e-mail pour confirmer la modification - Oublié ? <a href="../forgot/'.$entry['id'].'">Renvoi du code par email</a></p><label for="password">Code :</label> <input id="password" name="password" type="password" />';
} else {
	echo '<p>Un code sera fourni par e-mail pour confirmation de l\'inscription.</p><label for="email">Email :</label> <input id="email" name="email" type="text" '.(empty($entry) ? '' : 'value="'.$entry['email'].'"').'/>';
}
?>
<br/>
<h3>Membres de l'équipe</h3>
<p>Il n'est pas obligatoire de remplir tous les joueurs (mais au moins un)</p>

<?php
for ($i = 1 ; $i <= $game->num_players ; $i++) {
	echo '<h5>Joueur '.$i.'</h5>';
	echo '<label for="real_name'.$i.'">Nom :</label> ';
	echo '<input id="real_name'.$i.'" name="real_name[]" type="text" value="'.(empty($entry['real_name'][$i-1]) ? '' : $entry['real_name'][$i-1]).'" /><br/>';
	echo '<label for="p_name'.$i.'">'.$game->nickname_field.' :</label> ';
	echo '<input id="p_name'.$i.'" name="p_name[]" type="text" value="'.(empty($entry['p_name'][$i-1]) ? '' : $entry['p_name'][$i-1]).'" /><br/>';
	if ($game->multicampus > 0) {
		if (!empty($entry['p_campus'][$i-1])) {
			if ($entry['p_campus'][$i-1] == 'Cergy') {
				$check = ['checked="checked"',''];
			} else {
				$check = ['','checked="checked"'];
			}
		} else {
			$check = ['',''];
		}
		echo '<p>Campus :</p><input id="cergy'.$i.'" name="p_campus['.($i-1).']" type="radio" value="Cergy" '.$check[0].'" /><label for="cergy'.$i.'">Cergy</label><input id="pau'.$i.'" name="p_campus['.($i-1).']" type="radio" value="Pau" '.$check[1].'" /><label for="pau'.$i.'">Pau</label><br/>';
	} else {
		echo '<input type="hidden" name="p_campus['.($i-1).']" value="Cergy" />';
	}
}
?>
<br/><br/>
<button type="submit">Valider</button>
</form>
<footer>&copy; E-EISTI 2015 - La simplicité pour le confort.</footer>
</body>
</html>