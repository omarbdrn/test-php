<?php
class Colego{
	private $api_key;
	private $url;

	const COLEGO_PROD = "https://api.colego.internal/api/v1/";
	const COLEGO_SANDBOX = "https://staging.colego.internal/api/v1/";

	function __construct($api_key, $env){
		$this->api_key = $api_key;
		if ($env == 'PROD')
			$this->url = self::COLEGO_PROD;
		else
			$this->url = self::COLEGO_SANDBOX;
	}

	/*
	** getScan
	** Retrieves scan details
	** Input: ScanHash 
	** Output: ScanObject, refer to datastruct.php
	*/
	public function getScan($hash){
		$req = curl_init($this->url."scans/".$hash);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
			'Authorization: Token '.$this->api_key
			)
		);
		return $this->execRequest($req);
	}

	/*
	** listScans
	** Returns all owned scans
	** Input: None 
	** Output: Array(ScanObject), refer to datastruct.php
	*/
	public function listScans(){
		$req = curl_init($this->url."scans");
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
			'Authorization: Token '.$this->api_key
			)
		);
		return $this->execRequest($req);
	}

	/*
	** getMachine
	** Performs a lookup on a specific domain or ip address returning a json of running services and whois information
	** Input: Host (IP,Domain)
	** Output: MachineObject, refer to datastruct.php
	*/
	public function getMachine($host){
		$req = curl_init($this->url."machines/".$host);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
			'Authorization: Token '.$this->api_key
			)
		);
		return $this->execRequest($req);
	}

	/*
	** createScan
	** Input: ScanDTO Object
	** Output: ScanObject, refer to datastruct.php
	*/
	public function createScan($data){
		$jsonData = json_encode($data);
		$req = curl_init($this->url."scans");
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($req, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLINFO_HEADER_OUT, true);
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData),
			'Authorization: Bearer '.$this->api_key
			)
		);
		return $this->execRequest($req);
	}

	/*
	** createAsset
	** Input: AssetObject
	** Output: $status:boolean
	*/
	public function createAsset($data){
		$jsonData = json_encode($data);
		$req = curl_init($this->url."assets");
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($req, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLINFO_HEADER_OUT, true);
		curl_setopt($req, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData),
			'Authorization: Bearer '.$this->api_key
			)
		);
		return $this->execRequest($req);
	}

	public function getAgentID($jsonData) {
		$data = json_decode($jsonData);
		return $data->agent->id;
	}

	public function getScanHash($jsonData) {
		$data = json_decode($jsonData);
		return $data->details->hash;
	}

	public function getScanFrequency($jsonData) {
		$data = json_decode($jsonData);
		return $data->template->frequency;
	}

	private function execRequest($req) {
		$ret = curl_exec($req);
		$httpCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		curl_close($req);
		if($httpCode == 201 || $httpCode == 200)
			return $ret;
		elseif ($httpCode == 401)
			return "API Key is required to access this resource.";
		elseif ($httpCode == 403)
			return $ret->scope." Permission required to access this resource.";
		else
			return "HTTP Error ".$httpCode;
	}
}
?>
