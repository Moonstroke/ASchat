<?php
require_once 'mysql.php';
$mysql = connexion();

$connecte = false;
session_start();
if(isset($_POST['action']) && $_POST['action'] == 'Déconnexion') { // Déconnexion
	unset($_SESSION['pseudo']);
	unset($_SESSION['avatar']);
	session_write_close();
	$mysql = null;
	exit;
}
elseif(!empty($_POST['pseudo'])) { // Connexion
	$login = $mysql->prepare('select pseudo, mdp, avatar from POSTEUR where pseudo = :pseudo and mdp = :mdp', array(PDO::ATTR_CURSOR => PDO:: CURSOR_FWDONLY));
	$login->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$login->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
	if($login->execute()) {
		$_SESSION = $login->fetch(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
		if(!empty($_SESSION))
			$connecte = true;
		else
			$er_co = "<p class=\"err\">Mot de passe incorrect</p>\n";
	}
	unset($login); // on passe le balai
}
elseif(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) { // déja connecté
	$pseudo = $_SESSION['pseudo'];
	$connecte = true;
}
$TITLE = 'ASchat';
$HEADER = '    <h2>Version web <code>0.4</code></h2>';


// NAV
ob_start();
if($connecte) {
	$mes_posts = $mysql->query("select count(posts) from POSTEUR where pseudo = '$pseudo';");
	$mes_points = 0;
	foreach($mysql->query("select sum(points) from MESSAGE where pseudo = '$pseudo';") as $row)
		$self_points += $row['points'];

	?>
      <a href="profil.php?pseudo=<?= $pseudo ?>"  id="self">
        <img class="avatar" src="img/avatars/<?= $_SESSION['avatar'] ?>"/>
        <h3 class="pseudo"><?= $pseudo ?></h3>
        <p class="posts"><?= $self_posts ?> posts</p>
        <p class="points"><?= $self_points ?> points</p>
      </a>
      <form method="post" class="inline"><input type="hidden" name="action" value="Déconnexion"/><button class="x" onclick="this.parentNode.submit()">&#x2716;</button></form>
<?php
}
$ordre = $_GET['ordre'] ?? 'asc';
$jour = $_GET['jour'] ?? date('d/m/y');
?>
      <section class="separateur"></section>
      <form class="inline" method="GET">
        <label for="jour">Jour à afficher&nbsp;:</label><select id="jour" name="jour" onchange="this.parentNode.submit()">
<?php
foreach($mysql->query("select date_format(dateMsg, '%d/%m/%y') as jour from MESSAGE order by dateMsg;") as $row) {
	$_jour = $row['jour'];
	if($_jour == $jour)
		echo "<option value=\"$_jour\" selected>$_jour</option>\n";
	else
		echo "<option value=\"$_jour\">$_jour</option>\n";
}
?>
        </select>
      </form>

      <form class="inline" method="GET">
        <label for="ordre">Ordre d&apos;afichage&nbsp;:</label><select name="ordre" onchange="this.parentNode.submit()">
          <option value="asc"<?= $ordre == 'asc' ? ' selected' : '' ?>>Plus ancien d'abord</option>
          <option value="desc"<?= $ordre == 'desc' ? ' selected' : '' ?>>Plus récent d'abord</option>
        </select>
      </form>
<?php

$NAV = ob_get_clean();

// ASIDE
ob_start();

if($connecte) {
	?>
    <form id="form-submit" action="post.php" method="POST">
      <textarea id="message" name="message" maxlength="255" placeholder="Contenu du message" required></textarea>
      <p>255 caractères max.</p>
      <input type="reset" value="Vider"/><input type="submit" value="Balancer"/>
    </form>
<?php
}
else {
	?>
    <form id="connexion" method="POST">
      <fieldset>
        <legend>Connexion</legend>
        <?= @$er_co ?>
        <label for="pseudo">Pseudo</label><input type="text" name="pseudo" placeholder="Pseudo" maxlength="16" required/>
        <label for="mdp">Mot de passe</label><input type="password" name="mdp" placeholder="Mot de passe" maxlength="16" required/>
        <input type="reset" value="Vider"/><input type="submit" value="Connexion"/>
      </fieldset>
    </form>
    <p>Pas encore inscrit&nbsp;? C&apos;est par <a href="inscription.php">ici</a>&nbsp;!</p>
<?php
}
$ASIDE = ob_get_clean();

// MAIN
ob_start();

$sql = 'select pseudo, idMsg, date_format(dateMsg, "%H:%i") as heure, contenu, avatar, posts from MESSAGE natural join POSTEUR where date_format(dateMsg, "%d/%m/%y") = :jour order by dateMsg :ordre;';
$req = $mysql->prepare($sql);

if($req) {
	$req->bindValue(':jour', $jour, PDO::PARAM_STR);
	$req->bindValue(':ordre', $ordre, PDO::PARAM_STR);

	$pseudo_msg = '';
	$req->bindColumn('pseudo', $pseudo_msg, PDO::PARAM_STR);
	$idMsg = '';
	$req->bindColumn('idMsg', $id_msg, PDO::PARAM_STR);
	$heure = '';
	$req->bindColumn('heure', $heure, PDO::PARAM_STR);
	$contenu = '';
	$req->bindColumn('pseudo', $contenu, PDO::PARAM_STR);
	$avatar = '';
	$req->bindColumn('pseudo', $avatar_msg, PDO::PARAM_STR);
	$posts = '';
	$req->bindColumn('posts', $posts, PDO::PARAM_STR);
	$req->execute();

	$nb_msg = 0;
	while($req->fetch(PDO::FETCH_BOUND)) {
		?>
		<article class="post">
		  <header class="user">
		    <img src="img/avatars/<?= $avatar ?>" alt="Avatar de <?= $pseudo_msg ?>" class="avatar"/>
		    <h3 class="pseudo"><?= $pseudo_msg ?></h3>
		    <p class="posts"><?= $posts ?> posts</code>
		  </header>
		  <p class="heure"><?= $heure ?></p>
		  <p class="points"><?= $points ?> points<a href="plussoie.php?id=<?= $idMsg; ?>">+</a></p>
		  <p class="message"><?= $contenu ?></p>
		</article>
	<?php
	$nb_msg += 1;
	}
}
if($nb_msg == 0) echo '      <p>Activité&nbsp;: désertique</p>';

$MAIN = ob_get_clean();

include 'modèle.php';
// v1.0 : AJAX
// schnaps.it : nice stuff
