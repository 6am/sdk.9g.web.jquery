<?php

class pages {
    function cache(){
		global $NGCloud;		
		$data['accesstoken'] = APITOKEN;
		$data['alias'] = $this->alias;
		$apilink = 'api.9g.com.br/v1/';
		if(isset($_GET["draft"])) $data['draft'] = $_GET["draft"];
		$page = json_decode($NGCloud->get('api.sandbox.9g.com.br/v1/pages', $data),true);
		if(@$page["error"]["header"]=="404" && $this->alias!="404") {
//			header("location: /404");
			echo "404";
			echo $this->alias;
			exit;
		}
		//no 404 template		
		if(!isset($page["fulltext"]) && $this->alias=="404") {
			header("HTTP/1.0 404 Not Found");
			echo "404";
			exit;
		}
		$fp = fopen($this->cache["filename"], 'w');
		fwrite($fp, $page["fulltext"]);
		unset($page["fulltext"]);
		
		$fp = fopen(DATA.'/pages/'.$this->cache["token"].'.json', 'w');
		fwrite($fp, json_encode($page));
		fclose($fp);
		unset($_GET["draft"]);
		unset($_GET["nocache"]);
		$this->get();
    }

    function get(){
		$this->cache["token"]=md5($this->alias);
		$this->cache["filename"]=CACHE.$this->cache["token"].".html";
		$this->cache["cached"]=(file_exists($this->cache["filename"]) ? 1 : 0);
		$this->cache["time"]=0;
		if($this->cache["cached"]==1) $this->cache["time"] = floor((time()-filemtime($this->cache["filename"]))/60);						
		
		if(($this->cache["cached"]==1 && CACHE_TIME>$this->cache["time"]) || (isset($_GET["draft"]) || isset($_GET["nocache"]) || ($this->cache["cached"]==0))) 
			$this->cache();
		

		$output["fulltext"] = file_get_contents($this->cache["filename"], 'r');
		$output["data"] = json_decode(file_get_contents(DATA.'/pages/'.$this->cache["token"].'.json', 'r'),true);
		return $output;	
	}
}

?>