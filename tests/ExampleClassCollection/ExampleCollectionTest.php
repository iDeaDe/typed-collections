<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\Tests\ExampleClassCollection;

use PHPUnit\Framework\TestCase;

final class ExampleCollectionTest extends TestCase
{
    public function testAddCorrectValue(): void
    {
        $collection = new ExampleCollection();

        $this->assertEmpty($collection);

        $user = new Example('1', '1');
        $collection->add($user);

        $this->assertContains($user, $collection);
        $this->assertCount(1, $collection);
        $this->assertArrayHasKey(0, $collection);
    }
}
