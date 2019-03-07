<?php
/**
 * 在线聊天首页控制器
 */

namespace addons\onlineservice\controller;

use addons\onlineservice\library\Tool;
use addons\onlineservice\model\OnlineServiceManage;
use fast\Random;
use think\addons\Controller;
use think\Config;

class Index extends Controller
{

    protected $model = null;
    protected $config = null;
    protected $rand_admin_id = 0; //随机分配的admin_id

    public function _initialize()
    {
        parent::_initialize();
        $manage_m = new OnlineServiceManage();
        $rest = $manage_m->orderRaw('status desc,rand()')->limit(1)->find();
        $this->config = get_addon_config('onlineservice');
        $this->assign('user_id', $this->auth->id ? $this->auth->id : 0);
        $this->assign('config', $this->config);
        $this->assign('user_agent', request()->server('HTTP_USER_AGENT'));
        $this->assign('join_ip', request()->ip());
        $this->rand_admin_id = isset($rest) ? $rest['admin_id'] : 0;
    }

    /**
     * 官方演示
     * @return mixed
     */
    public function index()
    {
        if (!$this->rand_admin_id) {
            $this->error('请先配置在线客服管理员信息!');
        }
        return $this->fetch('', ['group_id' => 1, 'admin_id' => $this->rand_admin_id]);
    }

    /**
     * 聊天客服
     * @return mixed
     */
    public function deploy()
    {
        $group_id = $this->request->param('group_id', 1); //获取分组
        return $this->fetch('', ['uuid' => Tool::get_uuid(), 'group_id' => $group_id, 'admin_id' => $this->rand_admin_id, 'isHttps' => Tool::is_https()]);
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $file = $this->request->file('file');
        if (empty($file)) {
            $json['code'] = 1;
            $json['url'] = '';
            $json['msg'] = '未上传文件或超出服务器上传限制';
            return json($json);
        }

        $upload = Config::get('upload');

        $upload['maxsize'] = isset($this->config['maxsize']) ? $this->config['maxsize'] : $upload['maxsize']; //获取插件配置的上传大小
        $upload['mimetype'] = isset($this->config['mimetype']) ? $this->config['mimetype'] : $upload['mimetype']; //获取插件配置的上传大小

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();

        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix ? $suffix : 'file';
        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);
        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            $json['code'] = 1;
            $json['url'] = '';
            $json['msg'] = '上传的文件格式受限';
            return json($json);
        }
        $replaceArr = [
            '{year}' => date("Y"),
            '{mon}' => date("m"),
            '{day}' => date("d"),
            '{hour}' => date("H"),
            '{min}' => date("i"),
            '{sec}' => date("s"),
            '{random}' => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}' => $suffix,
            '{.suffix}' => $suffix ? '.' . $suffix : '',
            '{filemd5}' => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        //
        $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $json['code'] = 0;
            $json['url'] = $uploadDir . $splInfo->getSaveName();
            $json['file_name'] = $fileInfo['name'];
            $json['msg'] = '上传成功';
        } else {
            // 上传失败获取错误信息
            $json['code'] = 1;
            $json['url'] = '';
            $json['msg'] = $file->getError();
        }
        return json($json);
    }


}
