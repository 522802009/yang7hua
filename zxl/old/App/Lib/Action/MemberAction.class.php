<?php


class MemberAction extends BaseAction{

	private static $_MEM;
	private $_memberid = 1;

	protected $_allowAction = array('login');

	public function __construct(){
		if(!self::$_MEM instanceof Model){
			self::$_MEM = D('Member');
		}
		parent::__construct();
		isset($_SESSION['memberid']) && $this->_memberid = $_SESSION['memberid'];
	}

	public function index(){
		$data['info'] = $this->info();
		$data['finished_count'] = R('/Loan/getFinishedCount');
		$data['unfinished_count'] = R('/Loan/getUnfinishedCount');

		$this->assign('info', $data['info']);
		$this->assign('finished_count', $data['finished_count']);
		$this->assign('unfinished_count', $data['unfinished_count']);
		$this->display();
	}

	public function edit(){
		$action = $this->_post('action');
		if(empty($action) || !in_array($action, array('edit', 'add'))){
			$this->assign('info', $this->info());
			$this->display();
			return;
		}
		$data['address'] = $this->_post('address');
		$data['contact1'] = $this->_post('contact1');
		$data['contact1_phone'] = $this->_post('contact1_phone');

		if(!empty($_POST['contact2']))
			$data['contact2'] = $this->_post('contact2');
		if(!empty($_POST['contact2_phone']))
			$data['contact2_phone'] = $this->_post('contact2_phone');

		$result = self::$_MEM->create($data);
		if(!$result)
			$this->ajaxReturn(self::$_MEM->getError(), false);

		if($action == 'edit'){
			$memberid = $this->_memberid;
			if(empty($memberid)){
				$this->ajaxReturn('参数错误', false);
			}
			$result = self::$_MEM->where("memberid=$memberid")->save($data);
		}

		if($result !== false)
			$this->ajaxReturn('操作成功！');
		else
			$this->ajaxReturn('操作失败！', false);

	}

	public function login(){
		if(!$this->isAjax()){
			$this->display("Public:login");
			return;
        }

        $this->checkCaptcha();

		$data['username'] = $this->_post('username');
		$data['password'] = $this->_post('password');

		$validate = array(
			array('username','require','登录账号不能为空',1),
			array('password','require','登录密码不能为空',1),
		);
		self::$_MEM->setProperty('_validate', $validate);
		$result = self::$_MEM->create($data);
		if(!$result)
			$this->ajaxReturn(self::$_MEM->getError(), false);


		$data['password'] = password($data['password']);

		$row = self::$_MEM->where($data)->find();

		if(!$row)
			$this->ajaxReturn('用户名或密码错误', false);

		if($row['status'] != 1)
			$this->ajaxReturn('您的账号未通过审核', false);


		$_SESSION['memberid'] = $row['memberid'];
		$_SESSION['username'] = $row['username'];
		$this->ajaxReturn('登录成功');
	}

	public function logout(){
		unset($_SESSION['memberid'], $_SESSION['username']);
		header('Location:/member/login');
	}
	public function info(){
		$memberid = $this->_memberid;
		return self::$_MEM->where("memberid=$memberid")->field('password', true)->find();
	}

	public function users(){
		if(!$this->isAjax()){
			$this->assign('info', $this->info());
			$this->display();
			return;
		}
		$M_USERS = M('User');
		$params = $this->getParams();

		$where['memberid'] = $this->_memberid;
		$res = $M_USERS->where($where)->page($params['p'], $params['limit'])->select();

		$count = $M_USERS->where($where)->count();
		$page = $this->page($count, $params['limit']);

		$this->ajaxReturn(array('list'=>$res, 'page'=>$page));
	}

	public function deluser(){
		$uid = $_POST['uid'];
		if(!$uid)
			$this->ajaxReturn('参数错误');

		$uid = intval($uid);
		$memberid = $this->_memberid;

		$where = "uid=$uid and memberid=$memberid";

		$M_LOAN = M('Loan');
		$M_LOAN->where($where)->delete();

		$M_USER = M('User');
		$result = $M_USER->where($where)->delete();

		$this->ajaxReturn('操作成功');
	}

	public function saveuser(){
		$memberid = $this->_memberid;
		$M_USER = M('User');

		$uid = intval($this->_post('uid'));
		$data['idcard'] = $this->_post('idcard');

		$row = $M_USER->where("memberid=$memberid and idcard='{$data['idcard']}' and uid!=$uid")->find();

		if($row)
			$this->ajaxReturn('该身份证已存在！', false);

		$data['realname'] = $this->_post('realname');
		$data['memberid'] = $memberid;


		$result = $M_USER->create($data);

		if(!$result)
			$this->ajaxReturn(self::$_USER->getError());

		$M_USER->where('uid=%d', $uid)->save($data);

		$this->ajaxReturn('操作成功！');

	}

}
