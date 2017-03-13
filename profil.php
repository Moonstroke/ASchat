<?php
require_once 'mysql.php';

$mysql = connexion();
$pseudo = htmlspecialchars($_GET['pseudo'] ?? '', ENT_QUOTES);
$req = $mysql->query("select avatar, posts from POSTEUR where pseudo = '$pseudo';");
if($req) {
	$pseudo_existe = true;
	$infos = $req->fetch();
	$avatar = $infos['avatar'];
	$nbposts = $infos['posts'];
}
else
	$pseudo_existe = false;

$TITLE = "ASchat : $pseudo";

$H1 = 'ASchat';

$HEADER = "    <h2>Profil de <em>$pseudo</em></h2>";

//ASIDE
ob_start();
echo <<< EOaside
    <img src="img/avatars/$avatar" alt="Avatar de $pseudo"/>
    <h3 class="pseudo">$pseudo</h3>
    <p class="posts">$nbposts posts</p>
EOaside
;

$ASIDE = ob_get_clean();

//MAIN
ob_start();
if($pseudo_existe) {
	$q = $mysql->query("select contenu, points, date_format(dateMsg, '%d/%m/%Y') as jour, date_format(dateMsg, '%H:%i') as heure from MESSAGE where pseudo = '$pseudo';");
	if($q) {
		foreach($q as $row) {
			$message = $row['contenu'];
			$points = $row['points'];
			$jour = $row['jour'];
			$heure = $row['heure'];
			echo <<< EOpost
    <article class="post">
      <p class="date">Le $jour, à $heure</p>
      <p class="points">$points points</p>
      <p class="contenu">$message</p>
    </article>
EOpost
;
		}
	}
	else
		echo "    <p>$pseudo n&apos;est pas très bavard&hellip;</p>\n";
}
else
	echo "<p class=\"err\">Le pseudo <code>$pseudo</code> n&apos;existe pas&nbsp;!</p>";
$MAIN = ob_get_clean();

include 'modèle.php';
