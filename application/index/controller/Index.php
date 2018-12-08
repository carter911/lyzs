<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Articles;
use think\Db;

use app\common\library\Token;
use think\Request;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';


    private $article ;

    public function _initialize()
    {
        parent::_initialize();
        $this->article = new Articles;

    }

    public function index()
    {
		$this->assign('title','首页-岭艺装饰');
		$banner = Db::name('banner')->where(['status'=>1])->order('id desc')->find();
		$gxj = Db::name('banner')->where(['status'=>4])->order('id desc')->find();
		$tc = Db::name('banner')->where(['status'=>2])->order('id desc')->find();
		$this->assign('banner',$banner);
		$this->assign('gxj',$gxj);
		$this->assign('tc',$tc);
		return $this->view->fetch('index');
    }

    //售后保障
    public function shbz()
    {
        return $this->view->fetch("shbz");
    }

    //设计团队
    public function sjtd()
    {
        return $this->view->fetch("sjtd");
    }

    //直播
    public function zb()
    {
        return $this->view->fetch("zb");
    }

    //最新活动
    public function zxhd($pageIndex=1, $status=1)
    {
        $article_list = $this->article ->get_article_list($pageIndex,$status);
        $page_list = $this->article->get_page($pageIndex,$status);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);

        return $this->view->fetch("zxhd");
    }


    //家居资讯
    public function jjzx($pageIndex=1, $status=2)
    {
        $article_list = $this->article ->get_article_list($pageIndex,$status);
        $page_list = $this->article->get_page($pageIndex,$status);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);

        return $this->view->fetch("jjzx");
    }


    //联系我们
    public function lxwm()
    {

        $top_company = DB::name("companyInfo")->where(["top_state" => 0])->find();
        $sub_company = DB::name("companyInfo")->select();

        //工作列表
        $job_list = DB::name("jobs")->where(["job_status" => 1])->limit(0, 9)->select();

        $this->assign('top_company', $top_company);
        $this->assign("sub_company", $sub_company);
        $this->assign("job_list", $job_list);

        return $this->view->fetch("lxwm");
    }


    /**
     * 查看家居咨询&最新活动详情
     */
    public function look_article_detail($id)
    {
        $article_detail = $this->article ->where(["id" => $id])->find();
        $this->assign("article_detail", $article_detail);

        return $this->view->fetch("article_detail");

    }

	public function ppsl()
	{
		$this->assign('title','品牌实力-岭艺装饰');
		return $this->view->fetch('ppsl');
    }

	public function gxj()
	{
		$this->assign('title','个性家-岭艺装饰');
		return $this->view->fetch('gxj');
	}

	public function tc()
	{
		$this->assign('title','套餐-岭艺装饰');
		return $this->view->fetch('tc');
	}

	public function gddz()
	{
		$this->assign('title','高端定制-岭艺装饰');
		return $this->view->fetch('gddz');
	}

}
