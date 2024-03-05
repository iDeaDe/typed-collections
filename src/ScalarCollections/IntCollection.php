<?php declare(strict_types=1);

namespace Ideade\TypedCollections\ScalarCollections;

use Ideade\TypedCollections\TypedCollection;

/**
 * @template K of array-key
 *
 * @template-extends TypedCollection<K, int>
 */
final class IntCollection extends TypedCollection
{
    protected function valueType(): string
    {
        return 'integer';
    }
}