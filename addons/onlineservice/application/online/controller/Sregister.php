<?php
namespace app\online\controller;

use Workerman\Worker;
use GatewayWorker\Register;
use think\addons\Controller;

class Sregister extends Controller{

    /**
     * text协议端口
     * @var int
     */
    protected static $register_port = 1236;

    public function __construct(){
        self::$register_port = intval(get_addon_config('onlineservice')['register_port']);
        // register 服务必须是text协议
        $register = new Register('text://0.0.0.0:'.self::$register_port);

        // 如果不是在根目录启动，则运行runAll方法
        if(!defined('GLOBAL_START'))
        {
            Worker::runAll();
        }
    }
}