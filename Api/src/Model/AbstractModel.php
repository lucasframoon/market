<?php

declare(strict_types=1);

namespace Src\Model;

use ReflectionClass;

abstract class AbstractModel
{

    /**
     * Used to get properties
     *
     * @param $property
     * @return mixed
     */
    public function __get($property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return null;
    }

    /**
     * Used to set properties
     *
     * @param $property
     * @param $value
     * @return void
     */
    public function __set($property, $value): void
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }
    }

    public function toArray(): array
    {
        $array = [];
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            if (!$property->isInitialized($this)) {
                continue;
            }

            $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }
}