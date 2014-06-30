<?php

class MethodsController extends Controller
{

	public function emptyAction()
	{
		if(!APP_DEBUG)
			return;
		$class = null;
		switch($this->getActionName()){
			case 'di':
				global $di;
				$class = $di;
				break;
			case 'dispatcher':
				global $di;
				$class = $di->get('dispatcher');
				break;
			case 'controller':
				$class = '\Phf\Mvc\Controller';
				break;
			case 'view':
				$class = '\Phf\Mvc\View';
				break;
			case 'model':
				$class = '\Phf\Mvc\Model';
				break;
			case 'db':
				$class = '\Phf\Db\Adapter\Pdo\Mysql';
				break;
			case 'url':
				$class = '\Phf\Mvc\Url';
				break;
			case 'router':
				$class = '\Phf\Mvc\Router';
				break;
			case 'metadata':
				$class = '\Phf\Mvc\Model\MetaData';
				break;
			case 'session':
				$class = '\Phf\Session\Adapter\Files';
				break;
			case 'volt':
				$class = '\Phf\Mvc\View\Engine\Volt';
				break;
			case 'response':
				$class = '\Phf\Http\Response';
				break;
				break;
			case 'gd':
				$class = '\Phf\Image\Adapter\GD';
				break;
			case 'image':
				$class = '\Phf\Image';
				break;
			case 'imagick':
				$class = '\Phf\Image\Adapter\Imagick';
				break;
			default:
				$class = ucwords($this->getActionName()) . 'Controller';
				if(!class_exists($class))
					$calss = __CLASS__;
				break;
		}
		$reflectionClass = new ReflectionClass($class);
		var_dump($reflectionClass->getMethods());
	}

}
