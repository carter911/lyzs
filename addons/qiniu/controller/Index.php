<?php

namespace addons\qiniu\controller;

use addons\qiniu\library\Auth;
use app\common\model\Attachment;
use Qiniu\Storage\UploadManager;
use think\addons\Controller;

/**
 * 七牛管理
 *
 */
class Index extends Controller
{

	public function read_all ($dir,&$file){
		if(!is_dir($dir)) return false;

		$handle = opendir($dir);

		if($handle){
			while(($fl = readdir($handle)) !== false){
				$temp = $dir.DIRECTORY_SEPARATOR.$fl;
				//如果不加  $fl!='.' && $fl != '..'  则会造成把$dir的父级目录也读取出来
				if(is_dir($temp) && $fl!='.' && $fl != '..'){
					self::read_all($temp,$file);
				}else{
					if($fl!='.' && $fl != '..'){
						//echo '文件：'.$temp.'<br>';
						$a = fileatime($temp);
						$arr = explode("/public/",$temp);
						$file[] = $arr[1];
					}
				}
			}
		}
	}

    public function index()
    {
    	$this->read_all(ASSETS_PATH.'/assets',$file);
        //$this->error("当前插件暂无前台页面");
		$config = get_addon_config('qiniu');
		$auth = new Auth($config['app_key'], $config['secret_key']);
		$token = $auth->uploadToken('lyzs');
		$uploadMgr = new UploadManager();

		foreach ($file as $key => $val){
			if($key>2){
				continue;
			}
			$uploadMgr->putFile($token, $key, $key);
		}

	}

    public function notify()
    {
        $config = get_addon_config('qiniu');
        $auth = new Auth($config['app_key'], $config['secret_key']);
        $contentType = 'application/x-www-form-urlencoded';
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        if (!$authorization && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $authorization = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        }

        $url = $config['notifyurl'];
        $body = file_get_contents('php://input');
        $ret = $auth->verifyCallback($contentType, $authorization, $url, $body);
        if ($ret) {
            parse_str($body, $arr);
            $admin_id = isset($arr['admin']) ? $arr['admin'] : 0;
            $user_id = isset($arr['user']) ? $arr['user'] : 0;
            $imageInfo = json_decode($arr['imageInfo'], TRUE);
            $params = array(
                'admin_id'    => (int)$admin_id,
                'user_id'     => (int)$user_id,
                'filesize'    => $arr['filesize'],
                'imagewidth'  => isset($imageInfo['width']) ? $imageInfo['width'] : 0,
                'imageheight' => isset($imageInfo['height']) ? $imageInfo['height'] : 0,
                'imagetype'   => isset($imageInfo['format']) ? $imageInfo['format'] : '',
                'imageframes' => 1,
                'mimetype'    => "image/" . (isset($imageInfo['format']) ? $imageInfo['format'] : ''),
                'extparam'    => '',
                'url'         => '/' . $arr['key'],
                'uploadtime'  => time(),
                'storage'     => 'qiniu'
            );
            Attachment::create($params);
            return json(['ret' => 'success', 'code' => 1, 'data' => ['url' => $params['url']]]);
        }
        return json(['ret' => 'failed']);
    }

}
