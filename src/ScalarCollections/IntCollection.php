<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\ScalarCollections;

use Ideade\TypedCollections\TypedCollection;

/**
 * @template-extends TypedCollection<int>
 */
final class IntCollection extends TypedCollection
{
    protected function valueType(): string
    {
        return 'integer';
    }
}
