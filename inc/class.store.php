<?php
class store {

	private $expiration, $path, $domain, $secure, $httponly, $name, $value;
	function __construct($name="mainStore",$expire="", $subDomains=false){
		if ($expire == '0'){
			$this->expiration = 0;
		} else {
			$expire = ($expire) ? $expire : ((7 * 24) * 60) * 60;
			$this->expiration = time() + $expire;
		}

		$srvname = strtolower($_SERVER["SERVER_NAME"]);

		$srvname = str_replace("www.","",$srvname);

		//echo $srvname;


		if ($subDomains){
			$srvname = '.' . $srvname;
		} else {
			$srvname = strtolower($_SERVER["SERVER_NAME"]);
		}
		$this->path = "/";
		$this->domain = $srvname;
		$this->name = $name;


//echo $this->domain;
		if (!isset($_COOKIE[$this->name])) {
			$value = $this->_serialize(array());
			$this->value = array();
			setcookie($this->name, $value, time() + $this->expiration, $this->path, $this->domain, $this->secure, $this->httponly);

		} else {
			$this->value = $this->_deserialize($_COOKIE[$this->name]);
		}


	}

	public function get($name){
		return (isset($this->value[$name]))? $this->value[$name]:"";
	}

	public function set($name,$value=""){
		if ($value){
			$this->value[$name] = $value;
		} else {
			if (isset($this->value[$name])){
				unset($this->value[$name]);
			}
		}

		$this->write();
		return $value;
	}

	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function remove($name) {
		if (isset($this->value[$name])) {
			unset($this->value[$name]);
		}
		$this->write();
	}
	private function _deserialize(){
		$cookie = (isset($_COOKIE[$this->name]))? $_COOKIE[$this->name]:"";
		return unserialize($cookie);
	}
	private function _serialize($str){
		return serialize($str);

	}
	private function write(){

		$value = $this->_serialize($this->value);
	
		$_COOKIE[$this->name] = $value;
		//echo ("name: " . $this->name . " - value: " . $value . " - expiration: " . time() + $this->expiration . " - path: " . $this->path . " - domain: " . $this->domain . " - secure: " . $this->secure . " - httponly: " . $this->httponly . "<br>");
		setcookie($this->name, $value, $this->expiration, $this->path, $this->domain, $this->secure, $this->httponly);
		return $this->value;

	}
	public function show(){
		return $this->_deserialize($this->value);
	}



}