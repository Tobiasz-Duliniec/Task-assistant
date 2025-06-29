<?php
/*
	File deletes a task with a specified name.
*/
if($_SERVER["REQUEST_METHOD"] == "POST"){
		$rawData = file_get_contents('php://input');
		$data = json_decode($rawData, true);
		
		$task = $data["task"];
		
		require("connect_to_database.php");

		
		if($connection){		
			mysqli_report(MYSQLI_REPORT_ALL);
			$task2 = mysqli_real_escape_string($connection, $task);
			if(mysqli_fetch_array(mysqli_query($connection, "SELECT COUNT(name) FROM tasks WHERE name='$task2'"))[0] == 1){
				$result = mysqli_query($connection, "DELETE FROM tasks WHERE name='$task2'");
				echo json_encode(array("status" => $result));
			}
			else{
				echo json_encode(array("status" => false));
			}
		}else{
			echo json_encode(array("status" => false));
		}
	}
?>
