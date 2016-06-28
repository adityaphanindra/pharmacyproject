<?php
	include 'db_config.php';
	try {
		# MS SQL Server and Sybase with PDO_DBLIB
		$connection = null;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>