<?php
/**
 * workerman + GatewayWorker
 * 此文件只能在Linux运行
 * run with command
 * php start.php start -d
 * @notes 处理业务的类
 * @author: ysongyang <ysongyang@qq.com>
 * @date: 2019/2/20 11:49
 * @site: https://zz1.com.cn
 * @copyright: copyright 2018/1/6 ysongyang all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
ini_set('display_errors', 'on');
if (strpos(strtolower(PHP_OS), 'win') === 0) {
    exit("send_start.php not support windows, please use start_for_win.bat\n");
}
//检查扩展
if (!extension_loaded('pcntl')) {
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
if (!extension_loaded('posix')) {
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
define('APP_PATH', __DIR__ . '/../../application/');
define('BIND_MODULE', 'online/Gate');
// 加载框架引导文件
require __DIR__ . '/../../thinkphp/start.php';