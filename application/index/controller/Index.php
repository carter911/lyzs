<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Articles;
use app\index\model\Cases;
use think\Db;

use app\common\library\Token;
use think\Request;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';


    private $article;

    public function _initialize()
    {
        parent::_initialize();
        $this->article = new Articles;

    }

    public function index()
    {
        $this->assign('title', '首页-岭艺装饰');
        $banner = Db::name('banner')->field('image')->where(['status' => 1])->order('id desc')->find();
        $gxj = Db::name('banner')->where(['status' => 4])->order('id desc')->find();
        $tc = Db::name('banner')->where(['status' => 2])->order('id desc')->find();

        $customer_list = Db::name('customer')->limit(10)->order('id desc')->select();
        foreach ($customer_list as $key => $val) {
            $customer_list[$key]['name'] = mb_substr($val['name'], 0, 1);
            $customer_list[$key]['mobile'] = substr($val['mobile'], 0, 3) . '*******' . substr($val['mobile'], 10, 1);
            $customer_list[$key]['createtime'] = date("m/d", $val['createtime']);
        }
        // 团队banner
        $team_img = Db::name('team')->field('image')->limit(10)->order('id desc')->select();
//        $resdata = Db::table('fa_cases')->alias('cases')
//            ->join('team_door Door',find_in_set(Door.id,cases.team_door_ids))
//            ->group('cases.team_door_ids')->select();
        // 团队案例db('category')->alias('c')->join('__SERVICE__ s','find_in_set(s.id,c.s_id)')->group('s.s_id')->select()
//        $team_name = Db::name('team')->field('id,name')->select();
//        $teamDoor = Db::name('teamDoor')->field('id,name')->select();team_door_ids
//        dump($resdata);
        $casesModel = new Cases();
        $casesObj = $casesModel->limit(6)->order('id desc')->select();
        $cases_lists = $casesObj->toArray();
        $this->assign('cases_lists', $cases_lists);
        $this->assign('team_img', $team_img);
        $this->assign('banner', $banner);
        $this->assign('gxj', $gxj);
        $this->assign('tc', $tc);
        $this->assign('customers', $customer_list);
        return $this->view->fetch('index');
    }


    public function ppsl()
    {
        $this->assign('title', '品牌实力-岭艺装饰');
        return $this->view->fetch('ppsl');
    }

    public function gxj()
    {
        $this->assign('title', '个性家-岭艺装饰');
        return $this->view->fetch('gxj');
    }

    public function tc()
    {
        $this->assign('title', '套餐-岭艺装饰');
        return $this->view->fetch('tc');
    }

    public function gddz()
    {
        $this->assign('title', '高端定制-岭艺装饰');
        return $this->view->fetch('gddz');
    }

    public function sjal()
    {
        $this->assign('title', '实景案例-岭艺装饰');
        return $this->view->fetch('sjal');
    }

    //售后保障
    public function shbz()
    {
        return $this->view->fetch("shbz");
    }

    //设计团队
    public function sjtd()
    {
        $this->assign('title', '首页-设计团队');
        $team_list = Db::name('team')->limit(10)->order('id desc')->select();
        $lists = Db::name('team')->paginate(10, false, [
            'type' => 'page\Page'
        ]);
        $this->assign('lists', $lists);
        $this->assign('page', $lists->render());
        $this->assign('team_list', $team_list);
        return $this->view->fetch("sjtd");
    }

    //直播
    public function zb()
    {
        return $this->view->fetch("zb");
    }

    //最新活动
    public function zxhd($pageIndex = 1, $status = 1)
    {
        $article_list = $this->article->get_article_list($pageIndex, $status);
        $page_list = $this->article->get_page($pageIndex, $status);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);

        return $this->view->fetch("zxhd");
    }


    //家居资讯
    public function jjzx($pageIndex = 1, $status = 2)
    {
        $article_list = $this->article->get_article_list($pageIndex, $status);
        $page_list = $this->article->get_page($pageIndex, $status);

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
        $article_detail = $this->article->where(["id" => $id])->find();
        $this->assign("article_detail", $article_detail);

        return $this->view->fetch("article_detail");

    }


}
