<?php
/**
 * Note: 在线客服聊天内容模型.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/20 0020 14:18:56
 */

namespace addons\onlineservice\model;

use think\Model;
use addons\onlineservice\library\Tool;

class OnlineServiceMsg extends Model
{

    protected $name = "onlineservice_msg";
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    public function getContentAttr($value, $data)
    {
        #return expression($value);
        return ['val' => $value, 'text' => Tool::expression($value, $data['file_name'])];

    }

    /**
     * 一对多
     * @return \think\model\relation\belongsTo
     */
    public function visitor()
    {
        return $this->belongsTo('OnlineServiceVisitor', 'uuid', 'uuid')->setEagerlyType(0);
    }


    /**
     * 记录访客信息
     * @param $params
     * @return array
     * @throws \think\Exception
     */
    public static function Record($params)
    {
        $addonsConfig = get_addon_config('onlineservice');
        if ($addonsConfig['speak_time'] > 0) {
            $where['uuid'] = $params['uuid'];
            $where['group_id'] = $params['group_id'];
            $where['source_status'] = 1;
            if ($sendtime = self::where($where)->order('id desc')->value('sendtime')) {
                if ($sendtime + intval($addonsConfig['speak_time']) > time()) {
                    return ['error' => 1, 'msg' => $addonsConfig['speak_msg']];
                }
            }
        }

        $data['uuid'] = $params['uuid'];
        $data['user_id'] = $params['user_id'];
        $data['admin_id'] = $params['admin_id'];
        $data['group_id'] = $params['group_id'];
        $data['content'] = $params['content'];
        if (strpos($params['content'], 'uploads') !== false) {
            $data['type'] = 1;
            $ext = pathinfo($params['content'])['extension'];
            if (in_array($ext, ['jpg', 'png', 'bmp', 'jpeg', 'gif'])) $data['type'] = 1;
            if (in_array($ext, ['zip', 'rar', 'xls', 'xlsx', 'doc', 'docx'])) $data['type'] = 2;
        } else {
            $data['type'] = 0;
        }
        $data['file_name'] = isset($params['file_name']) ? $params['file_name'] : '';
        $data['sendtime'] = time();
        $data['createtime'] = time();
        self::create($data);
        $id = self::getLastInsID();
        $type = $data['type'];
        $content = Tool::expression($params['content'], $params['file_name']);
        unset($where);
        $where['uuid'] = $params['uuid'];
        $where['group_id'] = $params['group_id'];
        $where['admin_id'] = $params['admin_id'];
        $where['msg_status'] = 0;
        $unread = self::where($where)->count();
        //昵称和头像
        $userInfo = OnlineServiceVisitor::where('uuid', $params['uuid'])->field('nickname,head_img')->find();
        $retArr = [
            'error' => 0,
            'id' => $id,
            'content' => $content,
            'type' => $type,
            'unread' => $unread,
            'nickname' => $userInfo['nickname'],
            'head_img' => $userInfo['head_img']
        ];
        return $retArr;
    }

    /**
     * 记录管理员信息
     * @param $params
     * @return mixed
     */
    public static function RecodeRet($params)
    {
        $data['uuid'] = $params['uuid'];
        $data['admin_id'] = $params['admin_id'];
        $data['group_id'] = $params['group_id'];
        $data['content'] = $params['content'];
        $data['source_status'] = 2;
        if (strpos($params['content'], 'uploads') !== false) {
            $data['type'] = 1;
            $ext = pathinfo($params['content'])['extension'];
            if (in_array($ext, ['jpg', 'png', 'bmp', 'jpeg', 'gif'])) $data['type'] = 1;
            if (in_array($ext, ['zip', 'rar', 'xls', 'xlsx', 'doc', 'docx'])) $data['type'] = 2;
        } else {
            $data['type'] = 0;
        }
        $data['file_name'] = isset($params['file_name']) ? $params['file_name'] : '';
        $data['sendtime'] = time();
        $data['createtime'] = time();
        self::create($data);
        $id = self::getLastInsID();
        $type = $data['type'];
        $content = Tool::expression($params['content'], $params['file_name']);
        return ['id' => $id, 'content' => $content, 'type' => $type];
    }

    public static function getList($uuid, $group_id, $admin_id, $page = 1, $limit = 5)
    {
        $where['uuid'] = $uuid;
        $where['group_id'] = $group_id;
        $where['admin_id'] = $admin_id;
        $list = self::where($where)->order('createtime desc')->paginate($limit, false, ['page' => $page]);
        #echo self::getLastSql();die;
        return $list;
    }

    /**
     * 更新未读消息为已读
     * @param $uuid
     * @param $group_id
     * @param $source_status
     * @return $this
     */
    public static function updateMsg($uuid, $group_id, $source_status = 1)
    {
        $where['uuid'] = $uuid;
        $where['group_id'] = $group_id;
        $where['source_status'] = $source_status;
        return self::where($where)->update(['msg_status' => 1]);
    }
}