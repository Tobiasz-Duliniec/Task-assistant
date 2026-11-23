<?php
/*
	File deletes a task with a specified name.
*/
if($_SERVER["REQUEST_METHOD"] == "DELETE"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		$task = $data["task"];
		
		require("connect_to_database.php");

		
		if($connection){		
			//mysqli_report(MYSQLI_REPORT_ALL);
			mysqli_report(MYSQLI_REPORT_ALL & ~MYSQLI_REPORT_INDEX);
			$task2 = mysqli_real_escape_string($connection, $task);
			try{
				if(mysqli_fetch_array(mysqli_query($connection, "SELECT COUNT(name) FROM tasks WHERE name='$task2'"))[0] == 1){
					$result = mysqli_query($connection, "DELETE FROM tasks WHERE name='$task2'");
					echo json_encode(array("status" => $result));
					return;
				}
				else{
					http_response_code(500);
					echo json_encode(array("status" => false, "reason" => "server side error has occured"));
					return;
				}
			}catch(Exception $e){
				http_response_code(500);
				echo json_encode(array("status" => false, "reason" => "server side error has occured"));
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
