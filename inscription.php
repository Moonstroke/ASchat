<?php
require_once 'mysql.php';
$mysql = connexion();

$taille_max = '50 Ko';
$tmax = 51200;
$minlength = 4;
$maxlength = 12;

$envoye = isset($_POST['pseudo']);

switch($envoye) {
case true:
	$errmsg = '';
	$pseudo = htmlspecialchars($_POST['pseudo'] ?? '', ENT_QUOTES);
	if(!$pseudo) {
		$errmsg = 'Pseudo vide';
		break;
	}
	if($HEADER = $mysql->query("select count(*) as pseudos from POSTEUR where pseudo = '$pseudo';")->fetch() <= 0) {
		$errmsg = 'Pseudo déja utilisé';
		break;
	}
	if(strlen($pseudo) < $minlength) {
		$errmsg = 'Pseudo trop court';
		break;
	}
	if(strlen($pseudo) > $maxlength) {
		$errmsg = 'Pseudo trop long';
		break;
	}
	$mdp = htmlspecialchars($_POST['mdp'] ?? '', ENT_QUOTES);
	$mdp2 = htmlspecialchars($_POST['mdp2'] ?? '', ENT_QUOTES);
	if($mdp !== $mdp2) {
		$errmsg = 'Les mots de passe ne correspondent pas';
		break;
	}
	if(strlen($mdp) > $maxlength) {
		$errmsg = 'Mot de passe trop long';
		break;
	}
	$avatar = $_FILES['avatar'];
	if($avatar['error'] > 0) {
		$errmsg = 'Échec de transfert du fichier;';
		break;
	}
	if($avatar['size'] > $tmax) {
		$errmsg = sprintf('Taille du fichier trop grande : %d', $avatar['size']);
		break;
	}
	$ext = strtolower(substr(strrchr($avatar['name'], '.'), 1));
	if(!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
		$errmsg = 'Type de fichier incorrect';
		break;
	}
	$nom_av = "$pseudo.$ext";
	$chemin_av = "img/avatars/$nom_av";
	if(file_exists($chemin_av))
		@unlink($chemin_av);
	
	$res = move_uploaded_file($avatar['tmp_name'], $chemin_av);
	if(!$res) {
		$errmsg = 'Échec du déplacement du fichier';
		break;
	}
	if($mysql->exec("insert into POSTEUR (pseudo, mdp, avatar) values ('$pseudo', '$mdp', '$nom_av');") != 1) {
		$errmsg = 'Échec de l&apos;insertion';
		break;
	}
	session_start();
	$_SESSION['pseudo'] = $pseudo;
	$_SESSION['mdp'] = $mdp;
}

$TITLE = 'Inscription (izi)';
$H1 = 'Inscription';

ob_start();
if(!$envoye) { ?>
    <form method="POST" enctype="multipart/form-data">
      <fieldset>
        <legend>Inscription</legend>
        <label for="pseudo">Pseudo</label><input type="text" id="pseudo" name="pseudo" minlength=<?= $minlength ?> maxlength="<?= $maxlength ?>" placeholder="Entre ton pseudo" required/>
        <label for="mdp">Mot de passe</label><input type="password" id="mdp" name="mdp" minlength="<?= $minlength ?>" maxlength="<?= $maxlength ?>" placeholder="Mot de passe" required/>
        <label for="mdp2">Confirmation</label><input type="password" id="mdp2" name="mdp2" minlength="<?= $minlength ?>"  maxlength="<?= $maxlength ?>" placeholder="Confirme ton mdp" required/>
        <label for="avatar">Avatar</label><input type="file" id="avatar" name="avatar" placeholder="Choisis une image" accept="image/*" class="tip" data-tip="<?= $taille_max ?> max."/>
        <input type="reset"/><input type="submit" value="Valider"/>
      </fieldset>
    </form>
<?php
}
elseif($errmsg)
	echo "    <p>Échec de l&apos;inscription&nbsp;: <code>$errmsg</code></p>\n";
else
	echo '    <p>Inscription réussie&nbsp;! Clique <a href=".">ici</a> pour retourner à la page d&apos;accueil.</p>';

$MAIN = ob_get_clean();

include 'modèle.php';
