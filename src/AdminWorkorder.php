<?php

namespace Ccbox\AdminWorkorder;

use Encore\Admin\Extension;

class AdminWorkorder extends Extension
{
    public $name = 'admin-workorder';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => '工单系统',
        'path'  => 'admin-workorder',
        'icon'  => 'fa-gears',
    ];
}