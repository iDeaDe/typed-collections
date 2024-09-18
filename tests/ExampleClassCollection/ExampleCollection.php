<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\Tests\ExampleClassCollection;

use Ideade\TypedCollections\TypedCollection;

/**
 * @template-extends TypedCollection<Example>
 */
final class ExampleCollection extends TypedCollection
{
    protected function valueType(): string
    {
        return Example::class;
    }
}
