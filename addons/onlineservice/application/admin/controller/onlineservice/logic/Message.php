<?php
/**
 * Note: 聊天信息的处理.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 15:41:12
 */

namespace app\admin\controller\onlineservice\logic;

use addons\onlineservice\model\OnlineServiceMsg;
use addons\onlineservice\model\OnlineServiceVisitor;
use app\common\controller\Backend;

class Message extends Backend
{

    public static function getVisitorList($group_id = 1, $admin_id)
    {
        $visitor_m = new OnlineServiceVisitor();
        $where['v.group_id'] = $group_id;
        $where['m.admin_id'] = $admin_id;
        $where['v.is_black'] = 0;
        $where['m.sendtime'] = ['between', [strtotime('-7 days'), time() + 3600]];
        $list = $visitor_m->alias('v')->field('v.*')
            ->join('onlineservice_msg m', 'm.uuid = v.uuid')
            ->where($where)->group('v.uuid')->order('v.status desc,m.createtime desc')->select();
        #echo $visitor_m->getLastSql();die;
        unset($where);
        foreach ($list as $k => $v) {
            $where_['uuid'] = $v['uuid'];
            $where_['group_id'] = $group_id;
            $where_['msg_status'] = 0;
            $where_['source_status'] = 1;
            $unreadCount = OnlineServiceMsg::where($where_)->count();
            $list[$k]['unread'] = $unreadCount ? $unreadCount : 0;
            unset($where_['msg_status']);
            unset($where_['source_status']);
            $msg = OnlineServiceMsg::where($where_)->order('createtime desc')->field('content,createtime,type,source_status,file_name')->find();
            if ($msg) {
                $msg['time'] = date('H:i', $msg['createtime']);
            }
            $list[$k]['msg'] = $msg;
        }
        return $list;
    }
}