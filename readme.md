# 欣汉生企业管理系统
* Author: Woozee(https://www.woozee.com.cn)

## 基础功能
- 用户认证 —— 注册、登录、退出；
- 企业结构 —— 部门；
- 分类 —— 产品分类、商品分类；

## 运行环境要求
- Nginx >= 1.12
- PHP >= 7.0.0
- Mysql >= 5.6
- Redis >= 3.2

## 开发环境部署/安装

本项目代码使用 PHP 框架 [Laravel 5.2](https://d.laravel-china.org/docs/5.2/) 开发，本地开发环境使用 [Docker](https://github.com/gentlemanwuyu/dockerproject)。

### 基础安装

#### 1. 克隆源代码

克隆 `blog` 源代码到本地：

     git clone https://github.com/gentlemanwuyu/blog.git

#### 2. 安装扩展包依赖

     composer install

#### 3. 生成配置文件

```
cp .env.example .env
```

你可以根据情况修改 `.env` 文件里的内容，如数据库连接、缓存等：

### 前端框架安装

1). 安装 yarn

windows系统直接去官网 [https://yarn.bootcss.com/](https://yarn.bootcss.com/) 下载安装最新版本。
Linux系统可使用apt直接安装。

2). 安装 前端扩展包

    yarn install
如果是windows系统，在执行时需要加上`--no-bin-links`

3). 安装 gulp

    yarn global add gulp

4). 编译前端内容

```shell
// 运行所有gulp编译任务...
gulp

// 运行所有gulp编译任务并缩小输出，一般是正式环境使用。
gulp --production
```

