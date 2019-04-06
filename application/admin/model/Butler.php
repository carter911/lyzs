<?php

namespace app\admin\model;

use think\Model;

class Butler extends Model
{
    // 表名
    protected $name = 'butler';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'is_gdzd_text'
    ];
    

    
    public function getIsGdzdList()
    {
        return ['1' => __('Is_gdzd 1'),'2' => __('Is_gdzd 2')];
    }     


    public function getIsGdzdTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_gdzd']) ? $data['is_gdzd'] : '');
        $list = $this->getIsGdzdList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
