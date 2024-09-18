<?php

declare(strict_types=1);

namespace Ideade\TypedCollections\Tests\ExampleClassCollection;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class IncorrectValueTypeTest extends TestCase
{
    public function testScalar(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ExampleCollection())
            ->add(true);
    }

    public function testAnotherObject(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ExampleCollection())
            ->add(new DateTime());
    }
}
