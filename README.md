# Mojito

Mojito 是一个基于 Laravel, Vue, Element UI 构建的后台管理系统。

## 截图

![mojito-admin.jpg](http://ww1.sinaimg.cn/large/7a679ca1gy1ggfdd1odgvj21420l20uj.jpg)

## Demo

登陆地址 http://mojito.moell.cn/admin/login ， 用户名 `mojito@gmail.com` ，密码 `mojito-demo`

## 特征

* 可快速衍生多个后台系统
* 内置角色，权限，用户，菜单管理
* API 权限精确至路由，页面权限精确到按钮或链接
* 完善的PHPUnit测试
* 前后端分离
* 多标签页

## 要求

- Laravel  >= 7.0.0
- Vue >= 2.5.17
- Element >= 2.9.1

## 安装

首先安装laravel,并且确保你配置了正确的数据库连接。

vcs:
```
    "repositories": [{
        "type": "vcs",
        "url": "https://github.com/osindex/mojito.git"
    }]
```

然后运行下面的命令来发布资源:

```
php artisan mojito:install
```

命令执行成功会生成配置文件，数据迁移和构建SPA的文件。

修改 `app/Http/Kernel.php` ：

```
class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        ...
        'mojito.permission' => \Moell\Mojito\Http\Middleware\Authenticate::class,
    ];

    protected $middlewareGroups = [
            ...
            'api' => [
                ...
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ],
        ];
}
```

执行数据迁移，数据填充

```
php artisan migrate

php artisan db:seed --class="Moell\Mojito\Database\MojitoTableSeeder"
```


安装 Javscript 依赖

```shell
npm install
npm install -D vue@^2.6.6 vuex@^3.0.1 vue-router@^3.0.1 vue-i18n@^8.1.0 localforage@^1.7.2 element-ui@^2.9.1
```

将 admin.js  添加到 webpack.mix.js 

```
mix.js('resources/js/admin.js', 'public/js');
```

运行 Mix

```
#npm run watch
npm run production
```
Log in

url: http://localhost/mojito#/admin/login

email: admin@admin.com

password: password

## Dependent on open source software

* Laravel
* Vue
* Element UI
* laravel/passport
* smartins/passport-multiauth
* spatie/laravel-permission
* orchestra/testbench
