<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;

class Brandstr extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
    	$this->assign('title','品牌实力-岭艺装饰');
        return $this->view->fetch('index');
    }

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

}
