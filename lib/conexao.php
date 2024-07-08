<?php 

	$server = 'localhost';  
	$user = 'root';
	$password = 'root';
	$db_name = 'restaurante';
	$port = 3306;

	$db_connect = new mysqli($server, $user, $password, $db_name, $port);

	if ($db_connect->connect_error) {
		die('Não foi possível conectar à base de dados. ' . $db_connect->connect_error);
	} 

	mysqli_set_charset($db_connect,"utf8");

?>
