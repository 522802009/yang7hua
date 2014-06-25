<?php

class RecordAction extends BaseAction{

    private static $reason = array(1=>'贷款审批', 2=>'担保资格审批');

    public function __construct()
    {
        parent::__construct();
    }

    public static function getReason($reason_key = false)
    {
        if($reason_key !== false && array_key_exists($reason_key, static::$reason)){
            return static::$reason[$reason_key];
        }
        return static::$reason;
    }

    public function add(array $data=array())
    {
        $M_RECORD = M('Record');
        $data['addtime'] = time();
        $validate = array(
            array('member_id','require','会员账号不能为空',1),
            array('reason','require','查询原因不能为空',1),
            array('realname','require','查询姓名不能为空',1),
            array('idcard','require','查询身份证不能为空',1),
            array('idcard','/^[\dxX]{16,18}$/','身份证号错误',0)
        );
        $M_RECORD->setProperty('_validate', $validate);
        $result = $M_RECORD->create($data);

        if(!$result)
            return $M_RECORD->getError();

        $result = $M_RECORD->add($data);
        if($result)
            return true;
        else
            return false;
    }

    private function _getList(array $where = array())
    {
        $params = $this->getParams();

        $M_RECORD = M('Record');
        $list = $M_RECORD->where($where)->page($params['p'], $params['limit'])->select();
        $count = $M_RECORD->where($where)->count();
        $page = $this->page($count, $params['limit']);

        return array(
            'list' => $list,
            'page' => $page
        );
    }

    public function _list()
    {
        print_r($this->_getList());
    }

    public function getRecordByCardnumber($card_number = '')
    {
        if(empty($card_number) || !preg_match('/^[\w]+$/', $card_number))
            return false;
        return $this->_getList(array('card_number'=>$card_number));
    }

    public function index()
    {
        print_r($this->getRecordByCardnumber('420528198709081396'));
    }

}
