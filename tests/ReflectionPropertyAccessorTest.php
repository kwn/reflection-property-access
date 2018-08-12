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

    public function testSetValue(): void
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

    public function testGetValue(): void
    {
        $object = new class {
            /** @var string */
            private $foo = 'bar';
        };

        self::assertEquals('bar', $this->reflectionPropertyAccessor->getValue($object, 'foo'));
    }

    public function testIsWritable(): void
    {
        $object = new class {
            /** @var string */
            private $foo;
        };

        self::assertTrue($this->reflectionPropertyAccessor->isWritable($object, 'foo'));
    }

    public function testIsNotWritable(): void
    {
        $object = new class {
            /** @var string */
            private $foo;
        };

        self::assertFalse($this->reflectionPropertyAccessor->isWritable($object, 'fizbiz'));
    }

    public function testIsReadable(): void
    {
        $object = new class {
            /** @var string */
            private $foo;
        };

        self::assertTrue($this->reflectionPropertyAccessor->isReadable($object, 'foo'));
    }

    public function testIsNotReadable(): void
    {
        $object = new class {
            /** @var string */
            private $foo;
        };

        self::assertFalse($this->reflectionPropertyAccessor->isReadable($object, 'fizbiz'));
    }
}
