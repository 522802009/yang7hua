<?php


class ManageAction extends BaseAction{

	private static $_A;

	protected $_allowAction = array('login');

	public function __construct(){
		if(!self::$_A instanceof Model){
			self::$_A = D('Admin');
		}

		parent::__construct();
	}

	public function login(){
		if(!$this->isAjax()){
			if($this->isLogin()){
				header('Location:/manage');
			}
			$this->display();
			return;
        }

        $this->checkCaptcha();

		$data['username'] = $this->_post('username');
		$data['password'] = $this->_post('password');

		$result = self::$_A->create($data);
		if(!$result){
			$this->ajaxReturn(self::$_A->getError(), false);
		}

        $data['password'] = password($data['password']);

		$row = self::$_A->where($data)->find();
		if(!$row){
			$this->ajaxReturn('账号或密码错误', false);
		}

		$_SESSION['mid'] = $row['mid'];
		$_SESSION['mname'] = $row['username'];
		$this->ajaxReturn('登录成功');
	}

	public function logout(){
		unset($_SESSION['mid'], $_SESSION['mname']);
		header('Location:/manage');
	}

	public function index(){
		$this->display("member");
	}

	public function members(){
		$params = $this->getParams();
		$M_MEMBER = M('Member');
		$res = $M_MEMBER->page($params['p'], $params['limit'])->field('password', true)->select();

		$count = $M_MEMBER->count();
		$page = $this->page($count, $params['limit']);

		$this->ajaxReturn(array(
			'list' => $res,
			'page' => $page
		));
	}


	public function addmember(){
		if(!$this->isAjax()){
			$this->display();
			return false;
		}

		$data['username'] = $this->_post('username');
		$data['password'] = $this->_post('password');
		$data['repassword'] = $this->_post('repassword');
		$data['companyname'] = $this->_post('companyname');

		$validate = array(
			array('username','require','登录账号不能为空',1),
			array('username','','账号已存在',0,'unique'),
			array('password','require','登录密码不能为空',1),
			array('repassword','require','确认密码不能为空',1),
			array('repassword','password','两次密码不一致',0,'confirm'),
			array('companyname','require','公司名称不能为空',1),
		);
		$_MEM = M('Member');
		$_MEM->setProperty('_validate', $validate);
		$result = $_MEM->create($data);

		if(!$result){
			$this->ajaxReturn($_MEM->getError(), false);
		}

		$data['password'] = password($data['password']);
		$data['addtime'] = time();

		$result = $_MEM->add($data);
		if(!$result){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}

	public function delmember(){
		$memberid = intval($this->_post('memberid'));
		if(!$memberid){
			$this->ajaxReturn('参数错误', false);
		}
		$_MEM = M('Member');
		$result = $_MEM->where("memberid=$memberid")->delete();
		if(!$result){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}

	public function editmember(){
		$memberid = intval($_POST['memberid']);
		if(!$memberid){
			$this->ajaxReturn('参数错误', false);
		}
		$data['username'] = $this->_post('username');
		$data['companyname'] = $this->_post('companyname');

		$_MEM = M('Member');

		if($_POST['status']){
			$data['status'] = intval($_POST['status']);

			//如果是通过审核，必须验证账号和密码
			if($data['status'] == 1){
				$validate = array(
					array('username','require','登录账号不能为空',1),
					array('companyname','require','公司名称不能为空',1)
				);
				$_MEM->setProperty('_validate', $validate);
				$result = $_MEM->create($data);
				if(!$result){
					$this->ajaxReturn($_MEM->getError(), false);
				}
				if($_MEM->where(array('username'=>$data['username'],'memberid'=>array('neq',$memberid)))->find()){
					$this->ajaxReturn('该账号已存在', false);
				}
			}
		}
		if(isset($_POST['password'])){
			$data['password'] = password($_POST['password']);
		}

		$result = $_MEM->where("memberid=$memberid")->save($data);

		if($_MEM->getDbError()){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}


	public function admin(){
		if(!$this->isAjax()){
			$this->display();
			return false;
		}
		$params = $this->getParams();

		$M_ADMIN = M('Admin');
		$res = $M_ADMIN->page($params['p'], $params['limit'])->select();

		$count = $M_ADMIN->count();
		$page = $this->page($count, $params['limit']);

		$this->ajaxReturn(array(
			'list' => $res,
			'page' => $page
		));
	}

	public function addadmin(){
		if(!$this->isAjax()){
			$this->display();
			return;
		}

		$data['username'] = $this->_post('username');
		$data['password'] = $this->_post('password');
		$data['repassword'] = $this->_post('repassword');

		$validate = array(
			array('username','require','账号不能为空',1),
			array('username','/^[^\s]{5,10}$/','账号格式不正确',0,'regex'),
			array('password','require','密码不能为空',1),
			array('repassword','require','确认密码不能为空',1),
			array('repassword','password','两次密码不一致',0,'confirm')
		);
		self::$_A->setProperty('_validate', $validate);
		$result = self::$_A->create($data);
		if(!$result){
			$this->ajaxReturn(self::$_A->getError(), false);
		}

		unset($data['repassword']);
		$data['password'] = password($data['password']);

		$result = self::$_A->add($data);
		if(!$result){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}

	public function deladmin(){
		$mid = intval($_POST['mid']);
		if(!$mid || $mid === 1){
			$this->ajaxReturn('参数错误', false);
		}
		$result = self::$_A->where("mid=$mid")->delete();
		if(!$result){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}

	public function editadmin(){
		$mid = intval($_POST['mid']);
		$data['username'] = trim($this->_post('username'));

		if($mid === 1 && $_SESSION['mid'] != 1){
			$this->ajaxReturn('无权限修改', false);
		}

		if($data['username'] == ''){
			$this->ajaxReturn('账号不能为空', false);
		}

		if(isset($_POST['password'])){
			$data['password'] = password($_POST['password']);
		}

		$result = self::$_A->where("mid=$mid")->save($data);
		if(self::$_A->getDbError()){
			$this->ajaxReturn('操作失败', false);
		}
		$this->ajaxReturn('操作成功');
	}

}
