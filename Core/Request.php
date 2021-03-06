<?php

/**
 * Request wrapper (GET, POST, Session, Cookie, Server)
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Request
{
	/**
	 * $instance
	 *
	 * @var Core_Request $instance
	 */
	static private $instance = NULL;

	/**
	 * $session
	 *
	 * @var Core_Session $session
	 */
	private $session;

	/**
	 * $locale
	 *
	 * @var Core_Locale $locale
	 */
	public $locale;

	/**
	 * $view
	 *
	 * @var Core_View $view
	 */
	public $view;

	/**
	 * $vars
	 *
	 * @var string[] $var
	 */
	protected $vars;

	/**
	 * factory
	 *
	 * @return Core_Request
	 */
	static public function factory($class = FALSE)
	{
		if (self::$instance == NULL) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}

	public function __get($key)
	{
		return $this->getFrom($key, $_GET);
	}

	/**
	 * __toString
	 *
	 * @return string serialized object which is unique identification of request
	 */
	public function __toString()
	{
		$arr = array_merge( $this->getGet(), array($this->locale, $this->getVar('browser')) );
		return implode('-',$arr);
	}

	/**
	 * getGet
	 *
	 * @param string $key Wrapper for $_GET[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getGet($key = FALSE /*,$acceptHTML = FALSE*/)
	{
		if ($key !== FALSE) return $this->getFrom($key, $_GET /*, $acceptHTML*/);
		return $_GET;
	}

	/**
	 * getPost
	 *
	 * @param string $key Wrapper for $_POST[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getPost($key = FALSE /*, $acceptHTML = FALSE*/)
	{
		if ($key !== FALSE) return $this->getFrom($key, $_POST /*, $acceptHTML*/);
		return $_POST;
	}

	/**
	 * getSession
	 *
	 * @param string $key Wrapper for $_SESSION[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getSession($key = FALSE /*, $acceptHTML = FALSE*/)
	{
		if (!$this->session) $this->session = Core_Session::singleton();
		if ($key !== FALSE) return $this->getFrom($key, $_SESSION /*, $acceptHTML*/);
		return $_SESSION;
	}

	/**
	 * getServer
	 *
	 * @param string $key Wrapper for $_SERVER[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getServer($key = FALSE /*, $acceptHTML = FALSE*/)
	{
		if ($key !== FALSE) return $this->getFrom($key, $_SERVER /*, $acceptHTML*/);
		return $_SERVER;
	}

	/**
	 * getCookie
	 *
	 * @param string $key Wrapper for $_COOKIE[$key]
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 */
	public function getCookie($key /*, $acceptHTML = FALSE*/)
	{
		if ($key !== FALSE) return $this->getFrom($key, $_COOKIE /*, $acceptHTML*/);
		return $_COOKIE;
	}

	/**
	 * getVar
	 *
	 * Returns variable stored in request object
	 * @param string $key
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 * @return mixed
	 */
	public function getVar($key /*, $acceptHTML = FALSE*/)
	{
		if ($key !== FALSE) return $this->getFrom($key, $this->vars /*, $acceptHTML*/);
		return $this->vars;
	}

	/**
	 * Return whole URL
	 * @return string URL
	 */
	public function getUrl()
	{
		if ($this->getServer('HTTPS') == 'on') {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}
		$host = $protocol . '://' . $this->getServer('HTTP_HOST');
		$path = preg_replace('%^' . $host . '%', '', $this->getServer('REQUEST_URI', TRUE));
		$path = '/' . ltrim($path, '/');
		return $host . $path;
	}

	/**
	 * Is request AJAX?
	 * @return boolean
	 */
	public function isAjax()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'POST' &&  //have to be a POST request
		        (
		          (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')  //text data sent by ajax
		          || (isset($_GET['command']) && isset($_FILES)) //or files sent by hidden textarea
		        )
		);
	}

	/**
	 * setVar
	 *
	 * @param string $key Wrapper for writing $_...[$key]
	 * @param mixed $value
	 * @return void
	 */
	public function setVar($key, $value){
		$this->vars[$key] = $value;
	}

	public function checkCSRF()
	{
		$token = $_GET['token'];
		$hash_part = substr($token, 0, 32);
		$key       = substr($token, 32);
		$hash = md5($_SESSION['password'] . $key . $this->sessionID());
		if ($hash !== $hash_part) {
			throw new Exception('Cross site request forgery attact from IP: ' . $_SERVER['REMOTE_ADDR'], 401);
		}
	}

	public function sessionID()
	{
		if (!$this->session) $this->session = Core_Session::singleton();
		return $this->session->sessionID;
	}

	/**
	 * Constructor
	 */
	protected function __construct(){
		$this->setLocale();
		$this->setView();
	}

	/**
	 * getFrom
	 *
	 * @param string $key Wrapper for reading $_...[$key]
	 * @param mixed $source GET | POST | COOKIE | SESSION
	 * @param boolean $acceptHTML Should we accept HTML (or clear it)?
	 * @return mixed
	 */
	protected function getFrom($key, $source /*, $acceptHTML = FALSE*/)
	{
		if(!isset($source[$key])) return FALSE;

		//remove slashes
		if (get_magic_quotes_gpc()) {
			$output = apply($source[$key], 'stripslashes');
		} else $output = $source[$key];

		return apply($output, 'trim');
	}

	protected function setLocale(){
		if ($this->getGet('lang')){ //GET - nejvyssi priorita
			$lang = $this->getGet('lang');
			Core_Response::factory()->setCookie('lang',$lang);
			unset($_GET['lang']);
		} elseif ($this->getCookie('lang')) {
			$lang = $this->getCookie('lang'); //COOKIE - stredni priorita
		} elseif ($this->getServer('HTTP_ACCEPT_LANGUAGE')) { //HTTP - nejnizsi priorita
			$arr = explode(';',$this->getServer('HTTP_ACCEPT_LANGUAGE'));
			$languages = $arr[0];
			$arr = explode(',',$languages);
			foreach($arr as $item){
				$lang = $item;
				if (file_exists(APP_PATH . '/locales/' . ucfirst($lang).'.php')) break;
			}
		} else {
			$lang = FALSE;
		}

		if (!$lang || !file_exists(APP_PATH . '/locales/' . ucfirst($lang) . '.php')) {
			$config = Core_Config::singleton();
			$lang = $config->locale;
			Core_Response::factory()->setCookie('lang', $lang);
		}

		$this->locale = ucfirst($lang);
		Core_Locale::factory($this->locale); //creates static instance
	}

	protected function setView(){
		if ($this->getGet('ajax')    !== FALSE) $this->view = 'Ajax';
		elseif ($this->getGet('rss') !== FALSE) $this->view = 'Rss';
		elseif ($this->getGet('csv') !== FALSE) $this->view = 'Csv';

		$agent = $this->getServer('HTTP_USER_AGENT');
		if (strpos($agent, 'Opera') !== FALSE) $this->setVar('browser', 'Opera');
		if (strpos($agent, 'Gecko') !== FALSE) $this->setVar('browser', 'Firefox');
		if (strpos($agent, 'MSIE')  !== FALSE) $this->setVar('browser', 'IE');
	}
}
