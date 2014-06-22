<?php

use \Util\Page;

class Controller extends \Phf\Mvc\Controller{
	private $_params;

	protected function initialize()
	{
		$this->_setParams();
		//print_r($this->page(100,3));
		$this->session->set('member_id', 1);
	}

	private function _setParams()
	{
		global $config;
		$page	= (array) $config->pagination;
		$this->_params = array_merge($page, $this->request->get());
	}

	protected function getParams()
	{
		return $this->_params;
	}

	protected function getMemberId()
	{
		return $this->session->get('member_id');
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

	protected function ajaxReturn($data, $success=true)
	{
		$return = array();
		$return['return'] = $success ? 1 : 0;
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

}
