laravel-admin 工单管理
======

#### admin-workorder 概况

- 适用版本： laravel-admin 1.7.3

#### 安装 admin-workorder
```
composer require ccbox/admin-workorder
```

#### 执行安装/更新命令
> 【安装命令暂时不能用，请直接导入数据表】
```bash
# 运行安装
php artisan admin-workorder:install
# 运行升级
php artisan admin-workorder:update
# 直接导入数据表
php artisan migrate --path=vendor/ccbox/admin-workorder/database/migrations
```

#### 发布资源【无静态资源时不用运行】
```bash
php artisan vendor:publish --provider=Ccbox\AdminWorkorder\AdminWorkorderServiceProvider
```

#### 发布菜单
```bash
php artisan admin:import admin-workorder
```

> 注意：更新版本时可能需要重新发布资源，具体可看更新文档

#### 使用说明
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

#### 其他

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


#### License

Licensed under The MIT License (MIT). 