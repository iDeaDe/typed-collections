# Typed collections

PHP collections with runtime type checking

### Installation:

`composer require ideade/typed-collections:~1.0`

### Supported types
- Scalar: bool, int, float, string :white_check_mark:
- Any of your classes :white_check_mark:
- resource(including closed), array :x:

### Usage

1. Define your collection class, or use one of scalar(from Ideade\TypedCollections\ScalarCollections):
```php
use Ideade\TypedCollections\TypedCollection;

final class ExampleCollection extends TypedCollection
{
    protected function valueType() : string
    {
        return Example::class;
    }
}
```
2. Use it as a normal array, or use the following methods:
```php

$collection = new ExampleCollection();

// Add an element
$collection->add(new Example());

// Get item by key
$collection->get(0);

// Add an element by key
$collection->addByKey(0, new Example());

// Delete element by key
$collection->remove(0);

// Set collection items (check the type of each item)
$collection->setItems([new Example(), new Example(), new Example()]);

```
