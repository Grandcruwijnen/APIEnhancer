# Grandcruwijnen API Enhancer

Based on the outdated [MSP APIEnhancer](https://github.com/magespecialist/m2-MSP_APIEnhancer)

### Install procedure
```
composer require grandcruwijnen/apienhancer
php bin/magento module:enable Grandcruwijnen_APIEnhancer
php bin/magento setup:upgrade
```

## Varnish configuration change
Add the following code to your Varnish configuration file at the beginning of **vcl_hash** section:
```
hash_data(regsub(std.tolower(req.http.Authorization), "^bearer\s\x22(\w+?):\w+?\x22", "\1"));
```

This would result into something like:
```
import std
...
sub vcl_hash {
    hash_data(regsub(std.tolower(req.http.Authorization), "^bearer\s\x22(\w+?):\w+?\x22", "\1"));
    ...
}
...
