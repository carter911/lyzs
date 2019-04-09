<?php

namespace addons\qiniu;

use addons\qiniu\library\Auth;
use think\Addons;

/**
 * 七牛上传插件
 */
class Qiniu extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     *
     */
    public function uploadConfigInit(&$upload)
    {
        $qiniucfg = $this->getConfig();

        $policy = array(
            'saveKey' => ltrim($qiniucfg['savekey'], '/'),
        );
        //如果启用服务端回调
        if ($qiniucfg['notifyenabled']) {
            $policy = array_merge($policy, [
                'callbackUrl'  => $qiniucfg['notifyurl'],
                'callbackBody' => 'filename=$(fname)&key=$(key)&imageInfo=$(imageInfo)&filesize=$(fsize)&admin=$(x:admin)&user=$(x:user)'
            ]);
        }

        $auth = new Auth($qiniucfg['app_key'], $qiniucfg['secret_key']);
        $multipart['token'] = $auth->uploadToken($qiniucfg['bucket'], null, $qiniucfg['expire'], $policy);
        $multipart['admin'] = (int)session('admin.id');
        $multipart['user'] = (int)cookie('uid');
        $upload = [
            'cdnurl'    => $qiniucfg['cdnurl'],
            'uploadurl' => $qiniucfg['uploadurl'],
            'bucket'    => $qiniucfg['bucket'],
            'maxsize'   => $qiniucfg['maxsize'],
            'mimetype'  => $qiniucfg['mimetype'],
            'multipart' => $multipart,
            'multiple'  => $qiniucfg['multiple'] ? true : false,
        ];
    }

}
