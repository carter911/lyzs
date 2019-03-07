<?php
/**
 * Note: 在线客服聊天内容模型.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 14:18:56
 */

namespace addons\onlineservice\model;

use addons\onlineservice\library\Tool;
use think\Model;

class OnlineServiceVisitor extends Model
{

    protected $name = "onlineservice_visitor";
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    /**
     * @param $params
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function Record($params)
    {
        $where['uuid'] = $params['uuid'];
        if (!self::where($where)->find()) {
            $data['uuid'] = $params['uuid'];
            $data['group_id'] = $params['group_id'];
            $data['user_id'] = isset($params['user_id']) ? $params['user_id'] : 0;
            $data['head_img'] = "/assets/addons/onlineservice/img/head_" . rand(1, 2) . ".png";
            $data['createtime'] = time();
            $data['status'] = 'on';
            self::create($data);
            $id = self::getLastInsID();
            self::where($where)->update(['nickname' => '游客#' . $id]);
        }
        if (!OnlineServiceVisitorLog::where($where)->whereTime('createtime', 'today')->find()) {
            $data_['uuid'] = $params['uuid'];
            $data_['joinip'] = $params['join_ip'];
            $data_['screen_size'] = $params['screen_size'];
            $data_['device_info'] = $params['device_info'];
            $data_['user_agent'] = isset($params['user_agent']) ? $params['user_agent'] : '';
            $arr = Tool::getTaobaoArea($data_['joinip']);
            if ($arr) {
                $data_['country'] = $arr['country'];
                $data_['region'] = $arr['region'];
                $data_['city'] = $arr['city'];
            }
            $data_['createtime'] = time();
            OnlineServiceVisitorLog::create($data_);
        }
    }

    /**
     * 更新访客在线或离线
     * @param $uuid
     * @param string $status off 离线 ， on 在线
     * @return $this
     */
    public static function updateOffOn($uuid, $status)
    {
        return self::where('uuid', $uuid)->update(['status' => $status]);
    }

    public static function upStatusOff()
    {
        return self::update(['status' => 'off']);
    }

    public static function changeStatus($uuidA, $status = 'off')
    {
        $where['uuid'] = ['not in', $uuidA];
        return self::where($where)->update(['status' => $status]);
    }

    public static function getInfo($uuid)
    {
        $info = self::where('uuid', $uuid)->field('head_img,nickname,email,mobile,remark,status,is_black')->find();
        $info['log'] = OnlineServiceVisitorLog::where('uuid', $uuid)->order('createtime desc')->find();
        if (isset($info['log']['user_agent'])) {
            $info['log']['agent'] = Tool::getBrowser($info['log']['user_agent']);
        }
        return $info;
    }


}