<?php

/**
 * Provides object access to configuration.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Config
{
	public $host;
	protected $data;
	protected static $instance = NULL;

	protected function __construct()
	{
	}

	/**
	 * singleton
	 *
	 * @return Core_Config
	 */
	public static function singleton()
	{
		if (self::$instance == NULL){
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}
	
	public function init()
	{
		$host = isset($_SERVER['HTTP_HOST'])
		        ? $_SERVER['HTTP_HOST']
		        : 'localhost';
		$host = preg_replace('/^www./', '', $host);
		$host = str_replace('.', '_', $host); //needed for advanced settings
		$host = preg_replace('/:.*$/', '', $host); //cut port from end

		$this->host = $host;
		
		$this->read(APP_PATH . '/config/' . $host . '.yml.php');
	}

	/**
	 * __get
	 *
	 * @param $key string
	 * @return string
	 */
	public function __get($key)
	{
		if (!isset($this->data->{$key})) return FALSE;
		return $this->data->{$key};
	}

	/**
	 * read
	 *
	 * Reads configuration from a file.
	 *
	 * @param string $file
	 * @param boolean $force_renew Force to reload the cache
	 * @return void
	 */
	public function read($file, $force_renew = FALSE){
		if ($this->data && !$force_renew) return TRUE;

		$arr = explode('/', $file);
		$name = $arr[count($arr) - 1];
		$cacheFile = APP_PATH . '/cache/' . $name . '.cache.php';

		if (!$force_renew && file_exists($cacheFile)) {
			include($cacheFile);
			if (/*rand(0, 10) < 8 ||*/ $time >= filemtime($file)) { //cache valid
				$this->data = $data;
				$this->prepareData();
				return TRUE;
			}
		}

		$this->data = Core_Parser_YML::read($file, $cacheFile);
		$this->prepareData();
		return TRUE;
	}

	/**
	 * prepareData
	 *
	 * Prepares configuration data
	 *
	 * @return void
	 */
	private function prepareData()
	{
		$array = $this->data;
		$array['host'] = $this->host;
		$data = convertArrayToObject($array);
		$this->data = $data;
	}
}
