<?php

declare(strict_types=1);

namespace Ideade\TypedCollections;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use JsonSerializable;

use function array_values;
use function count;
use function get_class;
use function get_object_vars;
use function gettype;
use function is_object;
use function is_scalar;
use function reset;
use function sprintf;

/**
 * @template V
 *
 * @template-implements Iterator<V>
 * @template-implements ArrayAccess<array-key, V>
 */
abstract class TypedCollection implements Iterator, ArrayAccess, Countable, JsonSerializable
{
    /**
     * @var array<array-key, V>
     */
    private array $items = [];

    /**
     * @psalm-return 'boolean'|'integer'|'double'|'string'|class-string<V>
     */
    abstract protected function valueType(): string;

    /**
     * @param array<array-key, V> $items
     */
    public function __construct(array $items = [])
    {
        if (count($items) > 0) {
            $this->setItems($items);
        }
    }

    /**
     * @return V
     */
    public function current(): mixed
    {
        return $this->items[key($this->items)];
    }

    public function next(): void
    {
        next($this->items);
    }

    /**
     * @return array-key|null
     */
    public function key(): int|string|null
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * @param array-key $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param array-key $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param array-key|null $offset
     * @param V $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->add($value);
        } else {
            $this->addByKey($offset, $value);
        }
    }

    /**
     * @param array-key $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function jsonSerialize(): array
    {
        if ($this->count() === 0) {
            return [];
        }

        if (is_scalar(array_values($this->items)[0])) {
            return $this->items;
        }

        $result = [];

        /** @var object $item */
        foreach ($this->items as $item) {
            if ($item instanceof JsonSerializable) {
                /** @var array $itemResult */
                $itemResult = $item->jsonSerialize();

                $result[] = $itemResult;
            } else {
                $result[] = get_object_vars($item);
            }
        }

        return $result;
    }

    /**
     * @param array-key $key
     * @return ?V
     */
    public function get(int|string $key): mixed
    {
        return $this->offsetGet($key);
    }

    /**
     * @param array-key $key
     * @param V $value
     */
    public function addByKey(int|string $key, mixed $value): self
    {
        $this->testValueType($value);
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param V $item
     */
    public function add(mixed $item): self
    {
        $this->testValueType($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param array-key $key
     */
    public function remove(int|string $key): self
    {
        $this->offsetUnset($key);

        return $this;
    }

    /**
     * @param array<array-key, V> $items
     */
    public function setItems(array $items): self
    {
        // TODO: add item index to exception
        foreach ($items as $item) {
            $this->testValueType($item);
        }

        $this->items = $items;

        return $this;
    }

    /**
     * @param V $item
     */
    private function testValueType(mixed $item): void
    {
        $collectionType = $this->valueType();
        $itemType       = gettype($item);

        $isTypeCorrect = $itemType === 'object'
            ? $item instanceof $collectionType
            : $itemType === $collectionType;

        if (!$isTypeCorrect) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected value of type "%s", got "%s"',
                    $collectionType,
                    is_object($item) ? get_class($item) : gettype($item)
                )
            );
        }
    }
}
