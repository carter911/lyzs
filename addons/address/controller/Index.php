<?php

namespace addons\address\controller;

use think\addons\Controller;

class Index extends Controller
{

    public function index()
    {
        $this->error("当前插件暂无前台页面");
    }

    public function select()
    {
        $config = get_addon_config('address');
        $lat = $this->request->get('lat', $config['lat']);
        $lng = $this->request->get('lng', $config['lng']);
        $this->assign('lat', $lat);
        $this->assign('lng', $lng);
        $this->assign('scale', $config['scale']);
        $this->assign('location', $config['location']);
        return $this->fetch();
    }

}
