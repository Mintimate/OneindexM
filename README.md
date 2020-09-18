# 一：简介

本项目是Oneindex、OneindexN的继承、自用版本,以及OneindexN主题的修改。

OneindexN项目地址：https://github.com/xieqifei/OneindexN

管理员密码：MintimateBlog

![](https://cdn.jsdelivr.net/gh/Mintimate/OneindexM/demo/demo.png)

#### 关于部署到heroku等，php无硬盘操作权限的平台须知：

> 安装程序不可用，将无限循环。请自行在其他主机安装后将config下文件拷贝到仓库，然后部署。网站的后台也无法修改前台的功能，后台修改都需要在主机上修改后，将config文件拷贝到heroku。
>
> 建议使用虚拟主机/vps安装此应用。免费的虚拟主机：https://www.000webhost.com
>
> 一个不完整的000webhost使用教程：https://sci.ci/2020/06/22/oneindex-zi-jian-bu-xian-su-wang-pan/
>
> 这个虚拟主机安装的网站，网站会有水印，无法去除。你可以在这个主机上进行安装，然后将网站目录下的config文件夹下载下来，放到你fork的github仓库，然后部署到heroku。从而避免在heroku上运行安装程序。然后删除仓库的配置文件，/config/token.php和/config/base.php。务必关闭heroku的自动部署功能。

如果安装时无法切换世纪互联/国际版本，可以自行修改`/lib/onedrive.php`中的`api_url`和`oauth_url`参数。

全局搜索为onedrive官方返回的结果，搜索结果并不准确。过滤功能仅对当前页有效，能较为精确地根据关键词过滤。

> dev
> dev分支是开发中版本。较master功能可能更完善，但稳定性不做保证。

## 修改功能：

### 后台：

- 选择安装世纪互联/国际版（如需修改版本，需要删除config文件夹里的文件后重新进入安装程序）
- ~~开启/关闭游客在线上传、指定游客上传路径，管理员不受指定路径限制~~
- ~~开启/关闭游客离线上传，需安装aria2+rclone，上传路径在rclone中设置~~
- 指定文件夹/全部文件夹，关闭Readme.md、index.html、head.md渲染（如果开启游客离线上传，可以关闭此路径的渲染，避免游客上传会被渲染的文件。）

### 前台

以下功能仅支持nexmoe主题

- 搜索功能：全局搜索/当前页过滤
- 文件操作：删除/新建/重命名/剪切/复制/分享等。仅管理员可用
- 文件上传：4M以下的文件在线上传。url远程上传，仅onedrive个人版支持。aria2离线上传，需自行安装在vps上安装aria2并完成相关配置。
- 外部视频播放器播放接口。需要安装对应播放器。
- 管理员登陆后，查看加密文件夹无需密码。

# 二：部署网站

网站环境尽量使用nginx。后面会用nginx做反代，可以避免修改yaaw的rpc参数。

## 功能：

不占用服务器空间，不走服务器流量，  

直接列出 OneDrive 目录，文件直链下载。  

## 使用及免责协议

[使用及免责协议](./使用及免责协议.md)

## 安装运行

### 源码安装运行：

#### 需求：

1、PHP空间，PHP 5.6+ 需打开curl支持  
2、OneDrive 账号 (个人、企业版或教育版/工作或学校帐户)  
3、OneIndex 程序   

### 计划任务  

[可选]**推荐配置**，非必需。后台定时刷新缓存，可增加前台访问的速度。  

```
# 每小时刷新一次token
0 * * * * /具体路径/php /程序具体路径/one.php token:refresh

# 每十分钟后台刷新一遍缓存
*/10 * * * * /具体路径/php /程序具体路径/one.php cache:refresh
```

### Docker 安装运行

- 请参考[TimeBye/oneindex](https://github.com/TimeBye/oneindex)

## 特殊文件实现功能  

` README.md `、`HEAD.md` 、 `.password`特殊文件使用  

可以参考[https://github.com/donwa/oneindex/tree/files](https://github.com/donwa/oneindex/tree/files)  

**在文件夹底部添加说明:**  

>在 OneDrive 的文件夹中添加` README.md `文件，使用 Markdown 语法。  

**在文件夹头部添加说明:**  

>在 OneDrive 的文件夹中添加`HEAD.md` 文件，使用 Markdown 语法。  

**加密文件夹:**  

>在 OneDrive 的文件夹中添加`.password`文件，填入密码，密码不能为空。  

**直接输出网页:**  

>在 OneDrive 的文件夹中添加`index.html` 文件，程序会直接输出网页而不列目录。  
>配合 文件展示设置-直接输出 效果更佳。  

## 命令行功能  

仅能在PHP CLI模式下运行  

**清除缓存:**  

```
php one.php cache:clear
```

**刷新缓存:**  

```
php one.php cache:refresh
```

**刷新令牌:**  

```
php one.php token:refresh
```

**上传文件:**  

```
php one.php upload:file 本地文件 [OneDrive文件]
```

**上传文件夹:**  

```
php one.php upload:folder 本地文件夹 [OneDrive文件夹]
```

例如：  

```
//上传demo.zip 到OneDrive 根目录  
php one.php upload:file demo.zip  

//上传demo.zip 到OneDrive /test/目录  
php one.php upload:file demo.zip /test/  

//上传demo.zip 到OneDrive /test/目录并将其命名为 d.zip  
php one.php upload:file demo.zip /test/d.zip  

//上传up/ 到OneDrive /test/ 目录  
php one.php upload:file up/ /test/
```

# 三：使用Aria2

Nginx添加反向代理

```
location /jsonrpc {
        proxy_pass http://localhost:6800/jsonrpc;
        proxy_redirect off;
        proxy_set_header        X-Real-IP       $remote_addr;
        proxy_set_header        X-Forwarded-For
        $proxy_add_x_forwarded_for;
        proxy_set_header Host $host;
	}
```

nginx会监听发送给`http://yoursite/jsonrpc`的消息，然后将他转发给`http:localhost:6800/jsonrpc`，相当于你在yaaw中设置rpc时，只需要将其设置为`http://yoursite/jsonrpc`或者`https://yoursite/jsonrpc`,省略的端口信息为http对应80,https对应443，这些端口浏览器会自动转发，不用在设置中指定。如果你设置了反向代理，那么使用前台yaaw时，就不用在做rpc设置了。否则你需要去重新设置。

![](https://i.loli.net/2020/06/25/9cY2PiBr6usqXen.png)

**关闭aria2远程RPC**

仅vps本地也就是nginx转发的请求能到达6800。其他主机不能访问6800端口。

```shell
vim /root/.aria2c/aria2.conf
```

修改aria2配置内容

```
# 启用RPC, 默认:false
enable-rpc=true
# 接受所有远程请求, 默认:false
rpc-allow-origin-all=false
# 允许外部访问, 默认:false
rpc-listen-all=false
# RPC监听端口, 端口被占用时可以修改, 默认:6800
rpc-listen-port=6800
```

> 请注意，关闭远程请求并不能阻止其他主机向aria2发送请求，在不设置token时，任何人都可以通过直接向`http://yoursite/jsonrpc`这个地址发送请求连接aria2。如果你开启游客离线下载可以这么设置。如果你不希望有人通过其他aria2前端连接你的aria2，请你务必设置token，但是这样做，你也必须在使用的时候修改rpc设置，好在，第一次修改设置后，之后浏览器都会记住这个设置。

> 博客：https://sci.ci
>
> 如果应用有bug，或者你有好的修改建议，可以通过邮箱联系我：im@xieqifei.com

# 五：参考资料

《[github YAAW项目](https://github.com/binux/yaaw)》

《[Issue：一旦使用HTTPS协议就无法连接](https://github.com/mayswind/AriaNg/issues/62)》

《[Nginx 反向代理 Aria2 JSONRPC](https://kenvix.com/post/nginx-proxy-aria2/)》