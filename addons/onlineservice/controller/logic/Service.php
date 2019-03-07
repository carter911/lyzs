<?php
/**
 * Note: 聊天客服的逻辑处理.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 12:57:20
 */

namespace addons\onlineservice\controller\logic;

use addons\onlineservice\model\OnlineServiceMsg;
use think\addons\Controller;

class Service extends Controller
{

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 验证通讯密钥
     * @param $secret_key
     * @return bool
     */
    public static function checkSecretKey($secret_key)
    {
        return get_addon_config('onlineservice')['secret_key'] == $secret_key ? true : false;
    }

    /**
     *  获取聊天信息
     * @param $uuid
     * @param $group_id
     * @param $page
     * @param int $limit
     * @param int $source_status
     * @param $admin_id
     * @return \think\Paginator
     */
    public static function getMessageByVisitor($uuid, $group_id, $page, $limit = 10, $source_status = 2, $admin_id)
    {
        #更新管理员发过来的消息为已读
        OnlineServiceMsg::updateMsg($uuid, $group_id, $source_status);
        return OnlineServiceMsg::getList($uuid, $group_id, $admin_id,$page, $limit);
    }
}