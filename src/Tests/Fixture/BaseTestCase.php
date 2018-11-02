<?php

namespace App\Tests\Fixture;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class BaseTestCase extends TestCase
{
    protected function callObjectMethod($object, $methodName)
    {
        $args = func_get_args();
        array_shift($args); //$object
        array_shift($args); //$methodName
        $reflect = new \ReflectionClass($object);
        $method = $reflect->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }

    protected function setObjectAttribute($object, $attributeName, $value, $class = null)
    {
        $reflect = new \ReflectionClass(is_null($class)? $object: $class);
        $property = $reflect->getProperty($attributeName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
