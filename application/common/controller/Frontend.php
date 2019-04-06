<?php

namespace app\common\controller;

use app\common\library\Auth;
use app\common\model\Category;
use think\Config;
use think\Controller;
use think\Db;
use think\Hook;
use think\Lang;

/**
 * 前台控制器基类
 */
class Frontend extends Controller
{

	/**
	 * 布局模板
	 * @var string
	 */
	protected $layout = '';

	/**
	 * 无需登录的方法,同时也就不需要鉴权了
	 * @var array
	 */
	protected $noNeedLogin = [];

	/**
	 * 无需鉴权的方法,但需要登录
	 * @var array
	 */
	protected $noNeedRight = [];

	/**
	 * 权限Auth
	 * @var Auth
	 */
	protected $auth = null;

	public function _initialize()
	{
		//移除HTML标签
		$this->request->filter('strip_tags');
		$modulename     = $this->request->module();
		$controllername = strtolower($this->request->controller());
		$actionname     = strtolower($this->request->action());
		// 如果有使用模板布局
		if ($this->layout) {
			$this->view->engine->layout('layout/' . $this->layout);
		}
		$this->auth = Auth::instance();

		// token
		$token = $this->request->server('HTTP_TOKEN', $this->request->request('token', \think\Cookie::get('token')));

		$path = str_replace('.', '/', $controllername) . '/' . $actionname;

		// 设置当前请求的URI
		$this->auth->setRequestUri($path);
		// 检测是否需要验证登录
		if (!$this->auth->match($this->noNeedLogin)) {
			//初始化
			$this->auth->init($token);
			//检测是否登录
			if (!$this->auth->isLogin()) {
				$this->error(__('Please login first'), 'user/login');
			}
			// 判断是否需要验证权限
			if (!$this->auth->match($this->noNeedRight)) {
				// 判断控制器和方法判断是否有对应权限
				if (!$this->auth->check($path)) {
					$this->error(__('You have no permission'));
				}
			}
		} else {
			// 如果有传递token才验证是否登录状态
			if ($token) {
				$this->auth->init($token);
			}
		}

		$this->view->assign('user', $this->auth->getUser());

		// 语言检测
		$lang = strip_tags($this->request->langset());

		$site   = Config::get("site");
		$upload = \app\common\model\Config::upload();
		// 上传信息配置后
		Hook::listen("upload_config_init", $upload);
		$menu = Db::name('Category')->field('id,url,name,keywords,description,pid')->where(['status' => 1])->order('pid asc,weigh desc')->select();
		$menu_list = [];
		foreach ($menu as $key => $val){
			if($val['pid'] ==0){
				$menu_list[$val['id']] = $val;
			}else{
				$menu_list[$val['pid']]['chid'][] = $val;
			}
		}
		$menu_list = array_values($menu_list);
		$link          = Db::name('Link')->order('weigh desc')->select();
		$cusomer_count = Db::name('customer')->count();
		$num = intval((time()-strtotime('2019-01-01'))/(3600*36));
		// 配置信息
		$site['cdnurl'] = 'http://pphkj2wx9.bkt.clouddn.com';
		$config = [
			'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages', 'beian', 'telphone', 'logo'])),
			'upload'         => $upload,
			'modulename'     => $modulename,
			'controllername' => $controllername,
			'actionname'     => $actionname,
			'path'           => '/' . $modulename . '/' . $controllername . '/' . $actionname,
			'jsname'         => 'frontend/' . str_replace('.', '/', $controllername),
			'moduleurl'      => rtrim(url("/{$modulename}", '', false), '/'),
			'language'       => $lang,
			'link'           => $link,
			'customer_count' => $cusomer_count+500+$num
		];

		$config = array_merge($config, Config::get("view_replace_str"));

		Config::set('upload', array_merge(Config::get('upload'), $upload));

		$result = $this->search_word_from();
		if(!empty($result['form'])){
			session('form',$result);
		}else{
			session('form',[]);
		}
		// 配置信息后
		Hook::listen("config_init", $config);
		// 加载当前控制器语言包
		$this->loadlang($controllername);
		$this->assign('site', $site);
		$this->assign('menu', $menu_list);
		$this->assign('config', $config);
	}

	/**
	 * 加载语言文件
	 * @param string $name
	 */
	protected function loadlang($name)
	{
		Lang::load(APP_PATH . $this->request->module() . '/lang/' . $this->request->langset() . '/' . str_replace('.', '/', $name) . '.php');
	}

	/**
	 * 渲染配置信息
	 * @param mixed $name 键名或数组
	 * @param mixed $value 值
	 */
	protected function assignconfig($name, $value = '')
	{
		$this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
	}



	public function search_word_from() {
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		if(strstr( $referer, 'baidu.com')){ //百度
			preg_match( "|baidu.+wo?r?d=([^\\&]*)|is", $referer, $tmp );
			$keyword = urldecode( $tmp[1] );
			$from = 'baidu';
    	}elseif(strstr( $referer, 'google.com') or strstr( $referer, 'google.cn')){ //谷歌
			preg_match( "|google.+q=([^\\&]*)|is", $referer, $tmp );
			$keyword = urldecode( $tmp[1] );
			$from = 'google';
		}elseif(strstr( $referer, 'so.com')){ //360搜索
			preg_match( "|so.+q=([^\\&]*)|is", $referer, $tmp );
			$keyword = urldecode( $tmp[1] );
			$from = '360';
		}elseif(strstr( $referer, 'sogou.com')){ //搜狗
			preg_match( "|sogou.com.+query=([^\\&]*)|is", $referer, $tmp );
			$keyword = urldecode( $tmp[1] );
			$from = 'sogou';
		}elseif(strstr( $referer, 'soso.com')){ //搜搜
			preg_match( "|soso.com.+w=([^\\&]*)|is", $referer, $tmp );
			$keyword = urldecode( $tmp[1] );
			$from = 'soso';
		}else {
			$keyword ='';
			$from = '';
		}

		return array('keyword'=>$keyword,'from'=>$from);
	}

}
