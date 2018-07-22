# Cloudflare CNAME 接入

[Cloudflare Hosting Partner][1] 可以使用此项目为用户提供一个可视化的面板，可以让用户免费的使用 [CNAME 接入][2]。

[cf.tlo.xyz][3] 是安装了这个面板的最新稳定版的官方网站，值得你的信任。

[beta.cf.tlo.xyz][4] 安装了最新的测试版。

## 安装

如果你不想使用上述已经安装好了的面板，你可以在自己的服务器上安装。请参考下方指南。

### 需求

+ PHP 7.0+ (需要 cURL 和 APCu Cache 插件)
+ 网页服务器 (Nginx, Apache, 等)
+ Cloudflare Partner 账户

### 配置

只需要拷贝 `config.example.php` 到 `config.php`，然后对其编辑即可。

## 此面板的特性

+ 管理你的所有 DNS 记录。此面板使用了 [Cloudflare API v4][5]，所以支持各种格式的 DNS 记录。
+ 高级统计。你可以查看**过去一整年的统计信息**，而不仅仅是一个月。
+ 同时支持 NS 接入。此面板提供了 NS 接入信息，所以你可以随时切换到 Cloudflare DNS。此外，这个面板也支持 DNSSEC。
+ 同时支持 IP 接入。你可以看到 DNS 的 Anycast IPv4 和 IPv6 信息，这样你可以安全地在根域名下使用第三方 DNS。
+ 支持多种语言。

## 如何从 NS 接入转成从这个面板接入？

1. 备份现有域名的 DNS 记录。
2. 从备份中恢复，切换到另一个 DNS 解析商。(可选的)
3. 在 Cloudflare 上删除你的域名 (如果你没有完成第二部则可能会导致你的网站在一段时间内无法访问)
4. 在这个面板上重新添加域名。
5. 在这个面板上配置 DNS 记录。
6. 删除已有的 DNS 记录然后重新添加 CDN 的记录。(如果你在步骤二中切换到了另一个 DNS 解析商)

## CNAME 接入的好处

+ 更加灵活，因为你可以使用任何一个 DNS 提供商。
+ 将 Cloudflare 作为一个备份服务器，或者使用多个 CDN。
+ 可以免费支持四级域名下的 SSL！例如像 `dev.project.example.com` 这样的域名，Cloudflare 也会自动签发 SSL 证书，这是因为 CNAME 接入签发的是 [SSL for SaaS][6]，它会自动的为每一个字域名签发证书。

## 使用 Cloudflare 的好处

**你不需要在服务器端安装任何软件**。只需要在这个面板配置好源站服务器信息，删除已有记录并 CNAME 到 Cloudflare 的服务器，或者直接使用 Cloudflare DNS 即可！

+ 无限 DDOS 防御
+ 全球 CDN。你的网站会因此变得更快。
+ I'm Under Attack™ 模式可以自动清洗恶意流量。
+ Always Online ™ 让你的网站永远在线。
+ 支持 Page Rules. 你可以自定义缓存规则，配置 301 或 302 跳转以及更多。

## 屏幕截图

![Screenshot1][image-1]
![Screenshot2][image-2]
![Screenshot3][image-3]
![Screenshot4][image-4]

## Open sourced software used in this project

This project was based on a [HOSTLOC topic][7].

+ Amaze UI by Amaze UI Team | Licensed under MIT
+ Net\_DNS2 by Mike Pultz [mike@mikepultz.com][8] | Licensed under BSD-3-Clause
+ PHPMailer by Free Software Foundation, Inc. | Lincesed under GNU Lesser General Public License v2.1
+ Cloudflare SDK by Cloudflare | Licensed under BSD-3-Clause
+ Chart.js by Nick Downie | Licensed under the MIT license
+ Guzzle by Michael Dowling [mtdowling@gmail.com][9] | Licensed under MIT
+ PSR Http Message by Framework Interoperability Group | Licensed under MIT
+ Composer | Licensed under MIT

[1]:	https://www.cloudflare.com/partners/hosting-provider/
[2]:	https://support.cloudflare.com/hc/en-us/articles/200168706-How-do-I-do-CNAME-setup-
[3]:	https://cf.tlo.xyz
[4]:	https://beta.cf.tlo.xyz
[5]:	https://api.cloudflare.com/
[6]:	https://www.cloudflare.com/ssl-for-saas-providers/
[7]:	http://www.hostloc.com/thread-386441-1-1.html
[8]:	mailto:mike@mikepultz.com
[9]:	mailto:mtdowling@gmail.com

[image-1]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot1.png
[image-2]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot2.png
[image-3]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot3.png
[image-4]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot4.png