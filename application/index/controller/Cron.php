<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Exception;

class Cron extends Controller
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';


	public function get_project()
	{
		try{
		$id = Db::name('project')->order('id desc')->field('sgb_id')->find();
		$id = empty($id)?0:$id['sgb_id'];
		//初始化
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, 'http://dev.e-shigong.com/serverProjectList?companyId=44&start='.$id);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 1);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER,false);
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		$data = json_decode($data,true);
		if(empty($data['data'])){
			return false;
		}
		foreach ($data['data'] as $key => $val){
			$city['name'] ='';
			if(!empty($val['city'])){
				$city = Db::name('area')->where(['code'=>$val['city']])->find();
			}
			$add[] = [
				'sgb_id'=>$val['id'],
				'name'=>$val['name'],
				'city'=>$val['city'],
				'city_name'=>$city['name'],
				'start_time'=>date("Y-m-d H:i:s",$val['start_time']),
				'end_time'=> date("Y-m-d H:i:s",$val['end_time']),
				'circle'=> json_encode($val['circle']),
				'task'=> json_encode($val['task']),
				'image'=>json_encode($val['images_list']),
//				'status'=>$val['id'],
//				'type'=>$val['id'],
//				'area'=>$val['id'],
				'look_num'=>rand(1,100),
				'project_time'=>$val['create_time'],
				'create_time'=>date("Y-m-d H:i:s",time()),
				'update_time'=>date("Y-m-d H:i:s",time()),
			];
		}

		if(!empty($add)){
			Db::name('project')->insertAll($add);
		}
		}catch (Exception $e){
			print_r($e);
		}
    }


	public function update_circle()
	{
		$list = Db::name('project')->order(['update_time asc'])->limit(20)->select();
		foreach ($list as $key => $val){
			$flag = false;
			$circle_list = json_decode($val['circle'],true);
			foreach ($circle_list as $k=>$v){
				if(time()>=$v['start_time'] && time()<=$v['end_time']){
					$flag = true;
					Db::name('project')->where(['id'=>$val['id']])->update(['circle_name'=>$v['name'],'update_time'=>date("Y-m-d H:i:s",time())]);
					break;
				}
			}
			if(!$flag){
				Db::name('project')->where(['id'=>$val['id']])->update(['update_time'=>date("Y-m-d H:i:s",time())]);
			}
		}
    }


	public function get_circle()
	{
		try{
			//初始化
			$curl = curl_init();
			//设置抓取的url
			curl_setopt($curl, CURLOPT_URL, 'http://dev.e-shigong.com/serverProjectCircle?companyId=44');
			//设置头文件的信息作为数据流输出
			curl_setopt($curl, CURLOPT_HEADER, 1);
			//设置获取的信息以文件流的形式返回，而不是直接输出。
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER,false);
			//执行命令
			$data = curl_exec($curl);
			//关闭URL请求
			curl_close($curl);
			//显示获得的数据
			$data = json_decode($data,true);
			if(empty($data['data'])){
				return false;
			}
			foreach ($data['data'] as $key => $val){
				$add[] = [
					'sgb_id'=>$val['id'],
					'name'=>$val['name'],
					'create_time'=>date("Y-m-d H:i:s",time()),
					'update_time'=>date("Y-m-d H:i:s",time()),
				];
			}

			if(!empty($add)){
				Db::name('project_circle')->insertAll($add);
			}
		}catch (Exception $e){
			print_r($e);
		}
    }




}
