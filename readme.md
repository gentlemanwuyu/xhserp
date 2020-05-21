# 欣汉生企业管理系统
* Author: Woozee(https://www.woozee.com.cn)
* 演示地址: http://xhserp.woozee.com.cn
* 账号: 492444775@qq.com
* 密码: admin

## 基础功能
- 用户管理 —— 添加、编辑、删除；
- 权限系统 —— 角色、权限；
- 系统管理 —— 系统配置、系统日志；
- 分类管理 —— 商品分类、产品分类；
- 产品管理 —— 产品列表、添加产品、编辑产品、删除产品；
- 商品管理 —— 商品列表、添加商品、编辑商品、删除商品；
- 采购管理 —— 供应商管理、采购订单管理、采购退货单管理；
- 销售管理 —— 客户管理、付款方式申请审批、订单管理、出货单管理、退货单管理；
- 仓库管理 —— 备货管理、入库管理、出库管理、销售退货管理、采购退货管理、快递管理；
- 财务管理 —— 收款管理、销售应收款、付款管理、采购应付款、账户管理、货币列表；

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

