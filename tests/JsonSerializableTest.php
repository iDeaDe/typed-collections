<?php declare(strict_types=1);

namespace Ideade\TypedCollections\Tests;

use Ideade\TypedCollections\ScalarCollections\IntCollection;
use PHPUnit\Framework\TestCase;
use function json_encode;

final class JsonSerializableTest extends TestCase
{
    public function testEncodeIntCollection(): void
    {
        $collection = new IntCollection(array_fill(0, 13, 5));

        $expectedResult = '[5,5,5,5,5,5,5,5,5,5,5,5,5]';
        $encoded = json_encode($collection);

        $this->assertEquals($expectedResult, $encoded);
    }
}
