<?php
	/*
		File updates data for a specified task.
	*/

	if($_SERVER["REQUEST_METHOD"] == "PUT"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		require("connect_to_database.php");

		
		$new_title = mysqli_real_escape_string($connection, $data["new_title"]);
		$current_title = mysqli_real_escape_string($connection, $data["current_title"]);
		$description = mysqli_real_escape_string($connection, $data["text"]);
		$status = mysqli_real_escape_string($connection, $data["status"]);
		$deadline = mysqli_real_escape_string($connection, $data["deadline"]);
		
		if(is_null($new_title) || $new_title==""){
				http_response_code(400);
				echo json_encode(array("status" => false, "reason" => "task title must be provided"));
				return;
			}
		if(is_null($description) || $description==""){
			http_response_code(400);
			echo json_encode(array("status" => false, "reason" => "task description must be provided"));
			return;
		}
		
		//mysqli_report(MYSQLI_REPORT_ALL);
		mysqli_report(MYSQLI_REPORT_ALL & ~MYSQLI_REPORT_INDEX);
		if($connection){
			try{
				$result = mysqli_query($connection, "UPDATE tasks SET name='$new_title', description='$description', task_status='$status', deadline='$deadline' WHERE name='$current_title'");
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
					echo json_encode(array("status" => false, "reason" => "server side error has occured"));
				}
				return;
			}
		}else{
			http_response_code(500);
			echo json_encode(array("status" => false, "reason" => "server side error has occured"));
			return;
		}
	}else{
		http_response_code(405);
		echo json_encode(array("status" => false, "reason" => "invalid HTTP method"));
		return;
	}
?>