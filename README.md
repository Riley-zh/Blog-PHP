# 现代PHP博客内容管理系统 (Modern PHP Blog CMS)

[](https://github.com/Riley-zh/Blog-PHP/actions/workflows/ci.yml)
[](https://github.com/phpstan/phpstan)
[](https://opensource.org/licenses/MIT)
[](https://php.net/)

一个高性能、现代化的PHP博客内容管理系统，专为性能和可扩展性而设计。

## 功能特性

- 基于现代PHP 8.1+开发，采用严格的类型声明
- PSR-4自动加载标准
- MVC架构设计模式
- PDO数据库抽象层
- 多数据库支持（MySQL、PostgreSQL、SQLite）
- 安全的身份验证系统
- 响应式设计，使用TailwindCSS框架
- Composer依赖管理
- 性能优化的数据库查询
- 持久数据库连接
- 全面的日志记录系统
- 灵活的缓存系统
- 基于文件的队列系统
- 完整的后台管理系统
- RESTful路由系统
- 模型关系管理

## 系统要求

- PHP 8.1 或更高版本
- MySQL 5.7+ 或 MariaDB 10.2+（如使用MySQL）
- PostgreSQL 9.6+（如使用PostgreSQL）
- Composer依赖管理工具

## 数据库支持

本应用程序支持三种数据库系统：

1. **MySQL** - 默认选项，使用最广泛的关系型数据库
2. **PostgreSQL** - 功能强大的开源对象关系型数据库
3. **SQLite** - 轻量级基于文件的数据库，适合开发环境

## 安装指南

### 1. 克隆代码仓库：
```
git clone <repository-url>
```

### 2. 安装项目依赖：
```
composer install
```

### 3. 配置数据库连接：

#### MySQL配置：
```
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### PostgreSQL配置：
```
DB_DRIVER=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### SQLite配置：
```
DB_DRIVER=sqlite
DB_DATABASE=./database/blog.sqlite
```

### 4. 运行数据库迁移：
```
php cli.php migrate:up
```

### 5. （可选）填充测试数据：
```
php seed.php
```

### 6. 启动开发服务器：
```
composer start
```

### 7. 在浏览器中访问：
```
http://localhost:8000
```

## 项目结构说明

```
app/                    # 应用程序源代码目录
  Controllers/          # 控制器类文件
  Core/                 # 核心框架类文件
  Database/             # 数据库相关类文件
  Models/               # 模型类文件
  Middleware/           # 中间件类文件
  Utils/                # 工具类文件
bootstrap/              # 应用程序引导文件
config/                 # 配置文件目录
database/               # 数据库迁移文件
  migrations/           # 数据库迁移脚本
public/                 # 公共可访问文件目录
resources/              # 视图模板和其他资源文件
  views/                # 视图模板文件
routes/                 # 路由定义文件
storage/                # 存储目录（日志、缓存等）
  cache/                # 缓存文件存储目录
  logs/                 # 日志文件存储目录
  queues/               # 队列文件存储目录
tests/                  # 测试文件目录
```

## 性能优化措施

- 持久数据库连接减少连接开销
- 所有查询使用预处理语句防止SQL注入
- 高效的查询构建器
- 数据库模式中包含适当的索引
- 服务的延迟加载
- 缓存系统提升响应速度

## 系统组件详解

### 日志系统
- 符合PSR-3标准的日志记录器
- 可配置的日志级别（debug, info, warning, error等）
- 基于文件的日志存储

### 缓存系统
- 基于文件的缓存实现
- 可配置的生存时间(TTL)
- 支持缓存标签管理

### 队列系统
- 基于文件的队列实现
- 队列工作进程处理任务
- 支持延迟任务执行

### 后台管理系统
- 仪表板显示统计信息
- 文章的增删改查(CRUD)操作
- 用户管理功能
- 分类和标签管理

### 路由系统
- RESTful API路由支持
- 中间件支持（认证、权限等）
- 路由参数绑定

### 认证系统
- 用户注册和登录功能
- 密码加密存储
- 会话管理
- CSRF保护

## 测试

运行测试套件：
```
composer test
```

## 命令行工具

项目包含一个命令行接口(CLI)工具，用于执行各种管理任务：

```
php cli.php [命令] [参数]
```

可用命令：
- `migrate:up` - 运行所有待处理的迁移
- `migrate:down` - 回滚迁移
- `seed` - 填充测试数据
- `cache:clear` - 清除缓存
- `queue:work` - 启动队列工作进程

## 环境配置

项目使用`.env`文件进行环境配置，主要配置项包括：

- 应用程序基本信息（名称、环境、调试模式等）
- 数据库连接配置
- 日志配置
- 缓存配置
- 队列配置
- 邮件配置
- CORS跨域配置

## API接口

系统提供RESTful API接口，支持以下资源操作：

- 文章管理（创建、读取、更新、删除）
- 用户管理
- 分类和标签管理
- 评论管理

API接口遵循标准的HTTP状态码和JSON响应格式。

## 安全特性

- SQL注入防护（使用PDO预处理语句）
- XSS防护（输出转义）
- CSRF防护（令牌验证）
- 密码安全（使用PHP password_hash函数）
- 输入验证和过滤
- 安全的会话管理

## 扩展性设计

- 模块化架构便于功能扩展
- 接口驱动的设计便于替换组件
- 事件驱动架构支持插件系统
- 配置驱动便于不同环境部署

## 许可证

本项目为开源项目，采用MIT许可证发布。
