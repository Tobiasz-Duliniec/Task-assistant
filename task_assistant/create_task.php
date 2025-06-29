<?php
	/*
		File creates a new task in the database.
	*/
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		require("connect_to_database.php");
		
		$title = mysqli_real_escape_string($connection, $data["title"]);
		$description = mysqli_real_escape_string($connection, $data["text"]);
		$status = mysqli_real_escape_string($connection, $data["status"]);
		$deadline = strtotime(mysqli_real_escape_string($connection, $data["deadline"]));
		
		
		if($connection){
			mysqli_report(MYSQLI_REPORT_ALL);
			try{
				$deadline = date('Y-m-d', $deadline);
				$result = mysqli_query($connection, "INSERT INTO tasks(name, description, task_status, deadline) VALUES ('$title', '$description', '$status', '$deadline')");
				echo json_encode(array("status" => $result));
			}catch(Exception $e){

				if($e->getCode() == 1062){
					echo json_encode(array("status" => false, "reason" => "every task has to have a unique name"));
				}
			}
		}else{
			echo json_encode(array("status" => false, "reason" => "failed to connect to the database"));
		}
	}
?>