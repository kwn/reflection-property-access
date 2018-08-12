<?php

namespace Kwn\ReflectionPropertyAccess;

use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ReflectionPropertyAccessor implements PropertyAccessorInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws AccessException
     * @throws UnexpectedTypeException
     */
    public function setValue(&$objectOrArray, $propertyPath, $value): void
    {
        $this->assertObject($objectOrArray);

        try {
            $classReflection = new \ReflectionClass($objectOrArray);

            if (!$classReflection->hasProperty($propertyPath)) {
                throw new AccessException(sprintf(
                    'Property "%s" in class "%s" does not exist',
                    $propertyPath,
                    \get_class($objectOrArray)
                ));
            }

            $reflectionProperty = $classReflection->getProperty($propertyPath);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($objectOrArray, $value);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws AccessException
     * @throws UnexpectedTypeException
     */
    public function getValue($objectOrArray, $propertyPath)
    {
        $this->assertObject($objectOrArray);

        try {
            $classReflection = new \ReflectionClass($objectOrArray);

            if (!$classReflection->hasProperty($propertyPath)) {
                throw new AccessException(sprintf(
                    'Property "%s" does not exist in class "%s"',
                    $propertyPath,
                    \get_class($objectOrArray)
                ));
            }

            $reflectionProperty = $classReflection->getProperty($propertyPath);
            $reflectionProperty->setAccessible(true);

            return $reflectionProperty->getValue($objectOrArray);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isWritable($objectOrArray, $propertyPath): bool
    {
        if (!\is_object($objectOrArray)) {
            return false;
        }

        return $this->doesPropertyExist($objectOrArray, $propertyPath);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isReadable($objectOrArray, $propertyPath): bool
    {
        if (!\is_object($objectOrArray)) {
            return false;
        }

        return $this->doesPropertyExist($objectOrArray, $propertyPath);
    }

    /**
     * @throws AccessException
     */
    private function assertObject($objectOrArray): void
    {
        if (!\is_object($objectOrArray)) {
            throw new AccessException('Only objects are supported');
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function doesPropertyExist($objectOrArray, $propertyPath): bool
    {
        try {
            $classReflection = new \ReflectionClass($objectOrArray);

            return $classReflection->hasProperty($propertyPath);
        } catch (\ReflectionException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
