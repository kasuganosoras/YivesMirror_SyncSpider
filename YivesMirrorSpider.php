<?php
class ZeroDream {
	
	public function getVersionList($dir) {
		$res = scandir($dir);
		$list = Array();
		foreach($res as $file) {
			if((is_dir($dir . $file)) && ($file !== ".") && ($file !== "..")) {
				$list[] = $file;
			}
		}
		return $list;
	}
	
	public function http($url, $post = '', $cookie = '', $returnCookie = 0) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		/** Use ShadowsocksR **/
		//curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);  
		//curl_setopt($curl, CURLOPT_PROXY, "192.168.3.1:8080");
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_REFERER, $url);
		if ($post) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if ($cookie) {
			curl_setopt($curl, CURLOPT_COOKIE, $cookie);
		}
		curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {
			return curl_error($curl);
		}
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($code !== 200) {
			$data = false;
		}
		curl_close($curl);
		return $data;
	}
	
	public function Println($str) {
		echo date("[Y-m-d H:i:s] ") . "{$str}\n";
	}
	
	public function download($save, $url) {
		if(file_exists($save)) {
			$this->Println("File: {$save} already exist!");
			return;
		} else {
			$this->Println("Download \"{$url}\" to \"{$save}\"");
			system("wget \"{$url}\" -O \"{$save}\"");
			/*$data = file_get_contents($url);
			file_put_contents($save, $data);*/
		}
	}
}
/** Multi-Thread download test **/
class Download extends Thread {
	
	public $save;
	public $url;
	
	public function __construct($save, $url) {
		$this->save = $save;
		$this->url = $url;
	}
	
	public function run() {
		$save = $this->save;
		$url = $this->url;
		$ZeroDream = new ZeroDream();
		if(file_exists($save)) {
			$ZeroDream->Println("File: {$save} already exist!");
			return;
		} else {
			$ZeroDream->Println("wget \"{$url}\" -O \"{$save}\"");
			//system("wget \"{$url}\" -O \"{$save}\"");
			/*$data = file_get_contents($url);
			file_put_contents($save, $data);*/
			$ZeroDream->Println("Successful download: " . basename($url));
		}
	}
}

$root = "/data/wwwroot/cdn.tcotp.cn/download/server/";
$ZeroDream = new ZeroDream();
while(true) {
	$list = $ZeroDream->getVersionList($root);
	foreach($list as $item) {
		$temp = strtolower($item);
		switch($temp) {
			case 'paperspigot':
				$temp = 'paper';
				break;
			case 'torchspigot':
				$temp = 'torch';
				break;
		}
		$data = $ZeroDream->http("https://yivesmirror.com/api/list/{$temp}");
		$data = @json_decode($data, true);
		if(!$data) {
			$ZeroDream->Println("Failed get list {$item}");
		} else {
			if(isset($data['error'])) {
				$ZeroDream->Println($data['error']);
			} else {
				$thread_list = Array();
				foreach($data as $version) {
					//$thread_list[] = new Download("{$root}{$item}/{$version}", "https://yivesmirror.com/files/{$temp}/{$version}");
					$ZeroDream->download("{$root}{$item}/{$version}", "https://yivesmirror.com/files/{$temp}/{$version}");
					$ZeroDream->Println("Successful download: {$version}");
				}
				/*foreach($thread_list as $thread) {
					$thread->start();
				}
				$i = 0;
				while($i < count($thread_list)) {
					foreach($thread_list as $thread) {
						if(!$thread->isRunning()) {
							$i++;
						}
					}
				}*/
				$ZeroDream->Println("Successful download all file of: {$item}");
			}
		}
	}
	$ZeroDream->Println("Update complate!");
	sleep(3600);
}
