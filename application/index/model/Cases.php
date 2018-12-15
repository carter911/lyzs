<?php

namespace app\index\model;

use think\Model;

class Cases extends Model
{
    protected $table = 'fa_cases';
    protected $resultSetType = 'collection';

    protected function initialize()
    {
        parent::initialize();
    }
}