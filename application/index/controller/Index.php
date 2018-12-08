<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Articles;
use think\Db;

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
        $this->assign('title', '岭艺装饰-首页');
        return $this->view->fetch('index');
    }

    public function ppsl()
    {
        return $this->view->fetch('ppsl');
    }

    public function gxj()
    {
        return $this->view->fetch('gxj');
    }

    public function tc()
    {
        return $this->view->fetch('tc');
    }

    public function gddz()
    {
        return $this->view->fetch('gddz');
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


    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

}
