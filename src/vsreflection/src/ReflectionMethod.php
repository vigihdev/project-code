<?php

namespace vsReflection;

class ReflectionMethod extends \ReflectionMethod
{
    
    public function __construct(object|string $objectOrMethod, string $method)
    {
        parent::__construct($objectOrMethod, $method);
    }

    public function getParameters(): array
    {
        $params = [];
        foreach (parent::getParameters() as $param) {
            $name = $param->name;
            $class = $param->getDeclaringClass()->name;
            $func = $param->getDeclaringFunction()->name;
            $refParameter = new ReflectionParameter(array($class,$func),$name);
            $params[] = $refParameter;
        }
        return $params;
    }
}