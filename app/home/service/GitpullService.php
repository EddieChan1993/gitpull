<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/5/3
 * Time: 13:14
 */

namespace app\home\service;

use app\common\service\BaseService;
use think\Db;
use think\Exception;
use think\exception\HttpException;

class GitpullService extends BaseService
{
    public static function shell(string $key): bool
    {
        $flag = false;
        try {
            $data = Db::name('project')
                ->where(['key'=>$key])
                ->field('secret_token,path')
                ->find();

            $secret = $data['secret_token'];
            $path = $data['path'];

            if (empty($data)) {
                throw new Exception('"Nothink project"');
            }

            if (!is_dir($path)) {
                throw new Exception('Local directory is missing');
            }

            $payload = file_get_contents('php://input');
            if (!$payload) {
                throw new Exception('HTTP HEADER or POST is missing.');
            }

            $signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : "";
            if ($signature) {
                $hash = "sha1=" . hash_hmac('sha1', $payload, $secret);
                if (strcmp($signature, $hash) == 0) {
                    echo shell_exec("cd {$path} && git pull 2>&1");
                    $flag = true;
                }else{
                    throw new Exception('Permission Denied');
                }
            }else{
                throw new Exception('Permission Denied');
            }
        } catch (\Exception $e) {
            self::setErr($e);
        }
        return $flag;
    }
}