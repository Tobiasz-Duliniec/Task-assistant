<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Tobiasz Duliniec">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="task_manager.js" defer></script>
		<link rel="stylesheet" href="style.css">
		<title>Task assistant</title>
	</head>
	<body>
		<header><h1>Task assistant</h1></header>
		
		<p id="status_paragraph"></p>
		
		<form id="input_form">
			<label for="title">Task title:</label>
			<input type="text" name="title" id="title" placeholder="task title" required>
			<label for="task">Task description:</label>
			<input type="text" name="task" id="task" placeholder="task description" required>
			<label for="deadline">Deadline:</label>
			<input type="date" name="deadline" id="deadline" required>
			<select name="status" id="status">
				<option value="Not started">Not started</option>
				<option value="Started">Started</option>
				<option value="Finished">Finished</option>
			</select>
			<input type="button" value="Add" onclick="addTask()">
		</form>
		
		<div id="tasks">
		</div>
		
		<footer>
			Created by Tobiasz Duliniec.
		</footer>
		
	</body>
</html>