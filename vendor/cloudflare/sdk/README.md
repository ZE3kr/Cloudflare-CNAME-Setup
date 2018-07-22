# Cloudflare SDK (v4 API Binding for PHP 7)

[![Build Status](https://travis-ci.org/cloudflare/cloudflare-php.svg?branch=master)](https://travis-ci.org/cloudflare/cloudflare-php)

## Installation

The recommended way to install this package is via the Packagist Dependency Manager ([cloudflare/sdk](https://packagist.org/packages/cloudflare/sdk)). You can specific usage examples on the Cloudflare Knowledge Base under: [Cloudflare PHP API Binding](https://support.cloudflare.com/hc/en-us/articles/115001661191)

## Cloudflare API version 4

The Cloudflare API can be found [here](https://api.cloudflare.com/).
Each API call is provided via a similarly named function within various classes in the **Cloudflare\API\Endpoints** namespace:

- [x] [DNS Records](https://www.cloudflare.com/dns/)
- [x] Zones
- [x] User Administration (partial)
- [x] [Cloudflare IPs](https://www.cloudflare.com/ips/)
- [x] [Page Rules](https://support.cloudflare.com/hc/en-us/articles/200168306-Is-there-a-tutorial-for-Page-Rules-)
- [x] [Web Application Firewall (WAF)](https://www.cloudflare.com/waf/)
- [ ] Virtual DNS Management
- [x] Custom hostnames
- [x] Zone Lockdown and User-Agent Block rules
- [ ] Organization Administration
- [x] [Railgun](https://www.cloudflare.com/railgun/) administration
- [ ] [Keyless SSL](https://blog.cloudflare.com/keyless-ssl-the-nitty-gritty-technical-details/)
- [ ] [Origin CA](https://blog.cloudflare.com/universal-ssl-encryption-all-the-way-to-the-origin-for-free/)

Note that this repository is currently under development, additional classes and endpoints being actively added.

## Getting Started

```php
$key     = new Cloudflare\API\Auth\APIKey('user@example.com', 'apiKey');
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
$user    = new Cloudflare\API\Endpoints\User($adapter);
    
echo $user->getUserID();
```

## Contributions

We welcome community contribution to this repository. [CONTRIBUTING.md](CONTRIBUTING.md) will help you start contributing.

## Licensing 

Licensed under the 3-clause BSD license. See the [LICENSE](LICENSE) file for details.
