<?php
	/*
		File updates data for a specified task.
	*/

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		require("connect_to_database.php");

		
		$new_title = mysqli_real_escape_string($connection, $data["new_title"]);
		$current_title = mysqli_real_escape_string($connection, $data["current_title"]);
		$description = mysqli_real_escape_string($connection, $data["text"]);
		$status = mysqli_real_escape_string($connection, $data["status"]);
		$deadline = mysqli_real_escape_string($connection, $data["deadline"]);
		
		mysqli_report(MYSQLI_REPORT_ALL);
		
		if($connection){
			try{
				$result = mysqli_query($connection, "UPDATE tasks SET name='$new_title', description='$description', task_status='$status', deadline='$deadline' WHERE name='$current_title'");
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