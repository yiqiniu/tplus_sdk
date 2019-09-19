# tplus 接口

#### 项目介绍
T+进销存接口封装

#### 软件架构
软件架构说明


#### 安装教程

    配置compser.json


    包含开发包
    composer require "yqn/tplus_sdk":"*"

#### 使用说明

    1.加载配置
    $config = include 'config.php';
    $ibaseauth = tplus_baseAuth($config);

    2.登录认证

    1.orgid认证方式
    $ibaseauth->autologin()


    2. 用户名和密码认证 传入参数  用户名(username) 密码:(password),帐套:($accnum)
    $ibaseauth->autologin($username,$password,$accnum)

### 实例


    //加载配置文件
    $config = include 'config.demo.php';

    $ibaseauth = tplus_baseAuth($config);

    // 进行自动登录
    if($ibaseauth->autologin()){
        warehouse($ibaseauth);
        department($ibaseauth);

    }