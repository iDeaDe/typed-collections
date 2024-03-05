<?php declare(strict_types=1);

namespace Ideade\TypedCollections\ScalarCollections;


use Ideade\TypedCollections\TypedCollection;

/**
 * @template K of array-key
 *
 * @template-extends TypedCollection<K, float>
 */
final class FloatCollection extends TypedCollection
{
    protected function valueType(): string
    {
        // gettype returns "double" in case of a float
        return 'double';
    }
}