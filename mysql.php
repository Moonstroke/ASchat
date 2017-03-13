<?php
require 'id.php';

// instancie une connexion PDO à la base de données
function connexion($user = USER, $pass = PASS, $server = SERVER, $base = BASE, $persistante = true) {
	$pdo = new PDO("mysql:dbname=$base;host=$server;charset=utf8;", $user, $pass);
	$pdo->setAttribute(PDO::ATTR_PERSISTENT, $persistante); // => ++ vitesse exec, sécurité
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // ++ sécurité
	return $pdo;
}

// renvoie le résultat d'une requête sous forme de table HTML
function aff_table($req, $indent = "") {
	if(!$req) {
		echo "$indent<p class=\"err\">La requête a échoué.</p>\n";
	}
	else {
		$arr = $req->fetchAll(PDO::FETCH_ASSOC);
		echo "$indent<table class=\"centre\">\n";
		
		//ligne d'en-têtes
		echo "$indent  <tr>";
		foreach(array_keys($arr[0]) as $col)
			echo "<th>$col</th>";
		echo "</tr>\n";
		
		foreach($arr as $row) {
			echo "$indent  <tr>";
			foreach($row as $val) echo "<td>$val</td>";
			echo "</tr>\n";
		}
		echo "$indent</table>\n";
	}
}

