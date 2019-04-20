<?php

namespace app\admin\model;

use think\Model;

class Material extends Model
{
    // 表名
    protected $name = 'material';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'status_text',
        'is_gxj_text',
        'is_gddz_text',
        'is_yj_text'
    ];
    

    
    public function getStatusList()
    {
        return ['1' => __('Status 1'),'2' => __('Status 2')];
    }     

    public function getIsGxjList()
    {
        return ['1' => __('Is_gxj 1'),'2' => __('Is_gxj 2')];
    }     

    public function getIsGddzList()
    {
        return ['1' => __('Is_gddz 1'),'2' => __('Is_gddz 2')];
    }     

    public function getIsYjList()
    {
        return ['1' => __('Is_yj 1'),'2' => __('Is_yj 2')];
    }     


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsGxjTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_gxj']) ? $data['is_gxj'] : '');
        $list = $this->getIsGxjList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsGddzTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_gddz']) ? $data['is_gddz'] : '');
        $list = $this->getIsGddzList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsYjTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_yj']) ? $data['is_yj'] : '');
        $list = $this->getIsYjList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
