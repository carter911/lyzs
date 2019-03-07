<?php
/**
 * Note: 一键部署.
 * User: ysongyang <49271743@qq.com>
 * Time: 2019/2/21 0021 11:36:02
 */

namespace app\admin\controller\onlineservice;

use addons\onlineservice\library\Tool;
use addons\onlineservice\model\OnlineServiceManage;
use app\common\controller\Backend;

class Deploy extends Backend
{
    /**
     * LeeSign模型对象
     */
    protected $model = null;

    protected $searchFields = '';

    public function _initialize()
    {
        parent::_initialize();

    }


    /**
     * 查看
     */
    public function index()
    {
        $manage_m = new OnlineServiceManage();
        if (!$manage_m->find()) {
            $this->error('请到在线客服的其他设置中配置客服管理员账号信息!', url('admin/onlineservice/set'));
        }
        return $this->view->fetch('', ['url' => Tool::get_url(), 'group_id' => 1]);
    }


}