<?php
require_once 'mysql.php';

$mysql = connexion();

session_start();
if(isset($_SESSION['pseudo'])) {
	$id = $_GET['id'];
	if($id)
		$mysql->exec("update MESSAGE set points = points + 1 where idMsg = '$id';");
}
header('Location: .');
exit;
