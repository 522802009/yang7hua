<?php

use \Util\Page;

class Controller extends \Phf\Mvc\Controller
{

	private $_params;
	
	protected $_allowAction = array();

	private $_controllerName;

	private $_actionName;

	protected function initialize()
	{
		$this->_check();
		$this->_setParams();
	}

	private function _check()
	{
		if(!in_array($this->getActionName(), $this->_allowAction) && !$this->isLogin())
			$this->ajaxReturn('请先登陆', false);
	}

	protected function getControllerName()
	{
		global $router;
		if(!isset($this->_controllerName))
			$this->_controllerName = $router->getControllerName();
		return $router->getControllerName();
	}

	protected function getActionName()
	{
		global $router;
		if(!isset($this->_actionName))
			$this->_actionName = $router->getActionName();
		return $this->_actionName;
	}

	private function _setParams()
	{
		global $config;
		$page	= (array) $config->pagination;
		$params = array_merge($page, $this->request->get());
		foreach($params as &$param){
			$param = htmlspecialchars($param);
		}
		$this->_params = $params;
	}

	protected function getParams()
	{
		return $this->_params;
	}

	protected function getMemberId()
	{
		return $this->session->get('member_id');
	}

	protected function isLogin()
	{
		return $this->session->get('member_id') != null;
	}

	protected function page($count, $limit)
	{
		$page = new Page($count, $limit);
		return $page->getPage();
	}

	protected function isAjax()
	{
		if($this->request->isAjax() || $this->request->get('format') == 'json')
			return true;
		return false;
	}

	public function ajaxReturn($data, $success=true)
	{
		$return = array();
		$return['ret'] = $success ? 1 : 0;
		if($success){
			if(is_string($data))
				$return['msg'] = $data;
			else if(is_array($data))
				$return['data'] = $data;
		}else{
			$return['errmsg'] = $data;
		}
		exit(json_encode($return));
	}

	protected function display($renderView = '')
	{
		if($this->isAjax()){
			$this->ajaxReturn($this->view->getParamsToView());
		}
		//var_dump(getMethodsOfClass('\Phf\Mvc\View'));
		//$this->view->disable();
		if(empty($renderView)){
			$controllerDir = $this->getControllerName();
			$actionFile = $this->getActionName();
		}else if(strpos($renderView, '/') !== false){
			list($controllerDir, $actionFile) = explode('/', $renderView);
		}else{
			$actionFile = $renderView;
			$controllerDir = $this->getControllerName();
		}
		if(!is_dir($this->view->getViewsDir() . $controllerDir))
			throw new Exception('Tpl path: ' . $controllerDir . ' is not exists!');
		if(!file_exists($this->view->getViewsDir() . $controllerDir . '/' . $actionFile . '.phtml'))
			throw new Exception('Tpl file: ' . $controllerDir . '/' . $actionFile . '.phtml is not exists!');

		$this->view->pick($controllerDir . '/' . $actionFile);
	}

}