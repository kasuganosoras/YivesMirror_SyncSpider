# YivesMirror_SyncSpider
YivesMirror 镜像源同步脚本（Sakura's Mirror 专用）

YivesMirror 目前支持同步的服务端类型有：
```
Spigot
Bukkit
CraftBukkit
Paper
Cauldron
Torch
TacoSpigot
Thermos
Mcpc
HexaCord
Travertine
PocketMine
Nukkit
Hose
Pixelmon
```
### 如何同步呢？

1. 修改源码里的 `$root` 为你的储存目录，例如 `/data/server/`
2. 在储存目录下新建一些文件夹，名字就是上面所支持的类型。
3. 运行脚本，挂在后台，坐等同步完成就好了。

### 如何在后台运行呢？

安装 screen，命令如下
```
# CentOS / RedHat / Fedora
yum install screen -y
# Ubuntu / Debian
apt-get install screen
```
创建一个 screen 会话，名字随意，例如 yives
```
screen -S yives
```
启动脚本
```
php main.php
```
### 脚本交流

关于镜像站、爬虫、脚本交流，请加入我们的官方群：669204567

群内有机器人会推送每天的更新。
