<?php
/**
 * Note: 在线客服聊天内容模型.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 14:18:56
 */

namespace addons\onlineservice\model;

use think\Model;

class OnlineServiceVisitorLog extends Model
{

    protected $name = "onlineservice_visitor_log";
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];
}