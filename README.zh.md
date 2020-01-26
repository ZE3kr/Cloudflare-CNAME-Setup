# Cloudflare CNAME 接入

[Cloudflare Hosting Partner][1] 可以使用此项目为用户提供一个可视化的面板，可以让用户免费的使用 [CNAME 接入][2]。

[cf.tlo.xyz][3] 是安装了这个面板的最新稳定版的网站，值得你的信任。

<blockquote>
注意：[cf.tlo.xyz][3] 的 Host API key 似乎被 Cloudflare 停用了。因此，现在你不能在这个网站上使用密码登录，亦不能添加新的 CNAME 接入的域名。对于已经有 CNAME 接入的用户，依然可以通过 Global API Key（不是密码）的方式登录本站，管理自己域名下的 DNS 记录。
</blockquote>

## 安装

如果你不想使用上述已经安装好了的面板，你可以在自己的服务器上安装。现有多种方式将其安装到服务器，[详情请参见 Wiki 中的安装方法][5]。

## 此面板的特性

+ 管理你的所有 DNS 记录。此面板使用了 [Cloudflare API v4][6]，所以支持各种格式的 DNS 记录。
+ 高级统计。你可以查看**过去一整年的统计信息**，而不仅仅是一个月。
+ 同时支持 NS 接入。此面板提供了 NS 接入信息，所以你可以随时切换到 Cloudflare DNS。此外，这个面板也支持 DNSSEC。
+ 同时支持 IP 接入。你可以看到 DNS 的 Anycast IPv4 和 IPv6 信息，这样你可以安全地在根域名下使用第三方 DNS。
+ 适配移动设备。
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
+ 可以免费支持四级域名下的 SSL！例如像 `dev.project.example.com` 这样的域名，Cloudflare 也会自动签发 SSL 证书，这是因为 CNAME 接入签发的是 [SSL for SaaS][7]，它会自动地为每一个子域名签发证书。

## 使用 Cloudflare 的好处

**你不需要在服务器端安装任何软件**。只需要在这个面板配置好源站服务器信息，删除已有记录并 CNAME 到 Cloudflare 的服务器，或者直接使用 Cloudflare DNS 即可！

+ 无限 DDOS 防御
+ 全球 CDN。你的网站会因此变得更快。
+ I'm Under Attack™ 模式可以自动清洗恶意流量。
+ Always Online ™ 让你的网站永远在线。
+ 支持 Page Rules. 你可以自定义缓存规则，配置 301 或 302 跳转以及更多。

## 屏幕截图

<img src="https://tloxygen.com/wp-content/uploads/uploads/cloudflare/zh1.png" />
<img src="https://tloxygen.com/wp-content/uploads/uploads/cloudflare/zh2.png" width="433" />

## Open sourced software used in this project

This project was based on a [HOSTLOC topic][8].

+ jQuery | MIT License
+ popper.js | MIT License
+ Bootstrap | MIT License
+ Chart.js by Nick Downie | MIT License
+ Guzzle by Michael Dowling [mtdowling@gmail.com][9] | MIT License
+ PSR Http Message by Framework Interoperability Group | MIT License
+ Composer | MIT License
+ Net\_DNS2 by Mike Pultz [mike@mikepultz.com][10] | BSD-3-Clause License
+ PHPMailer by Free Software Foundation, Inc. | GNU Lesser General Public License v2.1
+ Cloudflare SDK by Cloudflare | BSD-3-Clause

[1]:	https://www.cloudflare.com/partners/hosting-provider/
[2]:	https://support.cloudflare.com/hc/en-us/articles/200168706-How-do-I-do-CNAME-setup-
[3]:	https://cf.tlo.xyz
[4]:	https://beta.cf.tlo.xyz
[5]:	https://github.com/ZE3kr/Cloudflare-CNAME-Setup/wiki/%E5%AE%89%E8%A3%85
[6]:	https://api.cloudflare.com/
[7]:	https://www.cloudflare.com/ssl-for-saas-providers/
[8]:	http://www.hostloc.com/thread-386441-1-1.html
[9]:	mailto:mtdowling@gmail.com
[10]:	mailto:mike@mikepultz.com
