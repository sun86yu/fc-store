# fc-store
使用 lavavel 作为框架，基于 adminlte 的前台界面。

##启动项目
1. 安装 php 依赖(初始化项目时必须执行).如果项目更新时 composer.json 有变动，则需要执行 ```composer install```
2. 安装 nodejs 依赖(初始化项目时必须执行).如果项目更新时 package.json 有变动，则需要执行 ```npm install```
3. 安装前台依赖包(初始化项目时必须执行).项目更新后,如果发现 bower.json 有变更，则需要执行 ```bower install```
4. 处理静态资源(初始化项目时必须执行).最好每次项目更新时都执行 ```gulp app:build```
5. 初始化库，只用执行一次 ```php artisan migrate``` 以及 ```php artisan db:seed```
6. 启动异步队列,每次启动项目时执行. ```php artisan queue:work --queue=sys_log,goods_search```
7. 添加队列监控.每次启动项目时都要执行,可以添加到计划任务里,每分钟执行,刷新监控结果以生成图表 ```php artisan horizon```
8. 建立上传目录 storege/app/public 和 public/storage 的软链接.只需要执行一次. ```php artisan storage:link```