<?php
/**
 * Note: 在线客服聊天内容模型.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 14:18:56
 */

namespace addons\onlineservice\model;

use addons\onlineservice\library\Tool;
use think\Model;

class OnlineServiceManage extends Model
{

    protected $name = "onlineservice_manage";
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
     * 更新在线或离线
     * @param $uuid
     * @param string $status off 离线 ， on 在线
     * @return $this
     */
    public static function updateOffOn($id, $status)
    {
        return self::where('admin_id', $id)->update(['status' => $status]);
    }


    public static function getAdminId()
    {
        $where['status'] = 'on';
        return self::where($where)->value('admin_id');
    }

    public static function upStatusOff()
    {
        return self::update(['status' => 'off']);
    }

}