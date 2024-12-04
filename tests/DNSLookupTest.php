<?php

namespace Orlo\DnsHostnameExpansion\Test;

use Orlo\DnsHostnameExpansion\DNSLookup;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress UnusedClass
 */
class DNSLookupTest extends TestCase
{

    public function testBasic(): void
    {
        $prefix = "prefix";
        $suffix = "suffix";

        $record = DNSLookup::expandFromDnsALookup("elasticsearch7.srv.socialsignin.net", $prefix, $suffix);

        $this->assertNotEmpty($record);

        $this->assertGreaterThan(2, count($record));

        foreach ($record as $string) {
            $this->assertStringContainsString("prefix", $string);
            $this->assertStringContainsString("suffix", $string);
            $this->assertMatchesRegularExpression("/^$prefix/", $string);
            $this->assertMatchesRegularExpression("/[a-z0-9-]+$suffix\$/", $string);
        }
    }

    public function testFallback(): void
    {
        $prefix = "prefix";
        $suffix = "suffix";

        $record = DNSLookup::expandFromDnsALookup("doesnotexist.srv.socialsignin.net", $prefix, $suffix);

        $this->assertNotEmpty($record);

        $this->assertEquals(["{$prefix}doesnotexist.srv.socialsignin.net{$suffix}"], $record);

    }

}
