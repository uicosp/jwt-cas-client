# jwt-cas-server
一个基于 JWT 实现「单点登陆」的 [CAS，Central Authentication System](https://apereo.github.io/cas/4.2.x/planning/Architecture.html) 系统。

本项目依赖于 Laravel。

## 系统组成

- CAS Server （服务端，仅有一个）
- CAS Clients （客户端，多个）

用户只需在 Server 端登陆一次，获得 `token` 后便可用该令牌访问系统中的任意 Clients。

**[注意] 此项目为该系统的客户端实现，服务端请移步 https://github.com/uicosp/jwt-cas-server**

Client 端提供一个校验 `token` 的中间件

`Uicosp\JwtCasClient\Middleware\VerifyCasToken`

该中间件会验证每次请求中携带的 `token` 的合法性。校验失败将返回错误信息给前端。校验通过则将解密后的 `token` 注入到 `$request` 中。可通过 `$request['verified_token']` 获取。`verified_token` 示例如下：

```php
array:6 [
  "sub" => 11
  "iss" => "http://user.dev/jwt/login"
  "iat" => 1482998888
  "exp" => 1483002488
  "nbf" => 1482998888
  "jti" => "e148091d51ece1fb1cf77cc14d317298"
]
```

## 安装

`composer require "uicosp/jwt-cas-client"`

## 配置

### 1. 注册服务

本项目依赖 [typmon/jwt-auth](https://github.com/tymondesigns/jwt-auth)，请添加

`Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class`,

到 `config/app.php` 的 `providers` 数组。

### 2. 添加 Middleware

将 `Uicosp\JwtCasClient\Middleware\VerifyCasToken::class` 添加到 `app/Http/Kernel.php` 文件中。例如：

```php
/**
 * The application's route middleware.
 *
 * These middleware may be assigned to groups or used individually.
 *
 * @var array
 */
protected $routeMiddleware = [
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    ...
    // 将中间件添加到路由中间件，按需调用
    'auth.cas' => \Uicosp\JwtCasClient\Middleware\VerifyCasToken::class,
];
```

然后在需要的地方调用，例如在 `routes/web.php`：

```php
Route::get('/', function () {
    return view('welcome')->middleware('auth.cas');
});
```
