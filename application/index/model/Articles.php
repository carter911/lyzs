<?php
/**
 * Created by PhpStorm.
 * User: xinghe
 * Date: 08/12/2018
 * Time: 2:49 PM
 */

namespace app\index\model;

use think\Model;

class Articles extends Model
{

    const PAGE_SIZE = 5;

    public function get_article_list($pageIndex = 1, $status=1)
    {
        $pageIndex = $pageIndex - 1 < 0 ? 0 : $pageIndex - 1;
        return $this->where(["status" => $status])->limit($pageIndex * Articles::PAGE_SIZE, Articles::PAGE_SIZE)->field(["id", "title", "desc", "image", "createtime"])->select();
    }


    public function get_page($pageIndex, $status)
    {
        $totalSize = $this->where(["status" => $status])->count();
        $lastPage = $totalSize % Articles::PAGE_SIZE == 0 ? intval($totalSize / Articles::PAGE_SIZE) : intval($totalSize / Articles::PAGE_SIZE) + 1;
        if($pageIndex > $lastPage) $pageIndex = $lastPage;

        return array(
            "first_page" => 1,
            "last_page" => $lastPage,
            "prev_page" => $pageIndex - 1 < 1 ? 1 : $pageIndex - 1,
            "next_page" => $pageIndex + 1 > $lastPage ? $lastPage : $pageIndex + 1,

            "start_page" => $pageIndex - 2 < 1 ? 1 : $pageIndex - 2,
            "end_page" => $pageIndex + 2 > $lastPage ? $lastPage + 1 : $pageIndex + 3,

            "current_page" => $pageIndex
        );
    }


}