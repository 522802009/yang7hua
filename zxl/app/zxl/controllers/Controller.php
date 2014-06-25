<?php

use \Util\Page;

class Controller extends \Phf\Mvc\Controller
{

	//$_REQUEST;
	private $_params;
	
	private $_allowController = array('methods');

	protected $_allowAction = array();

	private $_controllerName;

	private $_actionName;

	private $_tplparse = array();

	private $_globalVars = array();

	protected function initialize()
	{
		global $view;
		global $config;
		if(isset($view))
			$this->view = $view;
		$this->_check();
		$this->_setParams();
		if(isset($config->tplparse))
			$this->_tplparse = $config->tplparse->toArray();
		$this->_setGlobalVars();
	}
	
	/**
	  called when action is not declared;
	  global $view
	*/
	public function emptyAction()
	{
		global $view;
		if($this->isAjax()){
			$this->display();
		}
	}

	private function _setGlobalVars()
	{
		global $config;
		$globalVars = array();
		$this->_globalVars = array_merge($globalVars, $this->_tplparse);
		$this->view->setVars(
					$this->_globalVars
				);
	}

	private function _check()
	{
		if(in_array($this->getControllerName(), $this->_allowController))
			return;
		if(!in_array($this->getActionName(), $this->_allowAction) && !$this->isLogin()){
			if($this->isAjax())
				$this->ajaxReturn('请先登陆', false);
			$redirect = $this->getControllerName() == 'manage' ? 'manage/login' : 'member/login';
			$this->response->redirect($redirect)->sendHeaders();
		}
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

	protected function getAdminId()
	{
		return $this->session->get('mid');
	}

	protected function isLogin()
	{
		if($this->getControllerName() == 'manage')
			return $this->session->get('mid') != null;
		else
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
			$return['msg'] = $data;
		}
		exit(json_encode($return));
	}

	protected function display($renderView = '')
	{
		if($this->isAjax()){
			$this->ajaxReturn($this->view->getParamsToView());
		}
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
		//if(!file_exists($this->view->getViewsDir() . $controllerDir . '/' . $actionFile . '.html'))
		//	throw new Exception('Tpl file: ' . $controllerDir . '/' . $actionFile . '.html is not exists!');

		$this->view->pick($controllerDir . '/' . $actionFile);
	}

	protected function redirect($redirect)
	{
		$this->response->redirect($redirect)->sendHeaders();
	}

}
