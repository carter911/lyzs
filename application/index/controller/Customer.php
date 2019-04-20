<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\UserIp;
use think\Config;
use think\Cookie;
use think\Db;
use think\Exception;
use think\Hook;
use think\Request;
use think\Session;
use think\Validate;

/**
 * 客户资讯
 */
class Customer extends Frontend
{


	protected $noNeedLogin = '*';
	protected $noNeedRight = '*';
	protected $layout = '';

	private $userIp;


	public function _initialize()
	{
		parent::_initialize();

		$this->userIp = new UserIp();
	}

	/**
	 * 提交客户信息
	 * @param Request $request
	 * @return array
	 */
	public function store(Request $request)
	{
	   try{
			$param = $request->only(['name','mobile','door','area','toilet','type']);

			if(!isset($param['name']) || empty($param['name'])){
				return ['code'=>3001,'msg'=>'请输入您的姓名','data'=>null];
			}
			if(!isset($param['mobile']) || $param['mobile'] == ""){
				return ['code'=>3001,'msg'=>'请输入您的手机号码','data'=>null];
			}
			$param['createtime'] = time();
			$param['updatetime'] = time();

			$customer = Db::name('customer')->where(['mobile'=>$param['mobile'],'status'=>1])->find();
			if($customer){
				return ['code'=>200,'msg'=>'您的资料已经提交 请勿重复提交','data'=>null];
			}
			$result = Db::name('customer')->insert($param);
			if(!is_numeric($result)){
				return ['code'=>200,'msg'=>'系统异常 请稍后重试','data'=>null];
			}

			//添加设计师预约
            $paramTeam = $request -> only(["detail_id"]);
			$this->addTeamId($paramTeam["detail_id"]);

			return ['code'=>200,'msg'=>'恭喜您提交成功','data'=>null];

		}catch (Exception $e){
			return ['code'=>500,'msg'=>'系统异常 请稍后重试','data'=>$e];
		}
	}


    private function addTeamId($teamId) {
        $data["team_id"] = $teamId;
        $data["ip"] = \EasyWeChat\Payment\get_client_ip();

        try{
            $this->userIp->save($data);
        }catch (Exception $e){}
    }

}
