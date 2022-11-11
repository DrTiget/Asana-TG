<?php
class Tasks extends Asana{
	private $user_id,$db,$init,$project_id;
	function __construct($db,$user_id) {
		$this->db = $db;
		$this->user_id = $user_id;
		$this->asana_key = $this->GetAsanaKey();
		if ($this->asana_key == false) {
			$this->init = false;
		}else{
			$this->init = true;
		}
	}

	public function CheckInit() {
		return $this->init;
	}

	private function GetAsanaKey() {
		$result = "";
		$get_key = $this->db->select(false,array("user_key","user_project_id"),"users","user_id='".$this->user_id."'");
		if ($get_key != 0) {
			$result = $get_key['user_key'];
			$this->project_id = $get_key['user_project_id'];
		}else{
			$result = false;
		}
		return $result;
	}

	public function GetTasksToday() {
		$result = array();
		$tasks = $this->GetAllTasks($this->project_id);
		for($i=0;$i<count($tasks);$i++) {
			if (date("d.m.Y", strtotime($tasks[$i]['task']['due_on'])) == date("d.m.Y")) {
				echo date("d.m.Y", strtotime($tasks[$i]['task']['due_on']))." == ".date("d.m.Y")."<br>";
				array_push($result,$tasks[$i]);
			}else{
				echo date("d.m.Y", strtotime($tasks[$i]['task']['due_on']))." != ".date("d.m.Y")."<br>";
			}
		}
		return $result;
	}

	public function GetTasksLost() {
		$result = array();
		$tasks = $this->GetAllTasks($this->project_id);
		for($i=0;$i<count($tasks);$i++) {
			if ($tasks[$i]['task']['completed'] == false AND strtotime($tasks[$i]['task']['due_on']) < time()) {
				array_push($result,$tasks[$i]);
			}
		}
		return $result;
	}

	public function GetTasksActive() {
		$result = array();
		$tasks = $this->GetAllTasks($this->project_id);
		for($i=0;$i<count($tasks);$i++) {
			if ($tasks[$i]['task']['completed'] == false) {
				array_push($result,$tasks[$i]);
			}
		}
		return $result;
	}

	public function GetTasksAll() {
		$result = array();
		$tasks = $this->GetAllTasks($this->project_id);
		return $tasks;
	}
}
?>