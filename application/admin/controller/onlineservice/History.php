<?php
/**
 * Note: 历史记录.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 11:36:02
 */

namespace app\admin\controller\onlineservice;

use addons\onlineservice\model\OnlineServiceVisitorLog;
use app\common\controller\Backend;

class History extends Backend
{
    protected $relationSearch = true;


    /**
     * @var \app\admin\model\User
     */
    protected $model = null;
    protected $rulelist = [];
    protected $multiFields = '';
    protected $searchFields = "visitor.nickname";

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('addons\onlineservice\model\OnlineServiceMsg');
    }


    public function index()
    {

        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->with('visitor')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->with('visitor')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            foreach ($list as $key => $val) {
                $list[$key]['user_name'] = db('user')->where('id', $val['user_id'])->column('nickname');
                $list[$key]['admin_name'] = db('admin')->where('id', $val['admin_id'])->column('nickname');
                $list[$key]['sendtime'] = date('Y-m-d H:i:s', $val['sendtime']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v) {
                $delIds[] = $v;
            }
            $delIds = array_unique($delIds);
            $count = $this->model->where('id', 'in', $delIds)->delete();
            if ($count) {
                # 这里需要将内容全部设置为隐藏
                $this->success();
            }
        }
        $this->error();
    }

    /**
     * 详情
     */
    public function detail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        $info = OnlineServiceVisitorLog::where('uuid',$row['uuid'])->order('createtime desc')->find();
        $this->view->assign("row", $info->toArray());
        return $this->view->fetch();
    }

}