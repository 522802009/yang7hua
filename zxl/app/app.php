<?php

$controllerName = $router->getControllerName();
$actionName = $router->getActionName();

$controller = ucwords($controllerName) . 'Controller';

if(!class_exists($controller)) 
	throw new Exception('Controller not exists!');

$reflectionController = new ReflectionClass($controller);
if(!$reflectionController->hasMethod($actionName . 'Action')){

	$dispatcher = $di->get('dispatcher');
	$dispatcher->setControllerName($controllerName);
	$dispatcher->setActionName('empty');
	$dispatcher->setParams($router->getParams());

	$view = $di->get('view');
	$view->start();

	$dispatcher->dispatch();

	$view->render(
			$dispatcher->getControllerName(),
			$dispatcher->getActionName(),
			$dispatcher->getParams()
			);
	$view->render($controllerName, $actionName); 
	$view->finish();

	$response = $di['response'];
	$response->setContent($view->getContent());
	$response->sendHeaders();
	echo $response->getContent();
	exit();
}


set_include_path(get_include_path() . PATH_SEPARATOR . APP_PATH . $config->application->commonDir);
include 'functions.php';
