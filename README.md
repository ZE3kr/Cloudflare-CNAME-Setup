# Cloudflare CNAME Setup

This project allows [Cloudflare Hosting Partner][1] to provide a panel for customers, which allows customers to have [CNAME setup][2] for **free**.

[查看中文版本][3]

[cf.tlo.xyz][4] is the official site installed the stable version of this panel. The software is up-to-date and you can trust.

The [beta.cf.tlo.xyz][5] is for the beta version.

## Installation(Docker)

### Guide 

```
docker run -d \
           -e HOST_KEY=replace_with_your_api_key \
           -e HOST_MAIL=replace_with_your_mail   \
           -p the_port_you_like:80               \
           fanvinga/docker-cfpanel
```
Just replace the environment variable with yours then run !
* You can get the dockerfile by this [link](https://github.com/fanvinga/dockerfiles/tree/master/cfpanel)
## Installation(Manual)

If you don’t want to use the preinstalled panel above, you can install this panel on your server. [For more information, please see the Wiki][6].

## Features of this panel

+ Manage all your DNS records in one place. Using the [Cloudflare API v4][7], this project supports various types of DNS records.
+ Advanced Analytics. You can see the analytics report for the **previous year**, rather than a month.
+ NS setup is supported. This panel provides NS setup information so you can switch to Cloudflare DNS at any time. The DNSSEC feature is also supported.
+ IP setup is supported. This service provides the anycast IPv4 and IPv6 of the CDN. You can use this service for the root domain safely.
+ Supports multi-languages.

## How to switch to this panel from NS setup

1. Backup your existing record
2. Switch your domain to another DNS services and restore the DNS record (Optional)
3. Delete your domain on Cloudflare (Might have a downtime if you have not done step 2)
4. Re-add your domain on the panel
5. Setup the DNS records on this panel
6. Delete the existing DNS record and CNAME to Cloudflare (Only if you have done step 2)

## Advantages for CNAME setup

+ You can use any DNS provider you like, which is much more flexible.
+ Use Cloudflare CDN as a backup server or use multiple CDNs.
+ Support fourth-level subdomain SSL for free! For example, the domain like `dev.project.example.com` is ready for HTTPS. This is because, for CNAME setup, the Cloudflare issues the [SSL for SaaS][8], which issues the SSL separately for each sub-domain immediately. 

## Advantages when you use Cloudflare

**You don’t need to install any software on your origin**. Just configure your origin server information on the panel, delete the existing DNS record and CNAME to Cloudflare or switch to Cloudflare DNS, and you are done!

+ Unmetered Mitigation of DDoS
+ Global CDN. Your website will be much faster.
+ I'm Under Attack™ mode
+ Always Online ™
+ Page Rules included. You can customize the cache behavior, set up 301/302 redirect and much more.

## Screenshot

![Screenshot1][image-1]
![Screenshot2][image-2]
![Screenshot3][image-3]
![Screenshot4][image-4]

## Open sourced software used in this project

This project was based on a [HOSTLOC topic][9].

+ Amaze UI by Amaze UI Team | Licensed under MIT
+ Net\_DNS2 by Mike Pultz [mike@mikepultz.com][10] | Licensed under BSD-3-Clause
+ PHPMailer by Free Software Foundation, Inc. | Lincesed under GNU Lesser General Public License v2.1
+ Cloudflare SDK by Cloudflare | Licensed under BSD-3-Clause
+ Chart.js by Nick Downie | Licensed under the MIT license
+ Guzzle by Michael Dowling [mtdowling@gmail.com][11] | Licensed under MIT
+ PSR Http Message by Framework Interoperability Group | Licensed under MIT
+ Composer | Licensed under MIT

[1]:	https://www.cloudflare.com/partners/hosting-provider/
[2]:	https://support.cloudflare.com/hc/en-us/articles/200168706-How-do-I-do-CNAME-setup-
[3]:	https://github.com/ZE3kr/Cloudflare-CNAME-Setup/blob/master/README.zh.md
[4]:	https://cf.tlo.xyz
[5]:	https://beta.cf.tlo.xyz
[6]:	https://github.com/ZE3kr/Cloudflare-CNAME-Setup/wiki/%E5%AE%89%E8%A3%85
[7]:	https://api.cloudflare.com/
[8]:	https://www.cloudflare.com/ssl-for-saas-providers/
[9]:	http://www.hostloc.com/thread-386441-1-1.html
[10]:	mailto:mike@mikepultz.com
[11]:	mailto:mtdowling@gmail.com

[image-1]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot1.png
[image-2]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot2.png
[image-3]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot3.png
[image-4]:	https://cdn.landcement.com/uploads/cloudflare/Screenshot4.png
