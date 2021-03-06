<?php

namespace app\index\model;

use think\Db;
use think\Exception;
use think\Model;

class Cases extends Model
{
//    protected $table = 'fa_cases';
//    protected $resultSetType = 'collection';
//
//    protected function initialize()
//    {
//        parent::initialize();
//    }


	/**
	 * 查询每一个风格的案例
	 * @param array $param
	 * @return false|\PDOStatement|string|\think\Collection
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
    public function queryAllKindStyleCases($param=[]){

		$cases_list = Db::name('cases')
			->where($param)->limit(5)->order('id desc')->select();

		foreach ($cases_list as $key => $val){
			$cases_list[$key]['style'] = Db::name('team_style')->where('id','in',$val['team_style_ids'])->find();

			$area_style = DB::name("cases_area") -> where(["id" => $val["cases_area_id"]]) -> find();
			$cases_list[$key]["area_size"] = $area_style["name"];

			$designer = DB::name("team") -> where(["id" => $val["team_team_id"]]) -> find();
			$cases_list[$key]["designer"] = $designer["name"];

			//房型
			$door_style = DB::name("team_door") -> where (["id" => array("in", $val["team_door_ids"])]) -> select();
			if($door_style) {
				$doorName = "" ;
				foreach($door_style as $door) {
					$doorName = $door["name"]." ".$doorName;
				}
				$cases_list[$key]["door_name"] = $doorName;
			}



			$cases_list[$key]['images'] = [];
			if(!empty($val['images'])){
				$cases_list[$key]['images'] = explode(",",$val['images']);
			}
			$countNum = count($cases_list[$key]['images']);

			if($countNum<5){
				for ($num=1;$num<=(5-$countNum);$num++){
					$cases_list[$key]['images'][] = $val['image'];
				}
			}
		}

        return $cases_list;
    }


    /**
     * 实景案例
     *
     * @param int $caseStyle  户型
     * @param int $doorStyle  风格
     * @param int $areaStyle   面积
     * @param String $keywords
     * @param int $status
     * @param int $pageIndex
     * @param int $pageSize
     *
     * @return array
     *
     * @throws Exception
     */
    public function queryStyleCase($caseStyle=-1, $doorStyle=-1, $areaStyle=-1, $keywords = "", $status=1, $pageIndex=0 , $pageSize=6) {
        $result = [] ;
        $whereParam = "status = ".$status." ";

        $index = 1 ;
        if($caseStyle > 0) {
            $whereParam= "FIND_IN_SET(".$caseStyle.",team_door_ids)";
            $index = $index + 1;
        }

        if($doorStyle > 0) {
            $whereParam= $whereParam. ($index > 0 ? " AND " : "") . "FIND_IN_SET(".$doorStyle.",team_style_ids)";
            $index = $index + 1;
        }

        if($areaStyle > 0) {
            $whereParam = $whereParam . ($index > 0 ? " AND " : ""). " cases_area_id = ".$areaStyle;
            $index = $index + 1;
        }

        if(isset($keywords) && $keywords) {
            $whereParam = $whereParam . ($index > 0 ? " AND " : ""). "name like '%".$keywords."%'";
        }


        $pageCount = DB::name("cases") -> where($whereParam) -> count();
        $result["page"] = $this->get_page($pageIndex,$pageCount);

        $resultCase = DB::name("cases") -> where($whereParam) -> limit(max(0,($pageIndex - 1) * $pageSize), $pageSize) -> select();

        $caseArray = array();
        foreach ($resultCase as $case) {
            $style = Db::name('cases')->where(["team_style_ids" => $case["id"]]) -> find();
            $case["style_id"] = $style["id"];
            $case["style_name"] = $style["name"];

            //房型
            $door_style = DB::name("team_door") -> where (["id" => array("in", $case["team_door_ids"])]) -> select();
            if($door_style) {
                $doorName = "" ;
                foreach($door_style as $door) {
                    $doorName = $door["name"]." ".$doorName;
                }
                $case["door_name"] = $doorName;
            }

            //建筑面积
            $area_style = DB::name("cases_area") -> where(["id" => $case["cases_area_id"]]) -> find();
            $case["area_size"] = $area_style["name"];

            //设计师
            $designer = DB::name("team") -> where(["id" => $case["team_team_id"]]) -> find();
            $case["designer"] = $designer["name"];
            $case["designer_image"] = $designer["image"];

            $caseArray[] = $case;
        }

        $result["caseList"] = $caseArray;

        return $result;
    }


    private function get_page($pageIndex, $totalSize){

        $lastPage = $totalSize % 6 == 0 ? intval($totalSize / 6) : intval($totalSize / 6) + 1;
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




}