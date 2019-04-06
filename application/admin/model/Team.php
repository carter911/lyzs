<?php

namespace app\admin\model;

use think\Model;

class Team extends Model
{
    // 表名
    protected $name = 'team';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'is_home_text',
        'is_gxj_text',
        'is_gddz_text',
        'sex_text'
    ];
    

    
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

    public function getSexList()
    {
        return ['1' => __('Sex 1'),'2' => __('Sex 2')];
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


    public function getSexTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['sex']) ? $data['sex'] : '');
        $list = $this->getSexList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
