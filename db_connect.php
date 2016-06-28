<?php
	require "db_config.php";
	try {
		# MS SQL Server and Sybase with PDO_DBLIB
		$connection = new PDO($dsn, $username, $password);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>