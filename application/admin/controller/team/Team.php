<?php

namespace app\admin\controller\team;

use app\common\controller\Backend;
use think\Db;

/**
 * 设计团队
 *
 * @icon fa fa-circle-o
 */
class Team extends Backend
{

	/**
	 * Team模型对象
	 * @var \app\admin\model\Team
	 */
	protected $model = null;

	public function _initialize()
	{
		parent::_initialize();
		$this->model = new \app\admin\model\Team;
		$this->view->assign("sexList", $this->model->getSexList());
	}

	/**
	 * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
	 * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
	 * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
	 */
	/**
	 * 查看
	 */
	public function index()
	{
		//设置过滤方法
		$this->request->filter(['strip_tags']);
		if ($this->request->isAjax()) {
			//如果发送的来源是Selectpage，则转发到Selectpage
			if ($this->request->request('keyField')) {
				return $this->selectpage();
			}
			list($where, $sort, $order, $offset, $limit) = $this->buildparams();
			$total = $this->model
				->where($where)
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			$style = new Style();
			$door = new Door();
			foreach ($list as $row){
				$row['style_ids'] = $style->get_names($row['style_ids']);
				$row['door_ids'] = $door->get_names($row['door_ids']);
			}
			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();
	}


}

