<?php

namespace addons\thumb;

use think\Addons;

/**
 * 缩略图插件
 * 2018-02-12
 * author: dilu
 * 253407587@qq.com
 */
class Thumb extends Addons
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
     * 实现钩子方法
     * @return mixed
     */
    public function uploadAfter($param)
    {
        // 获取传进来的附件模型数据
        $data = $param->getData();
        //对文件进行检测 不是图片类型的不做处理
        if(!strpos($data['mimetype'],'image'))
        {
            return false;
        }
        //附件id
        $attachment_id = $data['id'];
        //获取配置
        $config = $this->getConfig();
        //图片质量
        $quality = isset($config['quality']) ? $config['quality'] : '100';
        if ($quality > 100 || $quality < 10) {
            $quality = 100;
        }
        if (1 == $config['replace'])//如果是选择替换原文件
        {
            //打开文件
            $image = \think\Image::open(ROOT_PATH . '/public' . $data['url']);
            $image->thumb($config['size'], $config['size'])->save(ROOT_PATH . '/public' . $data['url'], null, $quality);

            $data = array(
                'filesize' => filesize(ROOT_PATH . '/public' . $data['url']),
                'imagewidth' => $image->width(),
                'imageheight' => $image->height(),
                'imagetype' => $image->type(),
                'imageframes' => 0,
                'mimetype' => $image->mime(),
                'url' => $data['url'],
                'uploadtime' => time(),
                'storage' => 'local',
                'sha1' => sha1_file(ROOT_PATH . '/public' . $data['url']),
                'updatetime' => time(),
            );

            $param->where('id', $attachment_id)->update($data);
        } else {
            $image = \think\Image::open(ROOT_PATH . '/public' . $data['url']);
            //获取后缀
            $ext = isset($config['ext']) ? $config['ext'] : '-thumb';
            $url = explode('.', $data['url']);
            //组装缩略图的url
            $url = $url[0] . $ext . '.' . $url[1];
            $image->thumb($config['size'], $config['size'])->save(ROOT_PATH . '/public' . $url, null, $quality);

            $data = array(
                'filesize' => filesize(ROOT_PATH . '/public' . $url),
                'imagewidth' => $image->width(),
                'imageheight' => $image->height(),
                'imagetype' => $image->type(),
                'imageframes' => 0,
                'mimetype' => $image->mime(),
                'url' => $url,
                'uploadtime' => time(),
                'storage' => 'local',
                'sha1' => sha1_file(ROOT_PATH . '/public' . $url),
                'createtime' => time(),
                'updatetime' => time(),
            );
            $param->insert($data);
        }

        // 当前插件的配置信息，配置信息存在当前目录的config.php文件中，见下方
        //print_r($this->getConfig());
        // 可以返回模板，模板文件默认读取的为插件目录中的文件。模板名不能为空！
        //return $this->fetch('view/info');
    }

}
