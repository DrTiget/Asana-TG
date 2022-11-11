<?php
	include "config.php";
	include 'classes/Asana.class.php';
	include "classes/Tasks.class.php";
	$tasks = new Tasks($db,1);
	$task_active = $tasks->GetTasksToday();
	for($i=0;$i<count($task_active);$i++) {
		$text = $task_active[$i]['task']['name'].PHP_EOL.$task_active[$i]['task']['notes'].PHP_EOL."Добавлена: ".date("d.m.Y H:i:s", strtotime($task_active[$i]['task']['created_at'])).PHP_EOL."Дедлайн: ".date("d.m.Y H:i:s", strtotime($task_active[$i]['task']['due_on']));
		echo $text."<br>";
	}
?>