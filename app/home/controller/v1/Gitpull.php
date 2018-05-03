<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/3
 * Time: 13:11
 */

namespace app\home\controller\v1;


use app\home\controller\Base;
use app\home\service\GitpullService;

class Gitpull extends Base
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