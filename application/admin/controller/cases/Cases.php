<?php

namespace app\admin\controller\cases;

use app\common\controller\Backend;

/**
 * 案例时光机
 *
 * @icon fa fa-circle-o
 */
class Cases extends Backend
{
    
    /**
     * Cases模型对象
     * @var \app\admin\model\Cases
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Cases;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("isKjlList", $this->model->getIsKjlList());
        $this->view->assign("isHomeList", $this->model->getIsHomeList());
        $this->view->assign("isGxjList", $this->model->getIsGxjList());
        $this->view->assign("isGddzList", $this->model->getIsGddzList());
        $this->view->assign("isYjList", $this->model->getIsYjList());
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
			list($where, $sort, $order, $offset, $limit) = $this->buildparams("id,name");
			$total = $this->model
				->where($where)
				->order($sort, $order)
				->count();

			$list = $this->model
				->where($where)
				->order($sort, $order)
				->limit($offset, $limit)
				->select();

			$list = collection($list)->toArray();
			$result = array("total" => $total, "rows" => $list);

			return json($result);
		}
		return $this->view->fetch();
	}

}
