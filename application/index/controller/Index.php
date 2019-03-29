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
        $team_img = Db::name('team')->limit(10)->order('id desc')->select();
        // 团队案例
        $casesModel = new Cases();
        $casesObj = $casesModel->limit(6)->order('id desc')->select();
        $cases_lists = $casesObj/*$casesObj->toArray()*/
        ;
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
        $witness_lists = Db::name('witness')->where(['status' => 1])->limit(50)->select();
        // 最新活动
        $article_list = Db::name('articles')->where(['status' => 1])->limit(4)->select();
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
    public function gxj() {
        // 轮播图
        $banner = Db::name('banner')->field('id,image')->where(['status' => 4])->find();
        $this->assign('banner', $banner);

        // 主材
        $material_master_list = Db::name('Material')->field('id,name,images,status')->where(['status' => 1])->limit(6)->select();
        foreach ($material_master_list as $key => $val) {
            $material_master_list[$key]['images'] = explode(",", $val['images']);
        }

        // 辅材
        $material_auxiliary_list = Db::name('Material')->where(['status' => 2])->limit(6)->select();
        foreach ($material_auxiliary_list as $key => $val) {
            $material_auxiliary_list[$key]['images'] = explode(",", $val['images']);
        }

        //工序
        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();
        foreach ($procedure_list as $key => $val) {
            $procedure_list[$key]['images'] = explode(",", $val['images']);
        }


        $this->assign('procedure_list', $procedure_list);
        $this->assign('material_master_list', $material_master_list);
        $this->assign('material_auxiliary_list', $material_auxiliary_list);
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->select();
        $this->assign('witness_lists', $witness_lists);

        $this->assign('title', '个性家-岭艺装饰');


        //典型案例介绍
        $caseList = $this->cases->queryAllKindStyleCases();
        $this->assign("caseList", $caseList);

        return $this->view->fetch('gxj');
    }

    public function tc()
    {
        // 轮播图
        $banner = Db::name('banner')->field('id,image')->where(['status' => 2])->find();
        $this->assign('banner', $banner);
        // 客户见证
        $witness_lists = Db::name('witness')->field('image')->select();
        $this->assign('witness_lists', $witness_lists);
        // 主材
        $material_master_list = Db::name('Material')->where(['status' => 1])->limit(6)->select();
        foreach ($material_master_list as $key => $val) {
            $material_master_list[$key]['images'] = explode(",", $val['images']);
        }
        // 辅材
        $material_auxiliary_list = Db::name('Material')->where(['status' => 2])->limit(6)->select();
        foreach ($material_auxiliary_list as $key => $val) {
            $material_auxiliary_list[$key]['images'] = explode(",", $val['images']);
        }

        //工序
        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();
        foreach ($procedure_list as $key => $val) {
            $procedure_list[$key]['images'] = explode(",", $val['images']);
        }
        // 根据 风格 展示 实景案例
        $team_style_list = Db::name('team_style')->field("id,name")->limit(5)->order('id desc')->select();
        $cases_list = Db::name('cases')->field("id,name,image,team_style_ids")
            ->where(['status' => "1"])->limit(5)->order('id desc')->select();
        $live_cases_list = [];
        foreach ($team_style_list as $key => $val) {
            foreach ($cases_list as $k => $v) {
                if ($val['id'] == $v['team_style_ids']) {
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
        $this->assign('title', '优家-岭艺装饰');
        return $this->view->fetch('tc');
    }

    public function gddz()
    {
        // 我们的团队
        $me_team_list = Db::name('team')->field("id,name,image,work_name,content")->limit(4)->order('id asc')->select();
        $this->assign('me_team_list', $me_team_list);
        // 案例  品质境界即刻体验
        $cases_list = Db::name('cases')->field("id,name,image,team_door_ids")->limit(5)->order('id desc')->select();
        foreach ($cases_list as $key => $val) {
            $tmp_team_door_ids = substr($val['team_door_ids'], -1);
            $team_door_name = Db::name('team_door')->field('name')->where(['id' => $tmp_team_door_ids])->find();
            $cases_list[$key]['team_door_ids'] = $team_door_name['name'];
        }

        $this->assign('cases_list', $cases_list);
        $butler_list = Db::name('butler')->limit(8)->select();
        foreach ($butler_list as $key => $val) {
            $cases = Db::name('cases')->field('name')->where(['butler_id' => $val['id']])->limit(4)->select();
            $butler_list[$key]['cases'] = $cases;
        }


        $procedure_list = Db::name('procedure')->limit(10)->order('sort desc')->select();
        foreach ($procedure_list as $key => $val) {
            $procedure_list[$key]['images'] = explode(",", $val['images']);
        }

        $this->assign('procedure_list', $procedure_list);

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

        $page = 1 ;
        if(isset($param['page']) && is_numeric($param['page'])){
            $page = $param['page'];
        }

        $lists = Db::name('team')->where($where)->paginate(12,false, [
            'page' => $page
        ]);


        // 擅长户型
        $team_door = Db::name('team_door')->field('id,name')->select();
        // 风格
        $team_style = Db::name('team_style')->field('id,name')->select();

        $this->assign('team_style', $team_style);
        $this->assign('team_door', $team_door);
        $this->assign('team_list', $lists -> items());
        $this->assign('search', $search);

        $page = $this->get_page($lists->currentPage(), $lists->total());
        $this->assign('page', $page);

        $pageParams = $this -> parse_query_params($search);
        $this->assign("pageParams",$pageParams);


        return $this->view->fetch("sjtd");
    }



    //设计团队 详情
    public function sjtd_detail($detail_id)
    {
        $stylist = Db::name('team')->find($detail_id);
        // 擅长户型
        $stylist_door = Db::name('team_door')->field('name')->where('id','in',$stylist['team_door_ids'])->limit(3)->select();
        $stylist['team_door_ids'] = implode(', ', array_column($stylist_door,'name') );
        $this->assign('stylist', $stylist);
        $this->assign('title', '设计团队详情');
        return $this->view->fetch("sjtd_detail");
    }

    //直播
    public function zb()
    {
        $this->assign('title', '首页-直播');
        return $this->view->fetch("zb");
    }

    //直播详情
    public function zb_detail($detail_id)
    {
        $this->assign('title', '直播详情');
        return $this->view->fetch("zb_detail");
    }

    //最新活动
    public function zxhd($pageIndex = 1, $status = 1)
    {
        $article_list = $this->article->get_article_list($pageIndex, $status);
        $page_list = $this->article->get_page($pageIndex, $status);

        $this->assign("article_list", $article_list);
        $this->assign("article_page", $page_list);

        $this->assign('title', '首页-最新活动');
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
        $this->assign("job_size", sizeof($job_list));

        return $this->view->fetch("lxwm");
    }

    /**
     * 查看家居咨询&最新活动详情
     */
    public function look_article_detail($id)
    {
        $article_detail = $this->article->where(["id" => $id])->find();
        $this->assign("article_detail", $article_detail);
        $this->assign('title', '文章详情-岭艺装饰');
        return $this->view->fetch("article_detail");

    }

    public function sjal($caseStyle = -1, $doorStyle = -1, $areaStyle = -1)
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
        $caseResult = $this->cases->queryStyleCase($caseStyle, $doorStyle, $areaStyle);
        $this->assign("caseResult", $caseResult);
        $this->assign("caseResultSize", sizeof($caseResult));

        return $this->view->fetch('sjal');
    }

    public function sjal_detail(Request $request)
    {
        $id = $request->get('detail_id');
        $article_detail = $this->cases->where(["id" => $id])->find();
        $team = Db::name('team')->where(['id'=>$article_detail['team_team_id']])->find();
        $this->assign('title', '实景案例详情-岭艺装饰');
        $this->assign("info", $article_detail);
        $this->assign("team", $team);
        return $this->view->fetch('sjal-detail');
    }


    public function sjal0($caseStyle = -1, $doorStyle = -1, $areaStyle = -1)
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
        $caseResult = $this->cases->queryStyleCase($caseStyle, $doorStyle, $areaStyle, $keyword = '', $status = 2);
        $this->assign("caseResult", $caseResult);
        $this->assign("caseResultSize", sizeof($caseResult));

        $video = DB::name("cases_video")->limit(4)->select();
        $this->assign("video", $video);

        return $this->view->fetch('sjal0');
    }


    private function get_page($pageIndex, $totalSize){

        $lastPage = $totalSize % 12 == 0 ? intval($totalSize / 12) : intval($totalSize / 12) + 1;
        if($pageIndex > $lastPage) $pageIndex = $lastPage;

        return array(
            "first_page" => 1,
            "last_page" => $lastPage,
            "prev_page" => $pageIndex - 1 < 1 ? 1 : $pageIndex - 1,
            "next_page" => $pageIndex + 1 > $lastPage ? $lastPage : $pageIndex + 1,

            "start_page" => $pageIndex - 2 < 1 ? 1 : $pageIndex - 2,
            "end_page" => $pageIndex + 2 > $lastPage ? $lastPage + 1 : $pageIndex + 3,

            "current_page" => $pageIndex,
            "total_page"   => $totalSize,
        );
    }

    private function parse_query_params($array) {

        $target = '';
        foreach($array as $key => $value){
            $target = $target . $key . '=' . $value . '&';
        }

        return $target ;
    }


    public function showDialog(Request $request) {
        $param = $request->get();




        if(empty($param["type"])){
            return "";
        }

        //TODO data to deal with
        return $this->showDialogData($param["type"]);

    }


    /**
     *
     * 预约专属管家
     * @type
     * @throws \think\Exception
     *

     * @return no data
     */
    private function showDialogData($type) {
        $result =  $this -> view -> fetch("dialog/".$type);
        $result = explode("<!---------------------------------主体内容---------------------------------------------->",$result);
        $result = explode("<!----------------------------------固定区域--------------------------------------------->",$result[1]);
        return $result[0];

    }




}
