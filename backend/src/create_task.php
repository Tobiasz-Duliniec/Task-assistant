<?php
	/*
		File creates a new task in the database.
	*/
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		require("connect_to_database.php");
		
	
		
		
		if($connection){
			$title = mysqli_real_escape_string($connection, $data["title"]);
			$description = mysqli_real_escape_string($connection, $data["text"]);
			$status = mysqli_real_escape_string($connection, $data["status"]);
			$deadline = strtotime(mysqli_real_escape_string($connection, $data["deadline"]));
			if(is_null($title) || $title==""){
				http_response_code(400);
				echo json_encode(array("status" => false, "reason" => "task title must be provided"));
				return;
			}
			if(is_null($description) || $title==""){
				http_response_code(400);
				echo json_encode(array("status" => false, "reason" => "task description must be provided"));
				return;
			}
			mysqli_report(MYSQLI_REPORT_ALL);
			try{
				$deadline = date('Y-m-d', $deadline);
				$result = mysqli_query($connection, "INSERT INTO tasks(name, description, task_status, deadline) VALUES ('$title', '$description', '$status', '$deadline')");
				echo json_encode(array("status" => $result));
				return;
			}catch(Exception $e){
				if($e->getCode() == 1062){
					http_response_code(400);
					echo json_encode(array("status" => false, "reason" => "every task has to have a unique name"));
				}elseif($e->getCode() == 1265){
					http_response_code(400);
					echo json_encode(array("status" => false, "reason" => "invalid status value"));
				}else{
					http_response_code(500);
					echo json_encode(array("status" => false, "reason" => "internal server error"));
				}
				return;
			}
		}else{
			http_response_code(500);
			echo json_encode(array("status" => false, "reason" => "internal server error"));
			return;
		}
	}else{
		http_response_code(405);
		echo json_encode(array("status" => false, "reason" => "invalid HTTP method"));
		return;
	}
?>