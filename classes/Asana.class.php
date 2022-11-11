<?php
class Asana{
	protected $asana_key;
	public function GetAllTasks($project_id) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://app.asana.com/api/1.0/tasks?project='.$project_id,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Accept: application/json',
		    'Authorization: Bearer '.$this->asana_key
		  ),
		));

		$response = curl_exec($curl);
		$response = json_decode($response,true);
		curl_close($curl);
		if (isset($response['data'])) {
			$data = $response['data'];
			for($i=0;$i<count($data);$i++) {
				$task_one = $this->GetTask($data[$i]['gid']);
				$data[$i]['task'] = $task_one;
			}
		}
		return $data;
	}

	public function GetTask($task_id) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://app.asana.com/api/1.0/tasks/'.$task_id,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Accept: application/json',
		    'Authorization: Bearer '.$this->asana_key
		  ),
		));

		$response = curl_exec($curl);
		$response = json_decode($response,true);
		$response = $response['data'];
		return $response;
	}
}
?>