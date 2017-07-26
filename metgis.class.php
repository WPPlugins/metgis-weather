<?php

class metgisWeather {
	
	public $source = "http://api.metgis.com/forecast/";
	public $sourcemethod = "f";
	public $location = "47.211290, 11.452218";
	public $alt = "2246";
	public $days = "4";
	public $key = "";
	public $cachefile = "";
	public $cachetime = "3600";
	public $title = "Demo Plugin";
	public $options = array();
	
	public $data = "";
	
	function __construct($options) {
		foreach($options as $key=>$value) {
			if(isset($this->$key)) $this->$key = $value; 
		}
		$this->options = $options;
		$cache_sum = substr(md5(json_encode($options)),8);
		$this->cachefile=$this->cachefile."cache_".$cache_sum.".json";
		$this->clearCache($this->cachefile);
		$this->data = $this->getData();
	}
	
	function getData() {
		if(!file_exists($this->cachefile) || filemtime($this->cachefile) < time()-3600) {
			$latlon = explode(",",$this->location);
			$additionparameters = "";
			if(!strstr($this->options["lang"],"de_")) $additionparameters.="&lang=en";
			if(isset($this->options["show451"])) $additionparameters.="&tempU=f";
			if(!isset($this->options["showKMS"])) $additionparameters.="&windU=ms";
			if(isset($this->options["showINCH"])) $additionparameters.="&linU=ft";
					
			$source = $this->source."?key=".$this->key."&lat=".trim($latlon[0])."&lon=".trim($latlon[1])."&alt=".$this->alt."&v=plugin".$this->days.$additionparameters;
			
			if(ini_get('allow_url_fopen')) {
				$data = file_get_contents($source);
			} else if(function_exists('curl_version')) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $source);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$data = curl_exec($curl);
				curl_close($curl);
			} else {
				$data="";				
			}
			/* transform data */			
			if(!$data || $data=="" || $data=="{}" || count(json_decode($data)) == 0) {
				$data = array("error"=>"true","description"=>"MetGIS Service not reachable. Please check your API-Key and make sure, file_get_contents() with 'allow_url_fopen' or curl is enabled on this server.");
			} else {
				$data = json_decode($data);
			}
			
			$newData = array();
			foreach($data as $key=>$value) {
				if(is_array($value)) {
					for($i=0;$i<$this->days;$i++) {
						$newData["data"][$i][$key] = $value[$i];
					}
				} else {
					$newData["info"][$key] = $value;
				}
			}			
			if(!isset($newData["info"]["error"])) {
				file_put_contents($this->cachefile, json_encode($newData));
			}
			$data = json_decode(json_encode($newData));
		} else {
			$data = json_decode(file_get_contents($this->cachefile));
		}
		return $data;
			
	}

	function clearCache($cacheFile) {
		$dir = dirname($cacheFile);
		foreach(glob($dir.'/cache_*.*') as $c){
			if($c != $cacheFile) {
				unlink($c);
			}
		}
	}
	
}



?>