<?php

namespace app\admin\model;

use think\Model;

class Articles extends Model
{
    // 表名
    protected $name = 'articles';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'status_text',
        'is_home_text'
    ];
    

    
    public function getStatusList()
    {
        return ['1' => __('Status 1'),'2' => __('Status 2')];
    }     

    public function getIsHomeList()
    {
        return ['1' => __('Is_home 1'),'2' => __('Is_home 2')];
    }     


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsHomeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_home']) ? $data['is_home'] : '');
        $list = $this->getIsHomeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
