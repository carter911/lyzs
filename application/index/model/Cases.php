<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Cases extends Model
{
    protected $table = 'fa_cases';
    protected $resultSetType = 'collection';

    protected function initialize()
    {
        parent::initialize();
    }

//    public function getTeamDoorIdsAttr($value)
//    {
//        $newVal = explode(",", $value);
//        return $newVal;
//    }

}