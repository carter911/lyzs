<?php
/**
 * Created by PhpStorm.
 * User: xinghe
 * Date: 31/03/2019
 * Time: 8:36 AM
 */

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Customer;
use think\Db;
use think\Exception;
use think\Request;

class Form extends Frontend {

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    private $customer;


    public function _initialize() {
        parent::_initialize();
        $this->customer = new Customer();
    }

    public function showDialog(Request $request){
        $param = $request->get();

        if (empty($param["type"])) {
            return "";
        }

        switch ($param["type"]) {
            case 'yuyue_manager':
                return $this -> yuyue_manager();

            case  "baojia":
                return $this -> baojia();

            case "canjia_activity" :
                return $this -> canjia_activity();

            case "dingzhi_service":
                return $this -> dingzhi_service();

            case "gongdi":
                return $this -> gongdi();

            case "yuyue_designer":
                return $this -> yuyue_designer();

            case "tiyan_app":
                return $this -> tiyan_app();

            case "liaojie_more" :
                return $this -> liaojie_more();

        }

        //TODO data to deal with
        return $this->showDialogData($param["type"]);
    }


    public function showDialogWithId(Request $request) {
        $param = $request->get();

        if (empty($param["type"])) {
            return "";
        }

        switch ($param["type"]) {
            case 'yuyue_ta':
                return $this -> yuyue_ta($param);
                break;
        }

    }


    /**
     * 预约项目管家
     */
    private function yuyue_manager(){
        $totalCount = DB::name("customer") -> where(["form_type" => "yuyue_manager"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "yuyue_manager"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData('yuyue_manager');
    }

    private function baojia() {
        $totalCount = DB::name("customer") -> where(["form_type" => "baojia"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "baojia"]) -> limit(2) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData('baojia');
    }

    private function canjia_activity() {
        $totalCount = DB::name("customer") -> where(["form_type" => "canjia_activity"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "canjia_activity"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData('canjia_activity');
    }

    private function yuyue_ta($param) {
        $totalCount = DB::name("customer") -> where(["form_type" => "yuyue_ta"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "yuyue_ta"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);
        $this->assign("designerId", $param["otherId"]);

        return $this -> showDialogData('yuyue_ta');
    }

    private function dingzhi_service() {
        $totalCount = DB::name("customer") -> where(["form_type" => "dingzhi_service"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "dingzhi_service"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        //TODO 获取设计师 设计师排序
        $teamList = DB::name("team") -> limit(10) -> select();

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);
        $this->assign("teamList", $teamList);

        return $this -> showDialogData("dingzhi_service");
    }

    private function gongdi() {
        $totalCount = DB::name("customer") -> where(["form_type" => "gongdi"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "gongdi"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData("gongdi");
    }

    private function yuyue_designer(){
        $totalCount = DB::name("customer") -> where(["form_type" => "yuyue_designer"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "yuyue_designer"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData("yuyue_designer");
    }

    private function tiyan_app(){
        $totalCount = DB::name("customer") -> where(["form_type" => "tiyan_app"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "tiyan_app"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData("tiyan_app");
    }

    private function liaojie_more(){
        $totalCount = DB::name("customer") -> where(["form_type" => "liaojie_more"]) -> count();
        $managerList = DB::name("customer") -> where(["form_type" => "liaojie_more"]) -> limit(3) -> select();
        $managerList = $this->parseArrayData($managerList);

        $this->assign("totalCount",$totalCount);
        $this->assign("managerList",$managerList);

        return $this -> showDialogData("liaojie_more");



    }


    /**
     *
     * 预约专属管家
     * @type
     * @throws \think\Exception
     *
     * @return no data
     */
    private function showDialogData($type)
    {
        $result = $this->view->fetch("dialog/" . $type);
        $result = explode("<!---------------------------------主体内容---------------------------------------------->", $result);
        $result = explode("<!----------------------------------固定区域--------------------------------------------->", $result[1]);
        return $result[0];

    }

    private function parseArrayData($dataArray) {
        foreach ($dataArray as $key => $val) {
            $dataArray[$key]['name'] = mb_substr($val['name'], 0, 1). "**";
            $dataArray[$key]['phone'] = substr($val['mobile'], 0, 3) . '****' . substr($val['mobile'], 7, 4);
            $dataArray[$key]['time'] = date("m/d", $val['createtime']);
        }

        return $dataArray;

    }

    /**
     * 提交表单
     */
    public function submitForm(Request $request) {
        $postValue = $request -> post();

        switch ($postValue["type"]) {
            case 'yuyue_manager':
                return $this -> goto_yuyue_manager($postValue);

            case "baojia":
                return $this -> goto_baojia($postValue);

            case "canjia_activity" :
                return $this -> goto_canjia_activity($postValue);

            case "yuyue_ta":
                return $this -> goto_yuyue_ta($postValue);

            case "dingzhi_service":
                return $this -> goto_dingzhi_service($postValue);

            case "gongdi":
                return $this -> goto_gongdi($postValue);

            case "yuyue_designer":
                return $this -> goto_yuyue_designer($postValue);

            case "tiyan_app":
                return $this -> goto_tiyan_app($postValue);

            case "liaojie_more":
                return $this -> goto_liaojie_more($postValue);
        }
    }

    private function goto_dingzhi_service($value) {
        $data["form_type"] = "dingzhi_service";
        $data["name"] = $value["user_name"];
        $data["location"] = $value["user_location"];
        $data["mobile"] = $value["user_phone"];
        $data["designer_id"] = $value["designerId"];
        $data["createtime"] = time();

        return $this -> saveData($data);
    }

    private function goto_yuyue_ta($value) {
        $data["form_type"] = "yuyue_ta";
        $data["name"] = $value["user_name"];
        $data["location"] = $value["user_location"];
        $data["mobile"] = $value["user_phone"];
        $data["designer_id"] = $value["designerId"];
        $data["createtime"] = time();

        return $this -> saveData($data);
    }


    private function goto_yuyue_manager($value) {
        $data["form_type"] = "yuyue_manager";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();

        return $this -> saveData($data);
    }

    private function goto_baojia($value) {
        $data["form_type"] = "baojia";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["createtime"] = time();
        $data["area"] = $value["user_area"];
        $data["door"] = $value["shi"];
        $data["hall"] = $value["wei"];
        $data["toilet"] = $value["wei"];

        return $this -> saveData($data);
    }

    private function goto_canjia_activity($value) {
        $data["form_type"] = "canjia_activity";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();

        return $this -> saveData($data);
    }

    private function goto_gongdi($value) {
        $data["form_type"] = "gongdi";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();

        return $this -> saveData($data);
    }

    private function goto_yuyue_designer($value) {
        $data["form_type"] = "yuyue_designer";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();


        return $this -> saveData($data);
    }

    private function goto_tiyan_app($value) {
        $data["form_type"] = "tiyan_app";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();


        return $this -> saveData($data);
    }

    private function goto_liaojie_more($value) {
        $data["form_type"] = "liaojie_more";
        $data["name"] = $value["user_name"];
        $data["mobile"] = $value["user_phone"];
        $data["location"] = $value["user_location"];
        $data["createtime"] = time();


        return $this -> saveData($data);
    }

    private function  saveData($data) {
        $result = 0 ;
        try  {
            $result = $this->customer -> save($data);
        }catch (Exception $e) {
            echo $e -> getMessage();
        }

        return  $this -> showResult($result);
    }



    private function showResult($result){
        $data["code"] = $result ? 200 : -200;
        $data["message"] = $result ? "操作成功" : "操作失败";
        return json($data);
    }





}
