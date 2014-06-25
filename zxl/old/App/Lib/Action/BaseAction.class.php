<?php

class BaseAction extends Action {

    private $_params = array();

    protected $_allowAction = array();

    private $_manageModule = array('manage');

    private $_specialAction = array('list');

    protected function _initialize()
    {
        //$this->checkAction();
        $this->_setParams();
    }

    public function _empty()
    {
        if(in_array(ACTION_NAME, $this->_specialAction)){
            $action = '_' . ACTION_NAME;
            $this->$action();
        }
        exit('Sorry');
    }

    protected function isAjax()
    {
        if(strtolower($this->getParams()['format']) == 'json')
            return true;
        return parent::isAjax();
    }

    protected function display($templateFile='',$charset='',$contentType='',$content='',$prefix='')
    {
        if($this->isAjax()){
            $vars = $this->view->get();
            $this->ajaxReturn($vars);
        }
        parent::display($templateFile='',$charset='',$contentType='',$content='',$prefix='');
    }

    protected function checkCaptcha($captcha=null)
    {
        $noCaptcha = C('NO_CAPTCHA');
        if(!empty($noCaptcha))
            return;

        if ($captcha === null)
            $captcha = $this->_post('captcha');

        $errmsg = '';
        if (empty($captcha))
            $errmsg = '验证码不能为空';
        if (md5(strtolower($captcha)) != $_SESSION['verify']) {
            $errmsg = '验证码错误';
        }

        if (!empty($errmsg)) {
            $res = array('return'=>0, 'errmsg'=>$errmsg);
            $this->ajaxReturn($res);
        }
    }

    protected function checkAction(array $allow=array())
    {
        $allow = array_merge($this->_allowAction, $allow);
        if(in_array(ACTION_NAME, $allow))
            return;

        if(in_array(strtolower(MODULE_NAME), $this->_manageModule) && empty($_SESSION['mid']))
            $this->redirect('Manage/login');

        if(empty($_SESSION['memberid']))
            $this->redirect('Member/login');
    }

    protected function isLogin()
    {
        return in_array(strtolower(MODULE_NAME), $this->_manageModule)
                ? !empty($_SESSION['mid']) : !empty($_SESSION['memberid']);
    }

    private function _setParams()
    {
       $params = array_merge(C('DEFAULT_PARAMS'), $_GET, $_POST);
       $this->_params = $params;
       return $params;
    }

    protected function getParams()
    {
        return $this->_params;
    }

    protected function page($count, $limit)
    {
        import("ORG.Util.Page");
        $Page = new Page($count, $limit);
        return $Page->getPage();
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
        }
        else
            $return['errmsg'] = $data;
        parent::ajaxReturn($return);
    }

}

