laravel-admin 工单管理
======

#### admin-workorder 概况

- 适用版本： laravel-admin 1.7.3

# 安装 admin-workorder

## 运行命令

```
composer require ccbox/admin-workorder
```

## 配置参数

在`config/admin.php`文件的`extensions`，加上属于这个扩展的配置
```php
    'extensions' => [

        'admin-workorder' => [
            // 如果要关掉这个扩展，设置为false
            'enable' => true,
            // 设置本地启用的文本编辑器（工单内容字段）
            'editor' => [
                // 编辑器扩展名
                // 设置为 text/textarea 则使用单行/多行文本框，不使用富文本编辑器
                'name' => 'editor',
                // 编辑器自定义参数
                // 请先确认启用的编辑器是否支持options配置再填写，为空时则使用编辑器的默认配置
                'config' => [],
            ],
            // 工单附件存储盘，留空为使用系统默认盘
            'files_disk' => '',
        ],

    ]
```

## 执行安装/更新命令（导入数据表）
> 【安装命令暂时不能用，请直接导入数据表】
```bash
# 运行安装（暂不支持）
# php artisan admin-workorder:install
# 运行升级（暂不支持）
# php artisan admin-workorder:update

# 请直接导入数据表
php artisan migrate --path=vendor/ccbox/admin-workorder/database/migrations
```

## 发布菜单
```bash
php artisan admin:import admin-workorder
```

## 开始使用

安装完成之后打开`http://localhost/admin/admin-workorder`

> 注意：更新版本时可能需要重新发布资源，具体可看更新文档

# 使用说明

```php
// 获取用户工单列表
AdminWorkorder::getTickets($user);

// 获取某工单详情
AdminWorkorder::getTicketInfo($id);

/**
 * 添加工单
 * @param string $content
 * @param array $images
 * @param User $user
 * @param int $p_id
 */
AdminWorkorder::addTicket($content, $up_images, $user, $p_id);//添加工单
```

# 其他/本地安装

本地安装需要先配置项目内容如下：

> #### 项目composer.json，在repositories增加以下地址：
```
    "repositories": {
        "ccbox/admin-workorder": {
            "type": "path",
            "url": "./app/Admin/Extensions/ccbox/admin-workorder/"
        }
    }
```

> #### 插件composer.json:
```
    "minimum-stability": "dev",
```

> ####  安装本地 admin-workorder
```
composer require ccbox/admin-workorder:dev-master -vvv
```


License
------------
Licensed under [The MIT License (MIT)](LICENSE).