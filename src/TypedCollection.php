<?php declare(strict_types=1);

namespace Ideade\TypedCollections;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use JsonSerializable;

/**
 * @template K of array-key
 * @template V of mixed
 *
 * @template-implements Iterator<V>
 * @template-implements ArrayAccess<K, V>
 */
abstract class TypedCollection implements Iterator, ArrayAccess, Countable, JsonSerializable
{
    /**
     * @var array<int, K>
     */
    private array $keys = [];
    /**
     * @var array<K, V>
     */
    private array $items = [];
    /**
     * @psalm-suppress PropertyNotSetInConstructor The property is initialized by the rewind() method
     */
    private int $pointer;

    /**
     * @return 'boolean'|'integer'|'double'|'string'|class-string
     */
    abstract protected function valueType(): string;

    /**
     * @param array<K, V> $items
     */
    public function __construct(array $items = [])
    {
        $this->rewind();
        $this->setItems($items);
    }

    /**
     * @return V
     */
    public function current(): mixed
    {
        return $this->items[$this->keys[$this->pointer]];
    }

    public function next(): void
    {
        ++$this->pointer;
    }

    /**
     * @return K
     */
    public function key(): mixed
    {
        return $this->keys[$this->pointer];
    }

    public function valid(): bool
    {
        return isset($this->keys[$this->pointer], $this->items[$this->keys[$this->pointer]]);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    /**
     * @param K $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param K $offset
     * @return ?V
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param K $offset
     * @param V $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->testValueType($value);
        $currentOffset        = $this->keys[$this->pointer] ?? null;
        $this->items[$offset] = $value;
        $this->fillKeys();

        if (!is_null($currentOffset)) {
            $this->refreshKeyPointer($currentOffset);
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if (!isset($this->items[$offset])) {
            return;
        }

        unset($this->items[$offset]);

        $key = $this->keys[$this->pointer];

        if ($key === $offset) {
            if ($this->pointer === array_key_last($this->keys)) {
                $this->rewind();
            } else {
                $this->next();
            }
        } else {
            $this->fillKeys();
            $this->refreshKeyPointer($key);
        }
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

        $serialized = [];

        foreach ($this->items as $item) {
            if ($item instanceof JsonSerializable) {
                /**
                 * We can't know what an object will serialize into,
                 * since it can be literally any object with any logic
                 *
                 * @psalm-suppress MixedAssignment
                 */
                $serialized[] = $item->jsonSerialize();
            } else {
                $serialized[] = $item;
            }
        }

        return $serialized;
    }

    /**
     * @param K $key
     * @return ?V
     */
    public function get(mixed $key): mixed
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return null;
    }

    /**
     * @param K $key
     * @param V $value
     */
    public function addByKey(mixed $key, mixed $value): self
    {
        $this->offsetSet($key, $value);
        return $this;
    }

    /**
     * @param V $item
     */
    public function add(mixed $item): self
    {
        $this->testValueType($item);
        /**
         * @psalm-suppress InvalidPropertyAssignmentValue The method is not supposed to write to the array by key
         */
        $this->items[] = $item;
        $this->fillKeys();
        return $this;
    }

    /**
     * @param K $key
     */
    public function remove(mixed $key): self
    {
        $this->offsetUnset($key);
        return $this;
    }

    /**
     * @param array<K, V> $items
     */
    public function setItems(array $items): self
    {
        $this->items = [];

        foreach ($items as $key => $value) {
            $this->addByKey($key, $value);
        }

        return $this;
    }

    private function fillKeys(): void
    {
        $this->keys = array_keys($this->items);
    }

    /**
     * @param K $key
     */
    private function refreshKeyPointer(mixed $key): void
    {
        if (($pointer = array_search($key, $this->keys, true)) !== false) {
            $this->pointer = $pointer;
        } else {
            $this->rewind();
        }
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
                    $this->getDisplayableValueType($item)
                )
            );
        }
    }

    /**
     * @param V $value
     */
    private function getDisplayableValueType(mixed $value): string
    {
        if (is_object($value)) {
            return get_class($value);
        }

        return gettype($value);
    }
}