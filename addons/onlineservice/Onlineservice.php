<?php

namespace addons\onlineservice;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Onlineservice extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {

        $menu = [
            [
                'name' => 'onlineservice',
                'title' => '在线客服',
                'icon' => 'fa fa-comments',
                'sublist' => [
                    [
                        'name' => 'onlineservice/dialogue',
                        'title' => '对话管理',
                        'icon' => 'fa fa-whatsapp',
                        'sublist' => [
                            ['name' => 'onlineservice/dialogue/index', 'title' => '查看'],
                        ]
                    ],
                    [
                        'name' => 'onlineservice/history',
                        'title' => '历史记录',
                        'icon' => 'fa fa-history',
                        'sublist' => [
                            ['name' => 'onlineservice/history/index', 'title' => '查看'],
                            ['name' => 'onlineservice/history/del', 'title' => '删除'],
                            ['name' => 'onlineservice/history/detail', 'title' => '访客详情'],
                        ]
                    ],
                    [
                        'name' => 'onlineservice/deploy',
                        'title' => '一键部署',
                        'icon' => 'fa fa-bug',
                        'sublist' => [
                            ['name' => 'onlineservice/deploy/index', 'title' => '查看'],
                        ]
                    ],
                    [
                        'name' => 'onlineservice/set',
                        'title' => '其他设置',
                        'icon' => 'fa fa-gear',
                        'sublist' => [
                            ['name' => 'onlineservice/set/index', 'title' => '查看'],
                        ]
                    ],
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('onlineservice');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('onlineservice');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('onlineservice');
        return true;
    }

    /**
     * 实现钩子方法
     * @return mixed
     */
    public function testhook($param)
    {
        // 调用钩子时候的参数信息
        print_r($param);
        // 当前插件的配置信息，配置信息存在当前目录的config.php文件中，见下方
        print_r($this->getConfig());
        // 可以返回模板，模板文件默认读取的为插件目录中的文件。模板名不能为空！
        //return $this->fetch('view/info');
    }

}
