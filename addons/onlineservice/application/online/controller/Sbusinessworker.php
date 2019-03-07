<?php
namespace app\online\controller;

use Workerman\Worker;
use GatewayWorker\BusinessWorker;
use think\addons\Controller;

class Sbusinessworker extends Controller{

    /**
     * Text注册协议端口
     * @var int
     */
    protected static $register_port = 1236;

    public function __construct(){
        self::$register_port = intval(get_addon_config('onlineservice')['register_port']);
        // bussinessWorker 进程
        $worker = new BusinessWorker();
        // worker名称
        $worker->name = 'ServiceBusinessWorker';
        // bussinessWorker进程数量
        $worker->count = 4;
        // 服务注册地址
        $worker->registerAddress = '127.0.0.1:'.self::$register_port;
        //设置处理业务的类,此处制定Events的命名空间
        $worker->eventHandler = 'app\online\controller\Events';

        // 如果不是在根目录启动，则运行runAll方法
        if(!defined('GLOBAL_START'))
        {
            Worker::runAll();
        }
    }
}