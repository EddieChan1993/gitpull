<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/3
 * Time: 13:11
 */

namespace app\home\controller\v1;


use app\common\controller\BaseController;
use app\home\controller\Base;
use app\home\service\GitpullService;
use think\Request;

class Gitpull extends BaseController
{
    function searchProject($key)
    {
        $res=GitpullService::shell($key);
        if (!$res) {
            return json(GitpullService::getErr(), 500);
        }
        return json("Ok");
    }
}