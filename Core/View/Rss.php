<?php

/**
* Provides RSS output
*
* @author Daniel Milde <daniel@milde.cz>
* @package Core
*/
class Core_View_Rss extends Core_View
{
	public function __construct(Core_Controller $controller,$action){
		parent::__construct($controller,$action);
	}

	/**
	 * display
	 *
	 */
	public function display(){

		$request  = $this->controller->request;
		$response = $this->controller->response;

		$request->setVar('view', 'rss');

		$createCache = FALSE;  //should we create cache file?
		$cacheLifeTime = $this->controller->cache;

		if ($cacheLifeTime > 0 && !ini_get('short_open_tag')){ //caching allowed
			$cacheFileName = $request . ".cache.php";
			$cacheFilePath = APP_PATH.'/tpl/cache/'.$cacheFileName;
			if (file_exists($cacheFilePath)){
				$time = filemtime($cacheFilePath); //unix timestamp
				$request->setVar('cacheTime', $time);
				$age = time() - $time;
				if($age < $cacheLifeTime){ //cache not expired
					$response->sendHeaders('application/xml');
					include($cacheFilePath); //display cache
					return TRUE;
				} else $createCache = TRUE; //cache expired
			} else $createCache = TRUE; //cache not exists
		}

		$request->setVar('caching', $createCache);
		if ($createCache) ob_start(); //start output buffer

		//load helpers
		if (iterable($this->controller->helpers)) {
			foreach ($this->controller->helpers as $helper) {
				$class = 'Core_Helper_' . ucfirst($helper);
				$this->controller->setData(strtolower($helper), new $class);
			}
		}

		//execute action
		if (method_exists($this->controller,'beforeAction')) $this->controller->beforeAction();
		$action = $this->action;
		$this->controller->$action();
		if (method_exists($this->controller,'afterAction')) $this->controller->afterAction();

		$data = $this->controller->getData();

		$response->sendHeaders('application/xml');

		foreach($data as $k=>$v){
			${$k} = $v;
		}
		unset($data);

		include(APP_PATH.'/tpl/rss.tpl.php');

		//write cache
		if ($createCache) {  //create cache file
			$buffer = ob_get_contents(); //write buffer to String
			ob_end_clean(); //clear buffer
			file_put_contents($cacheFilePath,$buffer);
			include($cacheFilePath);
			return TRUE;
		}
	}

	public function __destruct(){

	}
}
