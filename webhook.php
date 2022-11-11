<?php
	include "config.php";
	include 'classes/Asana.class.php';
	include "classes/Tasks.class.php";
	if (isset($_REQUEST['data'])) {
		$data = json_decode($_REQUEST['data'],true);
	}else{
		$data = json_decode(file_get_contents('php://input'),JSON_OBJECT_AS_ARRAY);
	}
	$chat_id = $data['message']['chat']['id'];
	$text = $data['message']['text'];
	Send($chat_id,"Подожди немного");
	
	$get_user_id = $db->select(false,array("user_id"),"users","user_chat_id='$chat_id'");
	if ($get_user_id != 0) {
		$user_id = $get_user_id['user_id'];
		$tasks = new Tasks($db,$user_id);
		if ($tasks->CheckInit() == false) {
			send($chat_id,"Асана упала(");
			exit();
		}
		if ($text == "Покажи все задачи") {
			$all_tasks = $tasks->GetTasksAll();
			if (count($all_tasks) == 0) {
				Send($chat_id,"Нет задач!");
			}else{
				for($i=0;$i<count($all_tasks);$i++) {
					$text = $all_tasks[$i]['task']['name'].PHP_EOL.$all_tasks[$i]['task']['notes'].PHP_EOL."Добавлена: ".date("d.m.Y H:i:s", strtotime($all_tasks[$i]['task']['created_at'])).PHP_EOL."Дедлайн: ".date("d.m.Y", strtotime($all_tasks[$i]['task']['due_on'])).PHP_EOL;
					if ($all_tasks[$i]['task']['completed']) {
						$text.="✅ Выполнено! ✅";
					}else{
						$text.="❌ Не Выполнено! ❌";
					}
					Send($chat_id,$text);
				}
			}
		}else
		if ($text == "Покажи мои задачи на сегодня") {
			$task_today = $tasks->GetTasksToday();
			if (count($task_today) == 0) {
				Send($chat_id,"Нет задач на сегодня!");
			}else{
				for($i=0;$i<count($task_today);$i++) {
					$text = $task_today[$i]['task']['name'].PHP_EOL.$task_today[$i]['task']['notes'].PHP_EOL."Добавлена: ".date("d.m.Y H:i:s", strtotime($task_today[$i]['task']['created_at'])).PHP_EOL."Дедлайн: ".date("d.m.Y", strtotime($task_today[$i]['task']['due_on'])).PHP_EOL;
					if ($task_today[$i]['task']['completed']) {
						$text.="✅ Выполнено! ✅";
					}else{
						$text.="❌ Не Выполнено! ❌";
					}
					Send($chat_id,$text);
				}
			}
		}else if ($text == "Покажи мои незакрытые задачи") {
			$task_lost = $tasks->GetTasksLost();
			if (count($task_lost) == 0) {
				Send($chat_id,"Нет незакрытых задач!");	
			}else{
				for($i=0;$i<count($task_lost);$i++) {
					$text = $task_lost[$i]['task']['name'].PHP_EOL.$task_lost[$i]['task']['notes'].PHP_EOL."Добавлена: ".date("d.m.Y H:i:s", strtotime($task_lost[$i]['task']['created_at'])).PHP_EOL."Дедлайн: ".date("d.m.Y", strtotime($task_lost[$i]['task']['due_on']));
					Send($chat_id,$text);
				}
			}
		}else if ($text == "Покажи активные задачи") {
			$task_active = $tasks->GetTasksActive();
			if (count($task_active) == 0) {
				Send($chat_id,"Нет активных задач!");
			}else{
				for($i=0;$i<count($task_active);$i++) {
					$text = $task_active[$i]['task']['name'].PHP_EOL.$task_active[$i]['task']['notes'].PHP_EOL."Добавлена: ".date("d.m.Y H:i:s", strtotime($task_active[$i]['task']['created_at'])).PHP_EOL."Дедлайн: ".date("d.m.Y", strtotime($task_active[$i]['task']['due_on']));
					Send($chat_id,$text);
				}
			}
		}else{
			Send($chat_id,"Неизвестная команда, подумай еще");
		}
	}else{
		Send($chat_id,"Вас нет в базе данных!");
	}
?>