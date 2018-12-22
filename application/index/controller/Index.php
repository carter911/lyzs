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
        // 团队案例
        $casesModel = new Cases();
        $casesObj = $casesModel->limit(6)->order('id desc')->select();
        $cases_lists = $casesObj->toArray();
        foreach ($cases_lists as $key => $val) {
            $resTeamDoor = Db::name('teamDoor')->field('name')
                ->where('id', 'in', $cases_lists[$key]['team_door_ids'])->select();
            foreach ($resTeamDoor as $k => $v) $resTD[$v['name']] = $k;
            $cases_lists[$key]['team_door_ids'] = array_keys($resTD);

            $resTeamStyle = Db::name('teamStyle')->field('name')
                ->where('id', 'in', $cases_lists[$key]['team_style_ids'])->select();
            foreach ($resTeamStyle as $k => $v) $resTS[$v['name']] = $k;
            $cases_lists[$key]['team_style_ids'] = array_keys($resTS);

            $resTeam = Db::name('team')->field('name')
                ->where('id', $cases_lists[$key]['team_team_id'])->find();
            $cases_lists[$key]['team_team_id'] = $resTeam['name'];

            $resCasesArea = Db::name('cases_area')->field('name')
                ->where('id', $cases_lists[$key]['cases_area_id'])->find();
            $cases_lists[$key]['cases_area_id'] = $resCasesArea['name'];
        }

        // 客户见证
		$witness_lists = Db::name('witness')->where(['status'=>1])->limit(50)->select();

        $this->assign('witness_lists', $witness_lists);
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
        $witnessList = Db::name('witness')->where(['status'=>2])->limit(48)->select();
		$this->assign('witnessList', $witnessList);
        return $this->view->fetch('ppsl');
    }

    public function gxj()
    {
    	$material_list = Db::name('Material')
			->where(['status'=>1])
			->limit(6)
			->select();

    	foreach ($material_list as $key => $val){
			$material_list[$key]['images'] = explode(",",$val['images']);
		}
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->select();
        $this->assign('witness_lists', $witness_lists);
        $this->assign('title', '个性家-岭艺装饰');

		$this->assign('material_list', $material_list);
        return $this->view->fetch('gxj');
    }

    public function tc()
    {
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->select();

        // 材料图片
        $material_lists = Db::name('material')->field('id,images')->select();
        // 主材
        $material_master_category = Db::name('material_category')->field('id,name')->where('status', 1)->limit(6)->select();
        $this->assign('material_lists', $material_lists);
        $this->assign('material_master_category', $material_master_category);

        $material_auxiliary_category = Db::name('material_category')->field('id,name')->where('status', 2)->limit(6)->select();
        //  $this->assign('material_lists', $material_lists);
        $this->assign('material_auxiliary_category', $material_auxiliary_category);

        $this->assign('witness_lists', $witness_lists);
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
        // 擅长户型
        $team_door = Db::name('team_door')->field('id,name')->select();
        // 风格
        $team_style = Db::name('team_style')->field('id,name')->select();
        $this->assign('team_style', $team_style);
        $this->assign('team_door', $team_door);
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
