<?php
namespace app\api\controller;
use think\Db;
use think\Request;
use app\common\lib\Token;
use app\common\lib\Helper;
use app\common\lib\ReturnData;
use app\common\logic\EmailVerifyCodeLogic;

class EmailVerifyCode extends Base
{
	public function _initialize()
	{
		parent::_initialize();
    }
    
    public function getLogic()
    {
        return new EmailVerifyCodeLogic();
    }
    
    //列表
    public function index()
	{
        //参数
        $where = array();
        $limit = input('limit',10);
        $offset = input('offset', 0);
        $orderby = input('orderby','id desc');
        
        $res = $this->getLogic()->getList($where,$orderby,'*',$offset,$limit);
		
		exit(json_encode(ReturnData::create(ReturnData::SUCCESS,$res)));
    }
    
    //详情
    public function detail()
	{
        //参数
        if(input('id', '') !== ''){$where['id'] = input('id');}
        if(!isset($where)){exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));}
        
		$res = $this->getLogic()->getOne($where);
        if(!$res){exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));}
        
		exit(json_encode(ReturnData::create(ReturnData::SUCCESS,$res)));
    }
    
    //添加
    public function add()
    {
        if(Helper::isPostRequest())
        {
            $res = $this->getLogic()->add($_POST);
            
            exit(json_encode($res));
        }
    }
    
    //修改
    public function edit()
    {
        if(input('id',null)!=null){$id = input('id');}else{$id='';}if(preg_match('/[0-9]*/',$id)){}else{exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));}
        
        if(Helper::isPostRequest())
        {
            unset($_POST['id']);
            $where['id'] = $id;
            
            $res = $this->getLogic()->edit($_POST,$where);
            
            exit(json_encode($res));
        }
    }
    
    //删除
    public function del()
    {
        if(input('id',null)!=null){$id = input('id');}else{$id='';}if(preg_match('/[0-9]*/',$id)){}else{exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));}
        
        if(Helper::isPostRequest())
        {
            unset($_POST['id']);
            $where['id'] = $id;
            
            $res = $this->getLogic()->del($where);
            
            exit(json_encode($res));
        }
    }
    
    /**
     * 获取邮箱验证码
     * @param $email 邮箱
     * @param $captcha 验证码
     * @return string 成功失败信息
     */
    public function getEmailCode()
    {
        $res = $this->getLogic()->getEmailCode($_REQUEST);
        if ($res['code'] == ReturnData::SUCCESS)
        {
            exit(json_encode(ReturnData::create(ReturnData::SUCCESS, $res['data'])));
        }
        
        exit(json_encode(ReturnData::create(ReturnData::FAIL, null, $res['msg'])));
    }
    
    //邮箱验证码校验
    public function check()
	{
		$res = $this->getLogic()->check($_REQUEST);
        if ($res['code'] == ReturnData::SUCCESS)
        {
            exit(json_encode(ReturnData::create(ReturnData::SUCCESS)));
        }
        
        exit(json_encode(ReturnData::create(ReturnData::FAIL, null, $res['msg'])));
    }
    
}