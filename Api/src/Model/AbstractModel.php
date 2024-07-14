<?php

declare(strict_types=1);

namespace Src\Model;

use ReflectionClass;
use ReflectionException;

abstract class AbstractModel
{

    /**
     * Used to get properties
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        $property = $this->camelToSnake($name);
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }

    /**
     * Used to set properties
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function __set(string $name, mixed $value): void
    {
        $propertyName = $this->snakeToCamel($name);
        if (property_exists($this, $propertyName)) {
            $reflectionClass = new ReflectionClass($this);
            $property = $reflectionClass->getProperty($propertyName);

            $property->setAccessible(true);
            $value2 = $this->convertType($property, $value);
            $this->$propertyName =  $value2;
        }
    }

    /**
     * Convert instance to array, excluding private properties
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            if ($property->isPrivate()) {
                continue;
            }
            $propertyName = $this->camelToSnake($property->getName());
            $array[$propertyName] = $property->getValue($this);
        }

        return $array;
    }

    private function camelToSnake(string $input): string {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
    }

    private function snakeToCamel(string $input): string {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    /**
     * Convert value to the appropriate type
     *
     * @param $property
     * @param mixed $value
     * @return mixed
     */
    private function convertType($property, mixed $value): mixed {
        $propertyType = $property->getType();
        if ($propertyType) {
            $typeName = $propertyType->getName();

            switch ($typeName) {
                case 'float':
                    $value = (float) $value;
                    break;
                case 'int':
                    $value = (int) $value;
                    break;
                default:
                    $value = (string) $value;
                    break;
            }
        }
        return $value;
    }
}