<?php
namespace app\admin\controller\core;

use think\Db;
use think\Exception;
use think\Validate;

class Setting extends Base
{
    function home_page()
    {

        $sites = Db::name('options')->find(6);//网站配置信息
        $showSites = json_decode($sites['option_value']);
        $showSites->is_close = $sites['is_close'];
        $showSites = object2array($showSites);

        $seo = Db::name('options')->find(7);//Seo配置
        $showSeo = json_decode($seo['option_value']);
        $showSeo = object2array($showSeo);
        $showSites = array_merge($showSites, $showSeo);

        return view('core/setting/home_page', $showSites);
    }

    //存储网站配置信息
    function save_sites()
    {
        $option_value = $_POST['option_value'];

        if (!input('?is_close')) {
            $is_close = input('is_close') ? input('is_close') : 0;
        } else {
            $is_close = input('is_close');
        }

        $saveSitesMap = [
            'option_id' => 6,
            'option_value' => json_encode($option_value),
            'is_close' => $is_close
        ];

        if (Db::name('options')->update($saveSitesMap)) {
            $this->success('网站信息编辑成功');
        } else {
            $this->error('网站信息未做任何修改');
        }
    }

    //存储完整seo
    function save_seo()
    {
        $saveSeoMap = [
            'option_id' => 7,
            'option_value' => json_encode($_POST['option_value'])
        ];

        if (Db::name('options')->update($saveSeoMap)) {
            $this->success('网站Seo编辑成功');
        } else {
            $this->error('网站信息未做任何修改');
        }
    }
    /*========================================================插件库====================================================*/
    //插件页面
    function plugins_home()
    {
        $widgetsNums = Db::name('widgets')->count();
        $widgets = Db::name('widgets')
            ->order('wid_date desc')
            ->select();
        $map = [
            'widget' => $widgets,
            'widgetsNums' => $widgetsNums
        ];
        return view('core/setting/plugins_home', $map);
    }

    //添加插件
    function add_plugins_think()
    {
        $validate = new Validate([
            ['wid_name', 'require', '插件名称必填'],
            ['wid_key', 'require|unique:widgets', '插件关键字必填|插件关键字已经存在'],
            ['wid_params', 'require', '插件参数必填'],
        ]);

        try {
            if (!$validate->check($_POST)) {
                throw new Exception($validate->getError());
            }

            $addData = $_POST;
            $addData['wid_admin'] = open_secret(cookie('UID'));
            $addData['wid_date'] = time();
            $res = Db::name('widgets')->insert($addData);
            if (!$res) {
                throw new Exception("插件添加失败");
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件添加成功');
    }

    //删除插件
    function del_plugins_think()
    {
        if (Db::name('widgets')->delete(input('wid_id'))) {
            $this->success('插件删除成功');
        } else {
            $this->error('插件删除失败');
        }
    }

    function edit_plugins_page()
    {
        $plugins = Db::name('widgets')->find(input('wid_id'));
        return view('core/setting/plugins_edit_page', $plugins);
    }

    //插件编辑
    function edit_plugins_think()
    {
        try {
            $validate = new Validate([
                ['wid_name', 'require', '插件名称必填'],
                ['wid_key', 'require|unique:widgets', '插件关键字必填|插件关键字已经存在'],
                ['wid_params', 'require', '插件参数必填'],
            ]);
            if (!$validate->check($_POST)) {
                throw new Exception($validate->getError());
            }
            if (!Db::name('widgets')->update($_POST)) {
                throw new Exception("该插件编辑失败");
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('该插件编辑成功');
    }
}