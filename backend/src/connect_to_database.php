<?php
		$host = "db";
		$username = getenv("MYSQL_USER");
		$password = getenv("MYSQL_PASSWORD");
		$database = getenv("MYSQL_DATABASE");
		
		$connection = mysqli_connect($host, $username, $password, $database);
?>