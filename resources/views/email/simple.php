<style>body{font-family: Helvetica, sans-serif; width: 80%; margin: auto;}</style>
<p>Le mot de passe de confirmation, aussi utilisable pour modifier l'entrée (et ajouter/supprimer des membres d'une équipe) est le suivant : <?php echo $password; ?></p>
<p>Le lien de confirmation est : <a href="<?php echo $_ENV['BASE_URL'].'/light/verify/'.$id.'/'.$crpt_pass; ?>">--- ici ---</a>. Si ça ne marche pas, essayez <a href="<?php echo $_ENV['BASE_URL'].'/light/verify/'.$id; ?>">--- ici ---</a>.</p>
<br/>
<p>Cordialement,</p>
<p><a href="http://e-eisti.science">E-EISTI</a></p>