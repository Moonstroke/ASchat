<?php
require_once 'mysql.php';
$mysql = connexion();

session_start();
$pseudo = $_SESSION['pseudo'];
$msg = htmlspecialchars($_POST['message'], ENT_QUOTES);

$ok = $mysql->exec("insert into MESSAGE (dateMsg, pseudo, contenu) values (now(), '$pseudo', '$msg');");
if($ok === 1) {
	$mysql->exec("update POSTEUR set posts = posts + 1 where pseudo = '$pseudo';");
	header('Location: .');
	exit;
}
else {
	$TITLE = 'Oups';
	$H1 = 'Erreur lors du post';
	$MAIN = '<p class="err">Impossible de poster le message.</p>';
	include 'modèle.php';
}
