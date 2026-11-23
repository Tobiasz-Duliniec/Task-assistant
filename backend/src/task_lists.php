<?php

	/*
		File returns a JSON file of all tasks.
	*/

	require("connect_to_database.php");

	
	if($connection){
		$tasks = mysqli_query($connection, "SELECT * FROM tasks");
		$results = [];
		while($tasks_assoc = mysqli_fetch_assoc($tasks)){
			$results[] = json_encode($tasks_assoc);
		}
		echo json_encode($results);
	}else{
		http_response_code(500);
		echo json_encode(array("status" => false, "reason" => "internal server error"));
		return;
	}
?>