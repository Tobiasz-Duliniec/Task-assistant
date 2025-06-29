async function addTask(){
	//Function for adding new tasks
	var task_title = document.getElementById("title").value;
	var task_text = document.getElementById("task").value;
	var task_status = document.getElementById("status").value;
	var task_deadline = document.getElementById("deadline").value;
	if(task_deadline === ""){
		var deadline_date = new Date();
		var year = deadline_date.getFullYear();
		var month = deadline_date.getMonth()+1 < 10 ? ("0" + (deadline_date.getMonth()+1)) : deadline_date.getMonth()+1;
		var day = deadline_date.getDate();
		task_deadline = year + "-" + month + "-" + day;
	}
	if(validateData(task_deadline)){
		var response = await fetch(document.URL + "create_task.php", {
																method: "POST",
																body: JSON.stringify({
																						"title": task_title.replace(" ", "_"),
																						"text": task_text,
																						"status": task_status,
																						"deadline": task_deadline
																					}),
																headers: {
																	"Content-Type": "application/json",
																}
																});
		var response2 = await response.json();
		if(response2["status"]){
			alert("New task has been added.");
			addElement(createElement({"name": task_title, "description": task_text, "task_status": task_status, "deadline": task_deadline}, task_title));
		}else{
			alert("Failed to create a new task: "+ response2["reason"]);
		}
	}
	updateStatusParagraph();
}

function createEditForm(toEdit){
		//Function for creating edit form
	toEditOG = toEdit;
	toEdit = toEdit.replace(" ", "_")
	var editForm = document.createElement("form");
	editForm.setAttribute("id", "edit_form_"+toEdit);
	
	var titleEdit = document.getElementById("edit_title_" + toEdit).innerText;
	var descriptionEdit = document.getElementById("edit_description_" + toEdit).innerText;
	var statusEdit = document.getElementById("edit_status_" + toEdit).innerText;
	var deadlineEdit = document.getElementById("edit_deadline_" + toEdit).innerText;
	
	var titleInput = document.createElement("input");
	titleInput.setAttribute("type", "text");
	titleInput.setAttribute("name", "title");
	titleInput.setAttribute("placeholder", "task title");
	titleInput.setAttribute("id", toEdit+"_title");
	titleInput.setAttribute("value", titleEdit);
	
	
	var descriptionInput = document.createElement("input");
	descriptionInput.setAttribute("type", "text");
	descriptionInput.setAttribute("name", "task");
	descriptionInput.setAttribute("placeholder", "task description");
	descriptionInput.setAttribute("id", toEdit+"_task");
	descriptionInput.setAttribute("value", descriptionEdit);

	var deadlineInput = document.createElement("input");
	deadlineInput.setAttribute("type", "date");
	deadlineInput.setAttribute("name", "deadline");
	deadlineInput.setAttribute("id", toEdit+"_deadline");
	deadlineInput.setAttribute("value", deadlineEdit);
	
	var statusInput = document.createElement("select");
	statusInput.setAttribute("name", "status");
	statusInput.setAttribute("id", toEdit+"_status");
	
	var option1 = document.createElement("option");
	option1.setAttribute("value", "Not started");
	option1.innerText = "Not started";
	if(statusEdit === "Not started"){
		option1.setAttribute("selected", "")
	}

	var option2 = document.createElement("option");
	option2.setAttribute("value", "Started");
	option2.innerText = "Started";
	if(statusEdit === "Started"){
		option2.setAttribute("selected", "")
	}
	
	var option3 = document.createElement("option");
	option3.setAttribute("value", "Finished");
	option3.innerText = "Finished";
	if(statusEdit === "Finished"){
		option3.setAttribute("selected", "")
	}
	
	statusInput.appendChild(option1);
	statusInput.appendChild(option2);
	statusInput.appendChild(option3);
	
	editForm.appendChild(titleInput);
	editForm.appendChild(descriptionInput);
	editForm.appendChild(deadlineInput);
	editForm.appendChild(statusInput);
	
	var sendButton = document.createElement("input");
	sendButton.setAttribute("type", "button");
	sendButton.setAttribute("value", "Update");
	sendButton.setAttribute("onClick", "updateTask('" + toEditOG + "')");
	editForm.appendChild(sendButton);
	
	return editForm;
}

function validateData(deadlineDate){
	//Function for checking data correctness
	var current_date = new Date();
	current_date = new Date(current_date.getFullYear(), current_date.getMonth(), current_date.getDate());

	var deadline_date = new Date(deadlineDate);
	deadline_date = new Date(deadline_date.getFullYear(), deadline_date.getMonth(), deadline_date.getDate());
	if((current_date.getTime() - deadline_date.getTime()) > 0){
		if(confirm("Deadline date seems to be in the past. Continue anyway?")){
			return true; 
		}else{
			return false;
		}
	}
	return true;
}

async function updateTask(toEdit){
	//Function to update tasks
	var newTitle = document.getElementById(toEdit+"_title").value;
	var newDescription = document.getElementById(toEdit+"_task").value;
	var newStatus = document.getElementById(toEdit+"_status").value;
	var newDeadline = document.getElementById(toEdit+"_deadline").value;
	if(validateData(newDeadline)){
			var response = await fetch(document.URL + "update_task.php", {
																	method: "POST",
																	body: JSON.stringify({
																							"current_title": toEdit,
																							"new_title": newTitle,
																							"text": newDescription,
																							"status": newStatus,
																							"deadline": newDeadline
																						}),
																	headers: {
																		"Content-Type": "application/json",
																	}
																	});
			var response2 = await response.json();
			if(response2["status"]){
				alert("Task has been updated.");
				document.getElementById("edit_title_" + toEdit).innerText = newTitle;
				document.getElementById("edit_description_" + toEdit).innerText = newDescription;
				document.getElementById("edit_status_" + toEdit).innerText = newStatus;
				document.getElementById("edit_deadline_" + toEdit).innerText = newDeadline;
			}else{
				alert("Failed to update a task: " + response2["reason"]);
			}
	}
}

function editTask(toEdit){
	//Functions allowing to edit tasks
	toEdit = toEdit.replace(" ", "_");
	if(document.getElementById("task_" + toEdit).childElementCount === 6){
		document.getElementById("task_" + toEdit).appendChild(createEditForm(toEdit));
	}else{
		var editForm = document.getElementById("edit_form_" + toEdit);
		editForm.remove();
	}
}

async function deleteTask(task_to_delete){
	//Function for deleting tasks
	if(confirm("Are you sure you want to delete task?")){
		var response = await fetch(document.URL + "delete_task.php", {
																	method: "POST",
																	body: JSON.stringify({"task": task_to_delete.replace(" ", "_")}),
																	headers: {
																		"Content-Type": "application/json",
																	}
																});
		var response2 = await response.json();
		if(response2["status"]){
			alert("Task succesfully deleted.");
			var element_to_remove = document.getElementById("task_" + task_to_delete.replace(" ", "_"));
			element_to_remove.addEventListener("animationend", function() {
				element_to_remove.remove();
				updateStatusParagraph();
			});
			element_to_remove.style.animation = "vanish_animation 2s";
		}else{
			alert("Failed to delete the task.");
		}
	}
}

function createElement(task_info, index){
	var index_no_space = index.replace(" ", "_");
	var task_div = document.createElement("div");
	task_div.setAttribute("id", "task_" + index_no_space);
	
	var task_text_name = document.createElement("p");	
	task_text_name.innerText = 'Task name: ';
	var title_variable = document.createElement("span");
	title_variable.setAttribute("id", "edit_title_"+index_no_space);
	title_variable.innerText = task_info['name'].replace("_", " ");
	task_text_name.appendChild(title_variable);
	var task_text_task = document.createElement("p");
	task_text_task.innerText = 'Task: ';
	var description_variable = document.createElement("span");
	description_variable.setAttribute("id", "edit_description_"+index_no_space);
	description_variable.innerText = task_info['description'];
	task_text_task.appendChild(description_variable);
	var task_text_deadline = document.createElement("p");
	task_text_deadline.innerText = 'Deadline: ';
	var deadline_variable = document.createElement("span");
	deadline_variable.setAttribute("id", "edit_deadline_"+index_no_space);
	deadline_variable.innerText = task_info['deadline'];
	task_text_deadline.appendChild(deadline_variable);
	var task_text_status = document.createElement("p");
	task_text_status.innerText = 'Status: ';
	var status_variable = document.createElement("span");
	status_variable.setAttribute("id", "edit_status_"+index_no_space);
	status_variable.innerText = task_info['task_status'];
	task_text_status.appendChild(status_variable);
	
	task_div.appendChild(task_text_name);
	task_div.appendChild(task_text_task);
	task_div.appendChild(task_text_deadline);
	task_div.appendChild(task_text_status);
	
	var task_edit_button = document.createElement("button");
	task_edit_button.setAttribute('id', 'task_edit_'+index);
	task_edit_button.setAttribute("onClick", "editTask('" + task_info["name"] + "')");
	task_edit_button.innerText = "Edit task";
	task_div.appendChild(task_edit_button);

	var task_delete_button = document.createElement("button");
	task_delete_button.setAttribute('id', 'task_delete_'+index);
	task_delete_button.setAttribute("onClick", "deleteTask('" + task_info["name"] + "')");
	task_delete_button.innerText = "Delete task";
	task_div.appendChild(task_delete_button);	
	
	return task_div;
}

function addElement(element_to_add){
	var task_list = document.getElementById("tasks");
	task_list.appendChild(element_to_add);
}

function updateStatusParagraph(){
	//Function updates information about number of tasks.
	var no_of_tasks = document.getElementById("tasks").childElementCount;
	var plural = (no_of_tasks == 1 ? "" : "s");
	document.getElementById("status_paragraph").innerText = "You have " + no_of_tasks + " task" + plural + ".";
}

async function getTasks(){
	//Function gets all the tasks
	var task_data = await fetch(document.URL + "task_lists.php");
	var task_data_json = await task_data.json();
	if(task_data_json.length > 0){
		for(var i=0;i<task_data_json.length;i++){
			addElement(createElement(JSON.parse(task_data_json[i]), JSON.parse(task_data_json[i])["name"]));
		}
	}
}
getTasks().then(() => {
	updateStatusParagraph();
});
