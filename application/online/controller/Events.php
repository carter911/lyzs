<?php
/**
 * @notes 处理业务的类
 * @author: ysongyang <ysongyang@qq.com>
 * @date: 2019/2/20 11:49
 * @site: https://zz1.com.cn
 * @copyright: copyright 2018/1/6 ysongyang all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace app\online\controller;

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

use addons\onlineservice\controller\logic\Service;
use addons\onlineservice\model\OnlineServiceGreeting;
use addons\onlineservice\model\OnlineServiceManage;
use addons\onlineservice\model\OnlineServiceMsg;
use addons\onlineservice\model\OnlineServiceVisitor;
use GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;

class Events
{

    public static function onWorkerStart($businessWorker)
    {
        if ($businessWorker->id == 0) {
            /*$time_interval = 5;
            Timer::add($time_interval, function () use ($businessWorker) {
                echo "businessWorker is id " . $businessWorker->id . "\n";
            });*/
        }
    }

    /**
     * @notes 有消息时
     * @author: ysongyang <ysongyang@qq.com>
     * @param $client_id
     * @param $message
     * @return null
     * @throws \Exception
     */
    public static function onMessage($client_id, $message)
    {
        //生产环境请注释
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:" . json_encode($_SESSION) . " onMessage:" . $message . "\n";
        $message_data = json_decode($message, TRUE);
        if (!$message_data) {
            return NULL;
        }
        $group_id = isset($message_data['group_id']) ? $message_data['group_id'] : 1;
        $_SESSION['group_id'] = $group_id;
        $uuid = isset($message_data['uuid']) ? $message_data['uuid'] : 0;
        $admin_id = isset($message_data['admin_id']) ? $message_data['admin_id'] : 0;

        // 根据类型执行不同的业务
        switch ($message_data['type']) {
            // 客户端回应服务端的心跳
            case 'pong':
                break;
            //管理员上线登录
            case 'admin_login':
                if (isset($admin_id) && $admin_id !== 'null') {
                    #更新管理员用户在线
                    OnlineServiceManage::updateOffOn($admin_id, 'on');
                    $_SESSION['admin_id'] = $admin_id;
                    Gateway::bindUid($client_id, $admin_id);
                    Gateway::joinGroup($client_id, $group_id); //加入某个组

                    #广播通知大家
                    $res_message['type'] = 'airing';
                    $res_message['admin_id'] = $admin_id;
                    Gateway::sendToAll(json_encode($res_message));
                }
                break;
            case 'connect':
                $secret_key = isset($message_data['secret_key']) ? $message_data['secret_key'] : null;
                #目前是明文传输，后期改加密
                if (!Service::checkSecretKey($secret_key)) {
                    $re_message['type'] = 'error';
                    $re_message['msg'] = "无效的密钥,请检查配置文件";
                    $re_message['code'] = 0;
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                if (!$uuid) {
                    $re_message['type'] = 'error';
                    $re_message['msg'] = "uuid 参数有误!";
                    $re_message['code'] = 0;
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                self::checkUuidOnline();
                if (isset($admin_id) && $admin_id !== 'null') {
                    $time = date('H:i', time());
                    if (Gateway::isUidOnline($admin_id)) {
                        //如果有配置在线问候语则对客户端进行回复
                        $timer_id = Timer::add(1, function () use (&$timer_id, &$count, &$admin_id, &$time, &$client_id, &$uuid) {
                            $greetingInfo = OnlineServiceGreeting::getGreetingContent($admin_id, 'on', $uuid);
                            if ($greetingInfo) {
                                $re_message['type'] = 'send_ok';
                                $re_message['time'] = $time;
                                $re_message['content'] = $greetingInfo;
                                Gateway::sendToClient($client_id, json_encode($re_message));
                            }
                            Timer::del($timer_id);
                        });

                    } else {
                        //如果有配置离线问候语则对客户端进行回复
                        $timer_id = Timer::add(1, function () use (&$timer_id, &$count, &$admin_id, &$time, &$client_id, &$uuid) {
                            $greetingInfo = OnlineServiceGreeting::getGreetingContent($admin_id, 'off', $uuid);
                            if ($greetingInfo) {
                                $re_message['type'] = 'send_ok';
                                $re_message['time'] = $time;
                                $re_message['content'] = $greetingInfo;
                                Gateway::sendToClient($client_id, json_encode($re_message));
                            }
                            Timer::del($timer_id);
                        });
                    }
                }
                #新用户上线，需要获取当前在线的管理员 返回管理员id
                $ret = OnlineServiceManage::getAdminId();
                if ($ret && $admin_id == 'null') {
                    $res_message['type'] = 'airing';
                    $res_message['admin_id'] = $ret;
                    Gateway::sendToClient($client_id, json_encode($res_message));
                }

                $_SESSION['uuid'] = $uuid;
                #更新用户上线
                OnlineServiceVisitor::updateOffOn($uuid, 'on');
                Gateway::bindUid($client_id, $uuid);
                Gateway::joinGroup($client_id, $group_id); //加入某个组
                #记录用户信息online_service_visitor
                unset($message_data['type']);
                unset($message_data['secret_key']);
                OnlineServiceVisitor::Record($message_data);
                #广播通知大家
                $res_message['type'] = 'online';
                Gateway::sendToAll(json_encode($res_message));
                break;
            case 'say':
                if (!$uuid) {
                    $re_message['type'] = 'error';
                    $re_message['msg'] = "uuid 参数有误!";
                    $re_message['code'] = 0;
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                if (OnlineServiceVisitor::where(['uuid' => $uuid, 'is_black' => 1])->find()) {
                    $re_message['type'] = 'error';
                    $re_message['msg'] = "您已经被拖入黑名单，对方无法接收你的信息！";
                    $re_message['code'] = 1;
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                #记录用户信息online_service_msg
                $res = OnlineServiceMsg::Record($message_data);
                if (isset($res) && $res['error'] == 1) {
                    $re_message['type'] = 'error';
                    $re_message['code'] = 1;
                    $re_message['msg'] = $res['msg'];
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                $time = date('H:i', time());
                $re_message['type'] = 'say_ok';
                $re_message['time'] = $time;
                $re_message['content'] = $res['content'];
                Gateway::sendToClient($client_id, json_encode($re_message));
                #如果是客户端发言这里要通知服务端
                if (isset($admin_id) && $admin_id !== 'null') {
                    if (Gateway::isUidOnline($admin_id)) {
                        #管理员在线，更新我发送的消息为已读
                        //OnlineServiceMsg::updateMsg($uuid, $group_id, 1);
                        $client_id_a = Gateway::getClientIdByUid($admin_id);
                        foreach ($client_id_a as $val) {
                            $re_message['type'] = 'send_ok';
                            $re_message['time'] = $time;
                            $re_message['content'] = $res['content'];
                            $re_message['content_type'] = $res['type'];
                            $re_message['from_uid'] = $uuid;
                            $re_message['unread'] = $res['unread'];
                            $re_message['nickname'] = $res['nickname'];
                            $re_message['head_img'] = $res['head_img'];
                            Gateway::sendToClient($val, json_encode($re_message));
                        }
                    } else {
                        $re_message['type'] = 'send_ok';
                        $re_message['time'] = $time;
                        $re_message['content'] = '当前客服不在线，待客服上线后为您处理问题！';
                        Gateway::sendToClient($client_id, json_encode($re_message));
                    }
                }
                break;
            //如果是管理员回复信息  uuid  content   group_id   admin_id
            case 'say_user':
                if (!$uuid) {
                    $re_message['type'] = 'error';
                    $re_message['msg'] = "uuid 参数有误!";
                    $re_message['code'] = 0;
                    Gateway::sendToClient($client_id, json_encode($re_message));
                    return NULL;
                }
                #记录用户信息online_service_msg
                $res = OnlineServiceMsg::RecodeRet($message_data);
                $time = date('H:i', time());
                $re_message['type'] = 'say_ok';
                $re_message['time'] = $time;
                $re_message['content'] = $res['content'];
                $re_message['content_type'] = $res['type'];
                Gateway::sendToClient($client_id, json_encode($re_message));
                if ($res) {
                    if (Gateway::isUidOnline($uuid)) {
                        #客户端在线 更新我发送的消息为已读
                        OnlineServiceMsg::updateMsg($uuid, $group_id, 2);
                        $client_id_a = Gateway::getClientIdByUid($uuid);
                        foreach ($client_id_a as $val) {
                            $re_message['type'] = 'send_ok';
                            $re_message['time'] = date('H:i', time());
                            $re_message['content'] = $res['content'];
                            Gateway::sendToClient($val, json_encode($re_message));
                        }
                    }
                }
                break;
            case 'close':
                $uuid = $uuid ? $uuid : $_SESSION['uuid'];
                OnlineServiceVisitor::updateOffOn($uuid, 'off');
                Gateway::unbindUid($client_id, $uuid);
                unset($_SESSION['uuid']);
                Gateway::sendToAll(json_encode(['type' => 'onclose']));
                break;
        }
    }

    public static function checkUuidOnline()
    {
        $uidList = Gateway::getAllUidList();
        $uuidA = [];
        foreach ($uidList as $key => $val) {
            if (strlen($val) == 36) {
                $uuidA[] = $val;
            }
        }
        OnlineServiceVisitor::changeStatus($uuidA, 'off');
    }

    /**
     * 当客户端断开连接时
     * @param integer $client_id 客户端id
     */
    public static function onClose($client_id)
    {
        if (isset($_SESSION['uuid'])) {
            $uuid = $_SESSION['uuid'];
            OnlineServiceVisitor::updateOffOn($uuid, 'off');
            Gateway::unbindUid($client_id, $uuid);
            unset($_SESSION['uuid']);
            Gateway::sendToAll(json_encode(['type' => 'onclose']));
        }

        if (isset($_SESSION['admin_id'])) {
            #更新用户离线
            $admin_id = $_SESSION['admin_id'];
            OnlineServiceManage::updateOffOn($admin_id, 'off');
            Gateway::unbindUid($client_id, $admin_id);
            unset($_SESSION['admin_id']);

            $res_message['type'] = 'unairing';
            $res_message['admin_id'] = $admin_id;
            Gateway::sendToAll(json_encode($res_message));
        }
        Gateway::leaveGroup($client_id, $_SESSION['group_id']);
        //生产环境请注释
        echo "onClose : client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id\n";

    }

    /**
     * 客户端停止时批量下线
     * @param $businessWorker
     */
    public static function onWorkerStop($businessWorker)
    {
        //生产环境请注释
        //echo "WorkerStop\n";
        OnlineServiceVisitor::upStatusOff();
    }

}