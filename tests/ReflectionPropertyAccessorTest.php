<?php

namespace Kwn\ReflectionPropertyAccess;

use PHPUnit\Framework\TestCase;

class ReflectionPropertyAccessorTest extends TestCase
{
    /**
     * @var ReflectionPropertyAccessor
     */
    private $reflectionPropertyAccessor;

    protected function setUp()
    {
        $this->reflectionPropertyAccessor = new ReflectionPropertyAccessor();
    }

    /**
     * @test
     */
    public function setValue(): void
    {
        $object = new class {
            /** @var string */
            private $foo;

            public function getFoo(): string
            {
                return $this->foo;
            }
        };

        $this->reflectionPropertyAccessor->setValue($object, 'foo', 'bar');

        self::assertEquals('bar', $object->getFoo());
    }

    /**
     * @test
     */
    public function getValue(): void
    {
        $object = new class {
            /** @var string */
            private $foo = 'bar';
        };

        self::assertEquals('bar', $this->reflectionPropertyAccessor->getValue($object, 'foo'));
    }
}
