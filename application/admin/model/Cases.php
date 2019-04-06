<?php

namespace app\admin\model;

use think\Model;

class Cases extends Model
{
    // 表名
    protected $name = 'cases';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'status_text',
        'type_text',
        'is_kjl_text',
        'is_home_text',
        'is_gxj_text',
        'is_gddz_text'
    ];
    

    
    public function getStatusList()
    {
        return ['1' => __('Status 1'),'2' => __('Status 2')];
    }     

    public function getTypeList()
    {
        return ['1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3')];
    }     

    public function getIsKjlList()
    {
        return ['1' => __('Is_kjl 1'),'2' => __('Is_kjl 2')];
    }     

    public function getIsHomeList()
    {
        return ['1' => __('Is_home 1'),'2' => __('Is_home 2')];
    }     

    public function getIsGxjList()
    {
        return ['1' => __('Is_gxj 1'),'2' => __('Is_gxj 2')];
    }     

    public function getIsGddzList()
    {
        return ['1' => __('Is_gddz 1'),'2' => __('Is_gddz 2')];
    }     


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsKjlTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_kjl']) ? $data['is_kjl'] : '');
        $list = $this->getIsKjlList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsHomeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_home']) ? $data['is_home'] : '');
        $list = $this->getIsHomeList();
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




}
