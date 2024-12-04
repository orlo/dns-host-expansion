# TL;DR ?

ElasticSearch (for example) needs a list of hosts to talk to, for instance : 

 * https://host1:9200
 * https://host2:9200 etc


This allows you to have a single DNS record (e.g. in Route53) which responds with multiple A records, which then get formatted into an appropriate list, e.g

 * DNS record: elasticsearch.test.org resolves to 1.2.3.4, 1.2.3.5, 1.2.4.6
 * Becomes: https://1.2.3.4:9200, https://1.2.3.5:9200, https://1.2.3.6:9200

The prefix + suffix used in decoration are configurable and could be omitted.


In the event DNS lookup fails, we fall back to returning the passed in host - e.g. https://elasticsearch.test.org:9200

# Usage

 * composer require orlo/dns-host-expansion
 * ...
```php
<?php 
require_once(__DIR__ . '/vendor/autoload.php');
// ... 
$hosts = \Orlo\DnsHostnameExpansion\DNSLookup::expandFromDnsALookup("my.host.name", "https://", "9200);
// ...

```