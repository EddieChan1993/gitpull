<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/3
 * Time: 15:27
 */

namespace app\admin\controller;

use app\admin\controller\core\Base;
use app\common\service\CurdService;
use think\Validate;

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

    //插入逻辑
    public function addThink()
    {
        $validate = new Validate([
            ['name', 'require', '项目名称必填'],
            ['secret_token', 'require', '登录密钥必填'],
            ['key', 'require|unique:project', '登录key必填'],
        ]);

        if (!$validate->check($_POST)) {
            $this->error($validate->getError());
        }

        $res = CurdService::name($this->model)
            ->add($_POST);
        if ($res) {
            $this->success("添加成功");
        }else{
            $this->error("添加失败");
        }
    }

    //编辑逻辑
    public function editThink()
    {
        $validate = new Validate([
            ['name', 'require', '项目名称必填'],
            ['secret_token', 'require', '登录密钥必填'],
            ['key', 'require|unique:project', '登录key必填'],
        ]);

        if (!$validate->check($_POST)) {
            $this->error($validate->getError());
        }
        $res = CurdService::name($this->model)
            ->update($_POST);
        if ($res) {
            $this->success("编辑成功");
        }else{
            $this->error("未作任何更新");
        }
    }}