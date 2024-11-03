<?php declare(strict_types=1);

namespace Ideade\TypedCollections\Tests\ExampleClassCollection;

use PHPUnit\Framework\TestCase;

final class JsonSerializableTest extends TestCase
{
    public function testSerializeCollectionOfExamples(): void
    {
        $collection = new ExampleCollection([
            new Example('123', '456'),
            new Example('789', '789', new Example('111', '222')),
        ]);

        $expectedResult = '[{"exampleField1":"123","exampleField2":"456","exampleField3":null},{"exampleField1":"789","exampleField2":"789","exampleField3":{"exampleField1":"111","exampleField2":"222","exampleField3":null}}]';
        $this->assertEquals($expectedResult, json_encode($collection));
    }
}
