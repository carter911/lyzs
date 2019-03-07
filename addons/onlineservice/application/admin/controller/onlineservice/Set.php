<?php
/**
 * Note: 其他设置.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 11:36:02
 */

namespace app\admin\controller\onlineservice;

use addons\onlineservice\library\Tool;
use addons\onlineservice\model\OnlineServiceGreeting;
use addons\onlineservice\model\OnlineServiceManage;
use app\admin\model\Admin;
use app\common\controller\Backend;

class Set extends Backend
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['info', 'info_add', 'info_del', 'info_edit', 'greeting', 'setDef', 'unDef', 'greeting_add', 'greeting_edit', 'greeting_del'];
    /**
     * LeeSign模型对象
     */
    protected $model = null;

    protected $searchFields = '';

    public function _initialize()
    {
        parent::_initialize();

    }


    /**
     * 查看
     * @return string|\think\response\Json
     */
    public function index()
    {

        return $this->view->fetch('');
    }

    public function info()
    {
        $manage_m = new OnlineServiceManage();
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $manage_m
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $manage_m
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            /*foreach ($list as $key => $val) {
                $list[$key]['user_name'] = db('admin')->where('id', $val['user_id'])->column('nickname');
            }*/
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 问候语
     * @return string|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function greeting()
    {
        $manage_m = new OnlineServiceGreeting();
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $manage_m
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $manage_m
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

    public function setDef($type, $ids)
    {
        $manage_m = new OnlineServiceGreeting();
        $where['type'] = $type;
        $where['id'] = ['not in', $ids];
        $manage_m->where($where)->update(['is_def' => 0]);
        $manage_m->where('id', $ids)->update(['is_def' => 1]);
        $this->success('设置成功');
    }

    public function unDef($ids)
    {
        $manage_m = new OnlineServiceGreeting();
        $manage_m->where('id', $ids)->update(['is_def' => 0]);
        $this->success('取消默认成功');
    }

    /**
     * 添加
     */
    public function info_add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $manage_m = new OnlineServiceManage();
                if ($manage_m->where('admin_id', $params['admin_id'])->find()) {
                    $this->error('该客服已存在,请勿重复添加!');
                }
                $info = db('admin')->where('id', $params['admin_id'])->find();
                $params['username'] = $info['username'];
                $params['nickname'] = isset($params['nickname']) ? $params['nickname'] : $info['nickname'];
                $params['password'] = $info['password'];
                $params['head_img'] = $info['avatar'];
                $params['status'] = 'off';
                $params['createtime'] = time();
                $result = $manage_m->allowField(true)->save($params);
                if ($result === false) {
                    $this->error($manage_m->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $group_arr = ['1' => '默认分组'];
        return $this->view->fetch('', ['group_arr' => $group_arr]);
    }

    /**
     * 添加
     */
    public function greeting_add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $manage_m = new OnlineServiceGreeting();
                $params['admin_id'] = $this->auth->id;
                $result = $manage_m->allowField(true)->save($params);
                if ($result === false) {
                    $this->error($manage_m->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $type_arr = ['off' => '离线专用', 'on' => '在线专用'];
        return $this->view->fetch('', ['type_arr' => $type_arr]);
    }

    /**
     * 编辑
     */
    public function info_edit($ids = NULL)
    {
        $manage_m = new OnlineServiceManage();
        $row = $manage_m->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $result = $row->save($params);
                if ($result === false) {
                    $this->error($row->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", $row);
        $group_arr = ['1' => '默认分组'];
        return $this->view->fetch('', ['group_arr' => $group_arr]);
    }

    /**
     * 编辑
     */
    public function greeting_edit($ids = NULL)
    {
        $manage_m = new OnlineServiceGreeting();
        $row = $manage_m->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $result = $row->save($params);
                if ($result === false) {
                    $this->error($row->getError());
                }
                $this->success();
            }
            $this->error();
        }
        $this->view->assign("row", $row);
        $type_arr = ['off' => '离线专用', 'on' => '在线专用'];
        return $this->view->fetch('', ['type_arr' => $type_arr]);
    }

    /**
     * 删除
     */
    public function info_del($ids = "")
    {
        if ($ids) {
            $manage_m = new OnlineServiceManage();
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v) {
                $delIds[] = $v;
            }
            $delIds = array_unique($delIds);
            $count = $manage_m->where('id', 'in', $delIds)->delete();
            if ($count) {
                # 这里需要将内容全部设置为隐藏
                $this->success();
            }
        }
        $this->error();
    }

    /**
     * 删除
     */
    public function greeting_del($ids = "")
    {
        if ($ids) {
            $manage_m = new OnlineServiceGreeting();
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v) {
                $delIds[] = $v;
            }
            $delIds = array_unique($delIds);
            $count = $manage_m->where('id', 'in', $delIds)->delete();
            if ($count) {
                # 这里需要将内容全部设置为隐藏
                $this->success();
            }
        }
        $this->error();
    }

}