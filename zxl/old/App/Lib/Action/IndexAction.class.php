<?php

class IndexAction extends BaseAction {    		
	
	public function __construct(){
		parent::__construct();
	}	
		
    public function index(){
		header("Location:/search");
    }

    public function apply(){
    	if(!$this->isAjax()){
    		$this->display('/Member/apply');
    		return false;
    	}
    	$data['companyname'] = $this->_post('companyname');
    	$data['contact1'] = $this->_post('contact1');
    	$data['contact1_phone'] = $this->_post('contact1_phone');
    	if($_POST['email'])
    		$data['email'] = $this->_post('email');
    	if($_POST['qq'])
    		$data['qq'] = $this->_post('qq');  

        $this->checkCaptcha();

    	$validate = array(
    		array('companyname','require','公司名称不能为空',1),
    		array('contact1','require','联系人不能为空',1),
    		array('contact1_phone','require','联系人电话不能为空',1),
    		array('contact1_phone','/^1\d{10}$|^0\d{2,3}-?\d{7,8}$/','联系人电话格式不正确'),
    		array('email','email','邮箱格式不正确'),
    	);

    	$_MEM = M('Member');
    	$_MEM->setProperty('_validate', $validate);
    	$result = $_MEM->create($data);

    	if(!$result){
    		$this->ajaxReturn(array(
    			'return' => 0,
    			'errmsg' => $_MEM->getError()
    		));
    	}

    	$data['status'] = 0;
    	$data['addtime'] = time();
    	$result = $_MEM->add($data);
    	if(!$result){
    		$this->ajaxReturn(array(
    			'return' => 0,
    			'errmsg' => '申请失败'
    		));
    	}
    	$this->ajaxReturn(array(
    		'return' => 1,
    		'msg' => '申请成功'
    	));
    }    
	
}
