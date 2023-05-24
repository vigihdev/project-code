<?php

namespace vsReflection;

class ReflectionClass extends \ReflectionClass
{

    public function getMethods(int $filter = null): array
    {
        $methods = [];
        foreach (parent::getMethods($filter) as $method) {
            $class = $method->class;
            $name = $method->name;
            $methods[] = new ReflectionMethod($class,$name);
        }
        return $methods;
    }
}