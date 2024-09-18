<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\Tests\ExampleClassCollection;

final class Example
{
    public function __construct(
        public string $exampleField1,
        public string $exampleField2,
        public ?Example $exampleField3 = null,
    ) {}
}
