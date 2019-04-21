<?php

namespace app\index\controller;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Cron extends Controller
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';


	public function get_project()
	{
		//Db::name('project')->where('id','gt',0)->delete();die;
		try{
		$id = Db::name('project')->order('id desc')->field('sgb_id')->find();
		$id = empty($id)?0:$id['sgb_id'];
		echo '开始id'.$id.'</br>';
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
			echo '暂无数据';
			die;
		}

		foreach ($data['data'] as $key => $val){
			$city['name'] ='';
			if(!empty($val['city'])){
				$city = Db::name('area')->where(['code'=>$val['city']])->find();
			}
			echo '同步施公宝工地['.$val['name'].$val['circle_name'].']</br>';
			$arr = [
				'sgb_id'=>$val['id'],
				'name'=>$val['name'],
				'city'=>$val['city'],
				'city_name'=>$city['name'],
				'start_time'=>date("Y-m-d H:i:s",$val['start_time']),
				'end_time'=> date("Y-m-d H:i:s",$val['end_time']),
				'circle'=> json_encode($val['circle']),
				'task'=> json_encode($val['task']),
				'image'=>json_encode($val['images_list']),
				'circle_name'=>$val['circle_name'],
				'circle_id'=>$val['circle_id'],
//				'type'=>$val['id'],
//				'area'=>$val['id'],
				'look_num'=>rand(1,100),
				'project_time'=>$val['create_time'],
				'create_time'=>date("Y-m-d H:i:s",time()),
				'update_time'=>date("Y-m-d H:i:s",time()),
			];
			$add[] = $arr;
		}

		if(!empty($add)){
			Db::name('project')->insertAll($add);
		}
		}catch (Exception $e){
			print_r($e);
		}
    }


	public function update_circle(Request $request)
	{
		$param = $request->param();
		$where = [];
		if(isset($param['type'])){
			$where['circle_id'] = ['gt',0];
		}
		$list = Db::name('project')->where($where)->order(['update_time asc'])->limit(20)->select();
		foreach ($list as $key => $val){
			$flag = false;
			$circle_list = json_decode($val['circle'],true);
			foreach ($circle_list as $k=>$v){
				if(time()>=$v['start_time'] && time()<=$v['end_time']){
					$flag = true;
					echo '项目更新 名称['.$val['name'].'] -阶段'.$val['circle_name'].'</br>';
					Db::name('project')->where(['id'=>$val['id']])->update(['circle_name'=>$v['name'],'circle_id'=>$v['id'],'update_time'=>date("Y-m-d H:i:s",time())]);
					break;
				}
			}

			$update = ['update_time'=>date("Y-m-d H:i:s",time())];
			if(empty($val['circle_name'] )){
				$update['circle_name'] = $circle_list[0]['name'];
				$update['circle_id'] = $circle_list[0]['start_time'];
			}
			if(!$flag){
				echo '项目未更新 名称['.$val['name'].'] -阶段'.$val['circle_name'].'</br>';
				Db::name('project')->where(['id'=>$val['id']])->update($update);
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





	public function read_all ($dir,&$file){
		if(!is_dir($dir)) return false;

		$handle = opendir($dir);

		if($handle){
			while(($fl = readdir($handle)) !== false){
				$temp = $dir.DIRECTORY_SEPARATOR.$fl;
				//如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
				if(is_dir($temp) && $fl!='.' && $fl != '..'){
					//self::read_all($temp,$file);
				}else{
					if($fl!='.' && $fl != '..'){
						//echo '文件：'.$temp.'<br>';
						$a = fileatime($temp);
						//echo (time()-$a).'<br/>';
						$arr = explode("/public/",$temp);
						$file[] = $arr[1];
					}
				}
			}
		}
	}

	public function upload_assets()
	{
		$this->read_all(ASSETS_PATH.'/assets/img',$file);
		//$this->error("当前插件暂无前台页面");
		$config = get_addon_config('qiniu');
		$auth = new Auth($config['app_key'], $config['secret_key']);
		$token = $auth->uploadToken('lyzs');
		$uploadMgr = new UploadManager();
		foreach ($file as $key => $val){
			if($key%100==1){
				echo $key;
				//sleep(2);
			}
			echo $val.'</br>';
			$uploadMgr->putFile($token,$val, './'.$val);
		}

	}




}
