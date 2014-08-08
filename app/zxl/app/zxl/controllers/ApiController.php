<?php

use \Common\Func as Func;

class ApiController extends Controller
{

	private $params;

	private $noAuthAction = array('getauth', 'auth');

	public function initialize()
	{
		parent::initialize();
		$this->params = $this->getParams();
		$this->_check();
	}

	public function getAuthAction()
	{
		if(!$this->getAdminId())
			$this->response('无权限');				

		$companyid = 1;

		$auth = $this->session->get('auth');
		if(is_null($auth))
			$auth = array();

		if(array_key_exists($companyid, $auth))
			return $auth[$companyid];

		$newauth = Func\base64url_encode(date('YmdHis') . rand(10000, 99999) . $companyid);
	
		$auth[$companyid] = $newauth;
		$this->session->set('auth', $auth);

		return $newauth;
	}

	public function authAction()
	{
		$params = $this->getParams();
		if(in_array($params['auth'], $this->session->get('auth')))
			$this->response('校验成功');
		else
			$this->response('校验失败');
	}

	private function _check()
	{
		if(!in_array($this->getActionName(), $this->noAuthAction) 
			&& (empty($this->params['auth']) || !$this->session->__isset('auth')
				|| !in_array($this->params['auth'], $this->session->get('auth')) 
			   )
		)
			$this->response('auth错误');
	}

	private function response($msg)
	{
		exit($msg);
	}
}
