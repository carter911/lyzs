<?php
/**
 * Note: 在线客服问候语.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 14:18:56
 */

namespace addons\onlineservice\model;

use think\Model;

class OnlineServiceGreeting extends Model
{

    protected $name = "onlineservice_greeting";
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    public function getCreateTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 获取设置的默认问候语
     * @param $admin_id
     * @param $type
     * @param $uuid
     * @return mixed
     */
    public static function getGreetingContent($admin_id, $type, $uuid)
    {
        #如果访客跟管理员1个小时内有通话则不进行问候语回复
        if ($type == 'on') {
            $sendTime = (new OnlineServiceMsg())->where(['uuid' => $uuid, 'admin_id' => $admin_id])->order('sendtime desc')->value('sendtime');
            if (isset($sendTime)) {
                if (($sendTime + 3600) > time()) {
                    return null;
                }
            }
        }
        $where['admin_id'] = $admin_id;
        $where['type'] = $type;
        $where['is_def'] = 1;
        $where['status'] = 'normal';
        return self::where($where)->value('content');
    }

}