<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\ScalarCollections;

use Ideade\TypedCollections\TypedCollection;

/**
 * @template-extends TypedCollection<string>
 */
final class BoolCollection extends TypedCollection
{
    protected function valueType(): string
    {
        return 'boolean';
    }
}
