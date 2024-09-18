<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\Tests;

use Ideade\TypedCollections\ScalarCollections\StringCollection;
use PHPUnit\Framework\TestCase;

final class ArrayAccessTest extends TestCase
{
    public function testAdd(): void
    {
        $collection   = new StringCollection();
        $collection[] = 'Hello';
        $collection[] = 'World';

        $this->assertCount(2, $collection);
        $this->assertContainsEquals('World', $collection);
    }

    public function testAddByKey(): void
    {
        $collection           = new StringCollection();
        $collection['ip']     = '127.0.0.1';
        $collection['domain'] = 'example.com';

        $this->assertCount(2, $collection);
        $this->assertContainsEquals('127.0.0.1', $collection);
    }

    public function testUnset(): void
    {
        $collection   = new StringCollection(['Avatar']);

        $this->assertCount(1, $collection);

        unset($collection[0]);

        $this->assertCount(0, $collection);
    }

    public function testOffsetGet(): void
    {
        $collection = new StringCollection(['hello' => 'World']);

        $this->assertEquals('World', $collection['hello']);
        $this->assertEmpty($collection['world']);
    }
}
