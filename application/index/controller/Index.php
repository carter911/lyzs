<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Articles;
use app\index\model\Cases;
use fast\Date;
use think\Db;

use app\common\library\Token;
use think\Request;

class Index extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    private $article;
    private $cases;

    public function _initialize()
    {
        parent::_initialize();
        $this->article = new Articles;
        $this->cases = new Cases;
    }

    public function index()
    {
        $this->assign('title', '首页-岭艺装饰');

        $banner = Db::name('banner')->where(['status' => 1])->order('id desc')->select();
        $gxj = Db::name('banner')->where(['status' => 4])->order('id desc')->find();
        $tc = Db::name('banner')->where(['status' => 2])->order('id desc')->find();
        $customer_list = Db::name('customer')->limit(10)->order('id desc')->select();
        foreach ($customer_list as $key => $val) {
            $customer_list[$key]['name'] = mb_substr($val['name'], 0, 1);
            $customer_list[$key]['mobile'] = substr($val['mobile'], 0, 3) . '*******' . substr($val['mobile'], 10, 1);
            $customer_list[$key]['createtime'] = date("m/d", $val['createtime']);
        }

        // 团队banner
        $team_img = Db::name('team')->limit(10)->order('id desc')->where(['is_home' => 1])->select();
        foreach ($team_img as $key=>$value) {
            $team_img[$key]["subscribe_num"] =  $team_img[$key]["subscribe_num"] + DB::name("user_ip") -> where("team_id", $team_img[$key]["id"]) -> count();
        }


        // 团队案例
        $cases_lists = Db::name('cases')->where(['is_home' => 1])->limit(6)->order('id desc')->select();
        $resTeamDoor = Db::name('teamDoor')->field('id,name')->select();
        $resTeamStyle = Db::name('teamStyle')->field('id,name')->select();
        $TeamDoorData = array_combine(array_column($resTeamDoor, 'id'), array_column($resTeamDoor, 'name'));
        $TeamStyleData = array_combine(array_column($resTeamStyle, 'id'), array_column($resTeamStyle, 'name'));
        foreach ($cases_lists as $key => $val) {
            $cases_lists[$key]['team_door_ids'] = $TeamDoorData[$val['team_door_ids']];
            $cases_lists[$key]['team_style_ids'] = $TeamStyleData[$val['team_style_ids']];
            $resTeam = Db::name('team')->field('name')
                ->where('id', $cases_lists[$key]['team_team_id'])->find();
            $cases_lists[$key]['team_team_id'] = $resTeam['name'];

            $resCasesArea = Db::name('cases_area')->field('name')
                ->where('id', $cases_lists[$key]['cases_area_id'])->find();
            $cases_lists[$key]['cases_area_id'] = $resCasesArea['name'];
        }

        // 客户见证
        $witness_lists = Db::name('witness')->where(['status' => 1])->limit(50)->select();
        // 最新活动
        $article_list = Db::name('articles')->where(['status' => 1, 'is_home' => 1])->limit(4)->order('id desc')->select();
        $this->assign("article_list", $article_list);

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
        $witnessList = Db::name('witness')->where(['status' => 2])->limit(48)->select();
        $this->assign('witnessList', $witnessList);
        return $this->view->fetch('ppsl');
    }

    /**
     *
     * 个性家
     *
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gxj()
    {
        // 轮播图
        $banner = Db::name('banner')->field('id,image')->where(['status' => 4])->find();
        $this->assign('banner', $banner);

        // 主材
        $material_master_list = Db::name('Material')->field('id,name,images,status')->where(['status' => 1,'is_gxj'=>1])->limit(6)->select();
        foreach ($material_master_list as $key => $val) {
            $material_master_list[$key]['images'] = explode(",", $val['images']);
        }

        // 辅材
        $material_auxiliary_list = Db::name('Material')->where(['status' => 2,'is_gxj'=>1])->limit(6)->select();
        foreach ($material_auxiliary_list as $key => $val) {
            $material_auxiliary_list[$key]['images'] = explode(",", $val['images']);
        }

        //工序
        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();

        //添加所以数组
        $procedure_image_list = [];
        $procedure_title_list = [];
        foreach ($procedure_list as $key => $val) {
            $tempImageList = explode(",", $val['images']);
            $tempCount = sizeof($procedure_image_list);

            $procedure_image_list = array_merge($procedure_image_list, $tempImageList);
            $procedure_title_list[] = array(
                "title" => $val["name"],
                "start" => $tempCount,
                "end" => sizeof($procedure_image_list)
            );
        }

        $this->assign('procedure_image_list', $procedure_image_list);
        $this->assign("procedure_title_list", $procedure_title_list);

        $this->assign('material_master_list', $material_master_list);
        $this->assign('material_auxiliary_list', $material_auxiliary_list);
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->where(['status' => 1])->select();
        $this->assign('witness_lists', $witness_lists);

        $this->assign('title', '个性家-岭艺装饰');


        //典型案例介绍
        $caseList = $this->cases->queryAllKindStyleCases(['is_gxj' => 1]);
        $this->assign("caseList", $caseList);

        return $this->view->fetch('gxj');
    }

    public function tc()
    {
        // 轮播图
        $banner = Db::name('banner')->field('id,image')->where(['status' => 2])->find();
        $this->assign('banner', $banner);
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->where(['status' => 1])->select();
        $this->assign('witness_lists', $witness_lists);
        // 主材
        $material_master_list = Db::name('Material')->where(['status' => 1, 'is_yj'=>1])->limit(6)->select();
        foreach ($material_master_list as $key => $val) {
            $material_master_list[$key]['images'] = explode(",", $val['images']);
        }
        // 辅材
        $material_auxiliary_list = Db::name('Material')->where(['status' => 2,'is_yj'=>1])->limit(6)->select();
        foreach ($material_auxiliary_list as $key => $val) {
            $material_auxiliary_list[$key]['images'] = explode(",", $val['images']);
        }


        //工序
        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();
        //添加所以数组
        $procedure_image_list = [];
        $procedure_title_list = [];
        foreach ($procedure_list as $key => $val) {
            $tempImageList = explode(",", $val['images']);
            $tempCount = sizeof($procedure_image_list);

            $procedure_image_list = array_merge($procedure_image_list, $tempImageList);
            $procedure_title_list[] = array(
                "title" => $val["name"],
                "start" => $tempCount,
                "end" => sizeof($procedure_image_list)
            );
        }

        // 根据 风格 展示 实景案例
        $team_style_list = Db::name('team_style')->field("id,name")->limit(5)->order('id desc')->select();

        $cases_list = Db::name('cases')->field("id,name,image,images,team_style_ids")
            ->where(['status' => "1", 'is_yj' => 1])->limit(5)->order('id desc')->select();

        foreach ($cases_list as $key => $val) {
            $cases_list[$key]['style'] = Db::name('team_style')->where('id', 'in', $val['team_style_ids'])->find();
            $cases_list[$key]['images'] = [];
            if (!empty($val['images'])) {
                $cases_list[$key]['images'] = explode(",", $val['images']);
            }
            $countNum = count($cases_list[$key]['images']);

            if ($countNum < 5) {
                for ($num = 1; $num <= (5 - $countNum); $num++) {
                    $cases_list[$key]['images'][] = $val['image'];
                }
            }
        }


        $live_cases_list = [];
        foreach ($team_style_list as $key => $val) {
            foreach ($cases_list as $k => $v) {
                if ($val['id'] == $v['team_style_ids']) {
                    $team_style_list[$key]['images'] = [];
                    if (!empty($v['images'])) {
                        $team_style_list[$key]['images'] = $v['images'];
                    }

                    $team_style_list[$key]['style'] = $cases_list[$k];
                }
            }
            if (isset($team_style_list[$key]['style'])) {
                array_push($live_cases_list, $team_style_list[$key]);
            }
        }

        // 根据 风格 展示 实景案例 live_cases
        $this->assign('live_cases_list', $live_cases_list);
        $this->assign('procedure_list', $procedure_list);
        $this->assign('material_master_list', $material_master_list);
        $this->assign('material_auxiliary_list', $material_auxiliary_list);

        $this->assign('procedure_image_list', $procedure_image_list);
        $this->assign("procedure_title_list", $procedure_title_list);
        $this->assign("cases_list", $cases_list);

        $this->assign('title', '优家-岭艺装饰');
        return $this->view->fetch('tc');
    }

    public function gddz()
    {
        // 我们的团队
        $me_team_list = Db::name('team')->where(['is_gddz' => 1])->limit(4)->order('id desc')->select();
        $this->assign('me_team_list', $me_team_list);
        // 案例  品质境界即刻体验
        $cases_list = Db::name('cases')->field("id,name,image,team_door_ids,xq_name")->where(['is_gddz' => 1])->limit(5)->order('id desc')->select();
        foreach ($cases_list as $key => $val) {
            $tmp_team_door_ids = substr($val['team_door_ids'], -1);
            $team_door_name = Db::name('team_door')->field('name')->where(['id' => $tmp_team_door_ids])->find();
            $cases_list[$key]['team_door_ids'] = $team_door_name['name'];
        }

        $this->assign('cases_list', $cases_list);
        $butler_list = Db::name('butler')->limit(8)->where(['is_gdzd' => 1])->select();
        foreach ($butler_list as $key => $val) {
            $cases = Db::name('cases')->field('id,name')->where(['butler_id' => $val['id']])->limit(4)->select();
            $butler_list[$key]['cases'] = $cases;
        }


        //工序
        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();
        //添加所以数组
        $procedure_image_list = [];
        $procedure_title_list = [];
        foreach ($procedure_list as $key => $val) {
            $tempImageList = explode(",", $val['images']);
            $tempCount = sizeof($procedure_image_list);

            $procedure_image_list = array_merge($procedure_image_list, $tempImageList);
            $procedure_title_list[] = array(
                "title" => $val["name"],
                "start" => $tempCount,
                "end" => sizeof($procedure_image_list)
            );
        }

        $this->assign('procedure_image_list', $procedure_image_list);
        $this->assign("procedure_title_list", $procedure_title_list);

        $this->assign('butler_list', $butler_list);

        $this->assign('title', '高端定制-岭艺装饰');
        return $this->view->fetch('gddz');
    }


    //售后保障
    public function shbz()
    {
        return $this->view->fetch("shbz");
    }

    //设计团队
    public function sjtd(Request $request)
    {
        $this->assign('title', '首页-设计团队');

        $param = $request->get();
        $search = ['door' => '', 'style' => '', 'time' => '', 'sex' => ''];

        $where = [];
        if (isset($param['door']) && $param['door'] > 0) {

            $door = $param['door'];
            $search['door'] = $door;
            $where[] = ['exp', Db::raw("FIND_IN_SET('$door',team_door_ids)")];
        }

        if (isset($param['style']) && $param['style'] > 0) {
            $style = $param['style'];
            $search['style'] = $style;
            $where[] = ['exp', Db::raw("FIND_IN_SET('$style',team_style_ids)")];
        }

        if (isset($param['time']) && $param['time'] != "") {
            $search['time'] = $param['time'];
            $where['work_num'] = ['between', $param['time']];
        }

        if (isset($param['sex']) && $param['sex'] != "") {
            $search['sex'] = $param['sex'];
            $where['sex'] = $param['sex'];
        }

        $page = 1;
        if (isset($param['page']) && is_numeric($param['page'])) {
            $page = $param['page'];
        }

        if (isset($param['keyword']) && !empty($param['keyword'])) {
            $where['name'] = ['like', '%' . $param['keyword'] . '%'];
        }

        $lists = Db::name('team')->where($where)->paginate(8, false, [
            'page' => $page
        ]) ;

        $team_list = $lists->items();
        foreach ($team_list as $key=>$value) {
            $team_list[$key]["subscribe_num"] =  $team_list[$key]["subscribe_num"] + DB::name("user_ip") -> where("team_id", $team_list[$key]["id"]) -> count();
        }

        // 擅长户型
        $team_door = Db::name('team_door')->field('id,name')->select();
        // 风格
        $team_style = Db::name('team_style')->field('id,name')->select();

        $this->assign('team_style', $team_style);
        $this->assign('team_door', $team_door);
        $this->assign('team_list', $team_list);
        $this->assign('search', $search);

        $page = $this->get_page($lists->currentPage(), $lists->total());
        $this->assign('page', $page);
        $this->assign('param', $param);

        $pageParams = $this->parse_query_params($search);
        $this->assign("pageParams", $pageParams);


        return $this->view->fetch("sjtd");
    }


    //设计团队 详情
    public function sjtd_detail($detail_id)
    {
        //当前用户id
        $stylist = Db::name('team')->find($detail_id);
        $stylist["subscribe_num"] = $stylist["subscribe_num"] + DB::name("user_ip") -> where("team_id",$detail_id) -> count();

        // 擅长户型
        $stylist_door = Db::name('team_door')->field('name')->where('id', 'in', $stylist['team_door_ids'])->limit(3)->select();
        $stylist['team_door_ids'] = implode(', ', array_column($stylist_door, 'name'));
        $this->assign('stylist', $stylist);
        // 设计师案例列表
        $case_list = Db::name('cases')->where('team_team_id', $detail_id)->select();
        // 案例 总和
        $case_total = Db::name('cases')->where('team_team_id', $detail_id)->count('id');
        // 户型
        $door_list = Db::name('team_door')->field('id,name')->select();
        $style_list = Db::name('team_style')->field('id,name')->select();
        $TeamDoorData = array_combine(array_column($door_list, 'id'), array_column($door_list, 'name'));
        $TeamStyleData = array_combine(array_column($style_list, 'id'), array_column($style_list, 'name'));
        foreach ($case_list as $key => $val) {
            $case_list[$key]['team_door_ids'] = $TeamDoorData[$val['team_door_ids']];
            $case_list[$key]['team_style_ids'] = $TeamStyleData[$val['team_style_ids']];
        }
        $this->assign('case_total', $case_total);
        $this->assign('case_list', $case_list);
        // 获取当前小时
        $current_hour = Date('H', time());

        // 其他设计师列表  用当前时间作为 页数
        $team_list = Db::name('team')->limit(intval($current_hour % 2), 5)->select();
        foreach ($team_list as $key=>$value) {
            $team_list[$key]["subscribe_num"] =  $team_list[$key]["subscribe_num"] + DB::name("user_ip") -> where("team_id", $team_list[$key]["id"]) -> count();
        }

        $this->assign('team_list', $team_list);
        $this->assign('title', '设计团队详情');
        return $this->view->fetch("sjtd_detail");
    }

    //直播
    public function zb(Request $request)
    {
        $this->assign('title', '首页-直播');
        $param = $request->param();
        $where = ['is_show' => 1,'image'=>['neq','']];
        if (isset($param['city_name']) && !empty($param['city_name'])) {
            if ($param['city_name'] == 'other') {
                $where['city_name'] = ['eq', ''];
            } else {
                $where['city_name'] = ['like', '%' . $param['city_name'] . '%'];
            }
        } else {
            $param['city_name'] = '';
        }

        if (isset($param['rate']) && !empty($param['rate'])) {
            $where['circle_name'] = ['like', '%' . $param['rate'] . '%'];
        } else {
            $param['rate'] = '';
        }

        if (isset($param['keyword']) && !empty($param['keyword'])) {
            $where['name'] = ['like', '%' . $param['keyword'] . '%'];
        } else {
            $param['keyword'] = '';
        }

        $order = 'id desc';
        if (isset($param['sort']) && !empty($param['sort'])) {
            if (intval($param['sort']) == 1) {
                $order = 'look_num desc';
            } else if (intval($param['sort']) == 2) {
                $order = 'id desc';
            }

        } else {
            $param['sort'] = 0;
        }
        $list = Db::name('project')->where($where)->order($order)->paginate(20, false, ['query' => request()->param()])->each(function ($val, $key) {
            $val['start_time'] = date('Y-m-d', strtotime($val['start_time']));
            $val['end_time'] = date('Y-m-d', strtotime($val['end_time']));
            $val['circle'] = json_decode($val['circle'], true);
            array_multisort(array_column($val['circle'], 'start_time'), SORT_ASC, $val['circle']);
            $val['image'] = array_slice(json_decode($val['image'], true), 0, 6);
            return $val;
        });

        $page = $list->render();
        $area = Db::name('area')->where(['parent_id' => 24])->select();
        $circle = Db::name('project_circle')->select();

        $this->assign('area', $area);
        $this->assign('list', $list);
        $this->assign('param', $param);
        $this->assign('circle', $circle);

        $this->assign('page', $page);
        return $this->view->fetch("zb");
    }

    //直播详情
    public function zb_detail(Request $request)
    {
        $id = $request->get('detail_id');
        $this->assign('title', '直播详情');
        Db::name('project')->where('id', $id)->setInc('look_num');
        $info = Db::name('project')->where('id', $id)->find();
        $info['circle'] = json_decode($info['circle'], true);
        $info['task'] = json_decode($info['task'], true);
        $info['image'] = array_slice(json_decode($info['image'], true), 0, 6);
        foreach ($info['image'] as $key => $val) {
            if (empty($val)) {
                unset($info['image'][$key]);
            }
        }
        $next = Db::name('project')->where(['id' => ['gt', $id]])->find();
        $pre = Db::name('project')->where(['id' => ['lt', $id]])->find();
        $this->assign('next', $next);
        $this->assign('pre', $pre);
        $this->assign('info', $info);
        $customer_list = Db::name('customer')->where('status', 1)->limit(0,20)->select();
        $customer_total = Db::name('customer')->count('id');
        $this->assign('customer_list', $customer_list);
        $this->assign('customer_total', $customer_total);
        return $this->view->fetch("zb_detail");
    }

    //最新活动
    public function zxhd($pageIndex = 1, $status = 1, $keyword = "")
    {
        $article_list = $this->article->get_article_list($pageIndex, $status, $keyword);
        $page_list = $this->article->get_page($pageIndex, $status, $keyword);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);
        $this->assign("param", Request::instance()->param());
        $this->assign('title', '首页-最新活动');
        return $this->view->fetch("zxhd");
    }


    //家居资讯
    public function jjzx($pageIndex = 1, $status = 2, $keyword = "")
    {

        $article_list = $this->article->get_article_list($pageIndex, $status, $keyword);
        $page_list = $this->article->get_page($pageIndex, $status, $keyword);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);
        $this->assign("param", Request::instance()->param());

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
        $this->assign("job_size", sizeof($job_list));

        return $this->view->fetch("lxwm");
    }

    /**
     * 查看家居咨询&最新活动详情
     */
    public function look_article_detail($id)
    {
        $article_detail = $this->article->where(["id" => $id])->find();
		$article_detail['content'] = str_replace('http://pphkj2wx9.bkt.clouddn.com','http://cdn.ly-home.cn',$article_detail['content']);
        $article_prev = $this->article->field('id,title')->where(["id" => ['lt', $id]])->order('id desc')->find();
        $article_next = $this->article->field('id,title')->where(["id" => ['gt', $id]])->order('id asc')->find();
        $this->assign("article_prev", $article_prev);
        $this->assign("article_next", $article_next);
        $this->assign("article_detail", $article_detail);
        // 获取文章列表
        $article_list = Db::name('articles')->field('id,title,image,createtime')->limit(6)->select();
        $this->assign("article_list", $article_list);
        // 获取实景案例
        $cases_list = Db::name('cases')->field('id,name,image')->where('status', 1)->limit(3)->select();
        // 顾客列表
        $customer_list = Db::name('customer')->where('status', 1)->select();
        $customer_total = Db::name('customer')->count('id');
        $this->assign('cases_list', $cases_list);
        $this->assign('customer_list', $customer_list);
        $this->assign('customer_total', $customer_total);
        $this->assign('title', '文章详情-岭艺装饰');
        return $this->view->fetch("article_detail");
    }

    public function sjal($caseStyle = -1, $doorStyle = -1, $areaStyle = -1, $pageIndex = 0, $keyword = '')
    {
        $this->assign('title', '实景案例-岭艺装饰');

        $door_case = DB::name("team_door")->select();
        $door_style = DB::name("team_style")->select();
        $door_areas = DB::name("cases_area")->select();

        $this->assign("door_case", $door_case);
        $this->assign("door_style", $door_style);
        $this->assign("door_areas", $door_areas);

        $this->assign("caseStyle", $caseStyle);
        $this->assign("doorStyle", $doorStyle);
        $this->assign("areaStyle", $areaStyle);
        //案例
        $caseResult = $this->cases->queryStyleCase($caseStyle, $doorStyle, $areaStyle, $keyword, 1, $pageIndex);
        $this->assign("caseResult", $caseResult);
        $this->assign("caseResultSize", sizeof($caseResult));
        $video = DB::name("cases_video")->limit(4)->select();
        $this->assign("video", $video);
        $this->assign('param', Request::instance()->param());
        return $this->view->fetch('sjal');
    }

    public function sjal_detail(Request $request)
    {
        $id = $request->get('detail_id');
        $article_detail = $this->cases->where(["id" => $id])->find();
		$article_detail['content'] = str_replace('http://pphkj2wx9.bkt.clouddn.com','http://cdn.ly-home.cn',$article_detail['content']);
        $article_prev = $this->cases->where(["id" => ['lt', $id]])->order('id desc')->find();
        $article_next = $this->cases->where(["id" => ['gt', $id]])->order('id asc')->find();
        $this->assign("article_prev", $article_prev);
        $this->assign("article_next", $article_next);
        $info_data = $article_detail->toArray();
        $team = Db::name('team')->where(['id' => $article_detail['team_team_id']])->find();
        $this->assign('title', '实景案例详情-岭艺装饰');
        // 户型
        $door_list = Db::name('team_door')->field('id,name')->select();
        $style_list = Db::name('team_style')->field('id,name')->select();
        $TeamDoorData = array_combine(array_column($door_list, 'id'), array_column($door_list, 'name'));
        $TeamStyleData = array_combine(array_column($style_list, 'id'), array_column($style_list, 'name'));
        $article_detail['team_door_ids'] =$TeamDoorData[$article_detail['team_door_ids']];
        $article_detail['team_style_ids'] =$TeamStyleData[$article_detail['team_style_ids']];
        $this->assign("cases", $article_detail);
        $this->assign("info", $info_data);
        $this->assign("team", $team);
        return $this->fetch('sjal-detail');
    }


    public function sjal0($caseStyle = -1, $doorStyle = -1, $areaStyle = -1,$pageIndex=1)
    {
        $this->assign('title', '实景案例-岭艺装饰');

        $door_case = DB::name("team_door")->select();
        $door_style = DB::name("team_style")->select();
        $door_areas = DB::name("cases_area")->select();

        $this->assign("door_case", $door_case);
        $this->assign("door_style", $door_style);
        $this->assign("door_areas", $door_areas);

        $this->assign("caseStyle", $caseStyle);
        $this->assign("doorStyle", $doorStyle);
        $this->assign("areaStyle", $areaStyle);
        //案例
        $caseResult = $this->cases->queryStyleCase($caseStyle, $doorStyle, $areaStyle, $keyword = '', $status = 2, $pageIndex);
        $this->assign("caseResult", $caseResult);
        $this->assign("caseResultSize", sizeof($caseResult));

        $video = DB::name("cases_video")->limit(4)->select();
        $this->assign("video", $video);

        return $this->view->fetch('sjal0');
    }


    private function get_page($pageIndex, $totalSize)
    {

        $lastPage = $totalSize % 8 == 0 ? intval($totalSize / 8) : intval($totalSize / 8) + 1;
        if ($pageIndex > $lastPage) $pageIndex = $lastPage;

        return array(
            "first_page" => 1,
            "last_page" => $lastPage,
            "prev_page" => $pageIndex - 1 < 1 ? 1 : $pageIndex - 1,
            "next_page" => $pageIndex + 1 > $lastPage ? $lastPage : $pageIndex + 1,

            "start_page" => $pageIndex - 2 < 1 ? 1 : $pageIndex - 2,
            "end_page" => $pageIndex + 2 > $lastPage ? $lastPage + 1 : $pageIndex + 3,

            "current_page" => $pageIndex,
            "total_page" => $totalSize,
        );
    }

    private function parse_query_params($array)
    {

        $target = '';
        foreach ($array as $key => $value) {
            $target = $target . $key . '=' . $value . '&';
        }

        return $target;
    }


}
