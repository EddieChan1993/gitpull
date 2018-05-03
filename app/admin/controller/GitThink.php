<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/3
 * Time: 15:27
 */

namespace app\admin\controller;

use app\admin\controller\core\Base;

class GitThink extends Base
{
    public function __construct()
    {
        parent::_initialize();
        $this->model = "project";
        $this->setTitle("Git项目管理");
        $this->setTab1("Git项目列表");
        $this->setTab2("添加项目");
    }
}