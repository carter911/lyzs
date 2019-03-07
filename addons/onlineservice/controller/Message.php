<?php
/**
 * 聊天表控制器
 * User: ysongyang
 * Date: 19-2-20
 * Time: 下午8:38
 */

namespace addons\onlineservice\controller;

use addons\onlineservice\controller\logic\Service;
use think\addons\Controller;

class Message extends Controller
{


    public function _initialize()
    {
        parent::_initialize();
    }


    /**
     * 先获取首屏聊天内容
     */
    public function getMessage()
    {
        $params = $this->request->param();
        $message_list = Service::getMessageByVisitor($params['uuid'], $params['group_id'], $params['page'], 10, 2, $params['admin_id']);
        $rest = db('onlineservice_manage')->where(['admin_id' => $params['admin_id']])->field('status,nickname')->find();
        if (count($message_list) > 0) {
            foreach ($message_list as $key => $val) {
                if (date('m-d', strtotime('-1 day')) == date('m-d', $val['createtime'])) {
                    $message_list[$key]['time'] = '昨天 ' . date('H:i', $val['createtime']);
                } elseif (date('m-d') == date('m-d', $val['createtime'])) {
                    $message_list[$key]['time'] = date('H:i', $val['createtime']);
                } else {
                    $message_list[$key]['time'] = date('m-d', $val['createtime']) . ' ' . date('H:i', $val['createtime']);
                }
            }
            $this->result($message_list, 1, $rest['nickname'], 'json');
        } else {
            if (isset($params['admin_id'])) {

                if ($rest['status'] == 'on') {
                    $json['service_name'] = $rest['nickname'];
                    $json['code'] = 1;
                    $json['total'] = 0;
                } else {
                    $json['service_name'] = $rest['nickname'];
                    $json['code'] = 0;
                    $json['total'] = 0;
                }
                $this->result($json, 1, '', 'json');
            }
        }

    }
}