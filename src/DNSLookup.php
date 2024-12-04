<?php

namespace Orlo\DnsHostnameExpansion;

class DNSLookup
{
    /**
     * @param string $host_string - e.g. elasticsearch7.srv.socialsignin.net
     * @return string[] e.g. [ "https://172.16.11.50:9200", "https://172.16.11.51:9200" ]
     *
     * Returns a list of strings where the DNS lookup of the $host_string is expanded to include prefix/suffix on each A record returned.
     */
    public static function expandFromDnsALookup(string $host_string, string $prefix = "https://", string $suffix = ":9200"): array
    {
        // '.' at the end is to try and stop DNS doing a local domain search on it.
        // we 'could' have used SRV records, which would give us proto, port and priorities ... but we're too lazy.
        $dns = dns_get_record("{$host_string}.", DNS_A);
        if (is_array($dns) && count($dns) > 1) {
            return array_map(fn(array $record): string => "{$prefix}{$record['ip']}{$suffix}", $dns);
        } else {
            error_log(__FILE__ . " - warning: DNS lookup failed?" . json_encode(['host_string' => $host_string, 'dns_response' => $dns]));
        }
        // return a default
        return ["{$prefix}{$host_string}{$suffix}"];
    }

}