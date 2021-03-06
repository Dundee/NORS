<?php
session_start();
session_id('c1e9187c23aa854024fa2147741f6a38');
$_SERVER = array();
$_SERVER['PHP_SELF'] = '/nors4/index.php';
$_SERVER['REQUEST_URI'] = '/nors4/post/';
$_SERVER['SCRIPT_NAME'] = '/nors4/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REMOTE_ADDR'] = 'unit';
$_GET['controller'] = 'post';


define('HIGH_PERFORMANCE', 0);
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

define('APP_PATH', dirname(__FILE__) . '/..');
require_once('../Core/Functions.php');

setUrlPath();

Core_Config::singleton()->init();


$router   = Core_Router::factory();
$request  = Core_Request ::factory();
$router->decodeUrl($request, FALSE);

/* ************* TEST ************** */

class Core_DB_Test extends Core_DB
{
	protected function connect(){}
	protected function sql_query($query){}
	public function query($query){}
	public function silentQuery($query){}
	public function getRow($query = false){}
	public function num($query = false){}
	public function id($query = false){}
	public function __destruct(){}
	public function getRows($query = false)
	{
		if (strpos($query, 'user')) {
			return array(
				array('id_user' => 1,
				      'name' => 'dundee'),
				array('id_user' => 2,
				      'name' => 'test'),
			);
		} else {
			return array(
				array('id_category' => 1,
				      'name' => 'test',
				      'category' => 0)
			);
		}
	}
}

Core_DB::singleton();
Core_DB::_setInstance(new Core_DB_Test);

$admin = new Core_Helper_Administration();

$r = $router;
$submenu = array(
			'category'    => array('label' => 'categories',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'category'))),
			'post'    => array('label' => 'posts',
			                    'link'  => $r->genUrl('administration',
			                                          'content',
			                                          FALSE,
			                                          array('event' => 'post'))),
		);

Core_Response::factory()->sendHeaders();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . ENDL;
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head><title>Unit test</title>
<script type="text/javascript" src="../js/jquery.js"></script>
</head><body>';

$admin->submenu($submenu, 'post');
echo ENDL . '<br />';

$actions = array('add' => $r->forward(array('command'=>'add')), 'cron' => $r->forward(array('command'=>'cron')));
$admin->actions($actions);
echo ENDL . '<br />';

$admin->dump('user');

echo ENDL . '<br />';
$output = $admin->form('#', 'category', 0, TRUE);
echo clearOutput($output, TRUE);

echo ENDL;
?>
</body></html>
