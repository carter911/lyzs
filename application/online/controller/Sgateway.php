<?php

namespace app\online\controller;

use think\Controller;
use Workerman\Worker;
use GatewayWorker\Gateway;

class Sgateway extends Controller
{
    /**
     * 通讯协议端口
     * @var int
     */
    protected static $gateway_port = 7887;
    /**
     * Text注册协议端口
     * @var int
     */
    protected static $register_port = 1236;

    public function __construct()
    {
        self::$gateway_port = intval(get_addon_config('onlineservice')['port']);
        self::$register_port = intval(get_addon_config('onlineservice')['register_port']);
        // gateway 进程
        $gateway = new Gateway("Websocket://0.0.0.0:" . self::$gateway_port);
        // 设置名称，方便status时查看
        $gateway->name = 'ServiceGateway';
        // 设置进程数，gateway进程数建议与cpu核数相同
        $gateway->count = 4;
        // 分布式部署时请设置成内网ip（非127.0.0.1）
        $gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = 3000;
        // 心跳间隔
        $gateway->pingInterval = 10;
        // 心跳数据
        $gateway->pingData = '{"type":"ping"}';
        // 服务注册地址
        $gateway->registerAddress = '127.0.0.1:' . self::$register_port;

        // 如果不是在根目录启动，则运行runAll方法
        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }


    }
}