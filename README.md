# Cloudflare CNAME Setup

## 马上就要开源了快 Star 一下吧

This project allows [Cloudflare Hosting Partner][1] to provide a panel for customers, which allows customers to have [CNAME setup][2] for **free**.

[cf.tlo.xyz][3] is the official site installed the stable version of this panel. The software is up-to-date and you can trusted.

The [beta.cf.tlo.xyz][4] is for the beta version.

## Features of this panel

+ Manage all your DNS records in one place. Using the [Cloudflare API v4][5], this project supports various types of DNS records.
+ Advanced Analytics. You can see the analytics report for the **previous year**, rather than a month.
+ NS setup is supported. This panel provide NS setup information so you can switch to Cloudflare DNS at any time. The DNSSEC feature is also supported.
+ IP setup is supported. This service provides the anycast IPv4 and IPv6 of the CDN. You can use this service for the root domain safely.
+ Supports multi languages.

## How to switch to this panel from NS setup

1. Backup your existing record
2. Switch your domain to another DNS services and restore the DNS record (Optional)
3. Delete your domain on Cloudflare (Might have a down time if you have not down step 2)
4. Re-add your domain on the panel
5. Setup the DNS records on this panel
6. Delete the existing DNS record and CNAME to Cloudflare (Only if you have done step 2)

## Advantages for CNAME setup

+ You can use any DNS provider you like, which is much more flexible.
+ Use Cloudflare CDN as a backend server or use multiple CDNs.
+ Support fourth-level subdomain SSL for free! For example, the domain like `dev.project.example.com` is ready for HTTPS. This is because for CNAME setup, the Cloudflare issues the [SSL for SaaS][6], which issues the SSL separately for each sub-domain immediately. 

## Advantages when you using Cloudflare

**You don’t need to install any software on your backend**. Just configure your backend server information on the panel, delete the existing DNS record and CNAME to Cloudflare or switch to Cloudflare DNS, and you are done!

+ Unmetered Mitigation of DDoS
+ Global CDN. Your website will be much faster.
+ I'm Under Attack™ mode
+ Always Online ™
+ Page Rules included. You can customize the cache behavior, set up 301/302 redirect and much more.

## Screenshot

![Screenshot1](https://cdn.landcement.com/uploads/cloudflare/Screenshot1.png)
![Screenshot2](https://cdn.landcement.com/uploads/cloudflare/Screenshot2.png)
![Screenshot3](https://cdn.landcement.com/uploads/cloudflare/Screenshot3.png)
![Screenshot4](https://cdn.landcement.com/uploads/cloudflare/Screenshot4.png)

## Open sourced softwares used in this project

This project was based on a [HOSTLOC topic](http://www.hostloc.com/thread-386441-1-1.html).

+ Amaze UI by Amaze UI Team | Licensed under MIT
+ Net_DNS2 by Mike Pultz <mike@mikepultz.com> | Licensed under BSD-3-Clause
+ PHPMailer by Free Software Foundation, Inc. | Lincesed under GNU Lesser General Public License v2.1
+ Cloudflare SDK by Cloudflare | Licensed under BSD-3-Clause
+ Chart.js by Nick Downie | Licensed under the MIT license
+ Guzzle by Michael Dowling <mtdowling@gmail.com> | Licensed under MIT
+ PSR Http Message by Framework Interoperability Group | Licensed under MIT
+ Composer | Licensed under MIT

[1]:	https://www.cloudflare.com/partners/hosting-provider/
[2]:	https://support.cloudflare.com/hc/en-us/articles/200168706-How-do-I-do-CNAME-setup-
[3]:	https://cf.tlo.xyz
[4]:	https://beta.cf.tlo.xyz
[5]:	https://api.cloudflare.com/
[6]:	https://www.cloudflare.com/ssl-for-saas-providers/