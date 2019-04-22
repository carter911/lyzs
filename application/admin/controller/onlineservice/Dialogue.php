<?php
/**
 * Note: 对话管理.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 11:36:02
 */

namespace app\admin\controller\onlineservice;

use addons\onlineservice\controller\logic\Service;
use addons\onlineservice\library\Tool;
use addons\onlineservice\model\OnlineServiceManage;
use addons\onlineservice\model\OnlineServiceTag;
use addons\onlineservice\model\OnlineServiceVisitor;
use app\admin\controller\onlineservice\logic\Message;
use app\common\controller\Backend;

class Dialogue extends Backend
{
    /**
     * LeeSign模型对象
     */
    protected $model = null;

    protected $searchFields = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->assign('service_group_id', 1);
        $this->assign('admin_id', $this->auth->id);
        $this->assign('addon_config', get_addon_config('onlineservice'));
        $this->assign('isHttps', Tool::is_https());
    }


	/**
	 * 无需鉴权的方法,但需要登录
	 * @var array
	 */
	protected $noNeedRight = ['getuserlist','getMessage','getSetting','changeFileds','changeBlack','getBlackList','getquickReply','addquickReply','delquickReply'];


	/**
     *  查看
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $admin_id = $this->auth->id;
        $ret = OnlineServiceManage::where('admin_id', $admin_id)->find();
        if (!$ret) {
            $this->error('请到在线客服的其他设置中配置客服管理员账号信息!', url('admin/onlineservice/set'));
        }
        return $this->view->fetch();
    }

    public function getuserlist()
    {
        $group_id = $this->request->param('group_id', 1);
        $list = Message::getVisitorList($group_id, $this->auth->id);
        if ($list) {
            $json['code'] = 0;
            $json['data'] = $list;
        } else {
            $json['code'] = 1;
            $json['data'] = [];
        }
        return json($json);
    }


    /**
     * 先获取首屏聊天内容
     */
    public function getMessage()
    {
        $params = $this->request->param();
        $message_list = Service::getMessageByVisitor($params['uuid'], $params['group_id'], $params['page'], 10, 1, $params['admin_id']);
        foreach ($message_list as $key => $val) {
            if (date('m-d', strtotime('-1 day')) == date('m-d', $val['createtime'])) {
                $message_list[$key]['time'] = '昨天 ' . date('H:i', $val['createtime']);
            } elseif (date('m-d') == date('m-d', $val['createtime'])) {
                $message_list[$key]['time'] = date('H:i', $val['createtime']);
            } else {
                $message_list[$key]['time'] = date('m-d', $val['createtime']) . ' ' . date('H:i', $val['createtime']);
            }
        }
        $this->result($message_list, 1, '', 'json');
    }

    public function getSetting($uuid)
    {
        $info = OnlineServiceVisitor::getInfo($uuid);
        $this->result($info, 1, '', 'json');
    }

    public function changeFileds()
    {
        $params = $this->request->param();
        $where['uuid'] = $params['uuid'];
        $update = [];
        if (isset($params['nickname'])) {
            $update['nickname'] = $params['nickname'];
        }
        if (isset($params['email'])) {
            $update['email'] = $params['email'];
        }
        if (isset($params['mobile'])) {
            $update['mobile'] = $params['mobile'];
        }
        if (isset($params['remark'])) {
            $update['remark'] = $params['remark'];
        }
        if (!$update) return 0;
        return OnlineServiceVisitor::where($where)->update($update);
    }

    public function changeBlack($uuid, $is_black = 1)
    {
        return OnlineServiceVisitor::where('uuid', $uuid)->update(['is_black' => $is_black]);
    }

    public function getBlackList()
    {
        $where['is_black'] = 1;
        $list = OnlineServiceVisitor::where($where)->select();
        $this->result($list, 1, '', 'json');
    }

    public function getquickReply()
    {
        $order['createtime'] = 'desc';
        $list = OnlineServiceTag::order($order)->select();
        $this->result($list, 1, '', 'json');
    }

    public function addquickReply()
    {
        $params = $this->request->param();
        $where['tag_name'] = $params['tag_name'];
        $where['group_id'] = $params['group_id'];
        if (OnlineServiceTag::where($where)->find()) {
            $this->result('', 0, '标签已存在', 'json');
        }
        $ret = OnlineServiceTag::create($params);
        $this->result($ret, 1, '添加成功', 'json');
    }

    public function delquickReply($id)
    {
        $where['id'] = $id;
        $ret = OnlineServiceTag::where($where)->delete();
        $this->result($ret, 1, '删除成功', 'json');
    }
}
