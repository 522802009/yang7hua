<?php

!defined('APP_NAME') && defined('APP_NAME', 'zxl');
define('APP_PATH', __DIR__ . '/' . APP_NAME . '/');
define('CONF_PATH', APP_PATH . 'config/');

try{
$config = new Phf\Config\Adapter\Ini(CONF_PATH . 'config.ini');

define('LIB_PATH', APP_PATH . trim($config->application->libraryDir, '/') . '/');

$loader = new Phf\Loader();
$loader->registerDirs(
			array(
				APP_PATH . $config->application->controllersDir,
				APP_PATH . $config->application->modelsDir,
				APP_PATH . $config->application->libraryDir
			)
		)
		->registerNamespaces(
			array(
				'Util'	=>	LIB_PATH
			)
		)
		->register();

$di = new Phf\DI\FactoryDefault();
$di->set('view', function() use ($config){
			$view = new Phf\Mvc\View();
			$view->setViewsDir($config->application->viewsDir . $config->public->defaultTheme . '/');
			return $view; });
$di->set('url', function(){
			$url = new Phf\Mvc\Url();
			$url->setBaseUri('/');
			return $url;
		});
$di->set('db', function() use ($config){
			return new Phf\Db\Adapter\Pdo\Mysql(array(
				'host'	=>	$config->database->host,
				'username'	=>	$config->database->username,
				'password'	=>	$config->database->password,
				'dbname'	=>	$config->database->dbname,
				'charset'	=>	$config->database->charset
			));	
		});
$di->setShared('session', function(){
			$session = new Phf\Session\Adapter\Files();
			$session->start();
			return $session;
		});

function limit($p=1, $limit=10)
{
	return ($p-1)*$limit . ',' . $limit;
}
function password($password, $salt='zxl')
{
	return md5(md5($password . $salt));
}

$app = new Phf\Mvc\Application($di);
echo $app->handle()->getContent();
}catch(Exception $e){
	echo $e->getMessage();
}
