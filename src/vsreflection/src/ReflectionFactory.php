<?php

namespace vsReflection;


final class ReflectionFactory
{
    /** @var string $className */
    private $className;

    public static function from(string $class): ?self
    {
        $self = new self($class);
        return is_object($self) ? $self : null;
    }

    public function __construct(string $class)
    {
        if (class_exists($class)) {
            $this->className = $class;
        }
    }

    private function reflectionClass(): ReflectionClass
    {
        return new ReflectionClass($this->className);
    }

    public function getMethods(): array
    {
        $methods = [];

        foreach ($this->reflectionClass()->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if(!$method->isStatic()){
                $name = $method->name;
                $class = $method->class;
    
                $methodName = ['name' => $name, 'class' => $class];
                $parameters = [];
                foreach ($method->getParameters() as $param) {
                    $names = (string)'$' . $param->name;
                    if (!$param->isOptional()) {
                        $parameters[] = $names;
                    }
                }
    
                $methods[] = array_merge($methodName, [
                    'parameter' => implode(', ', $parameters)
                ]);

            }
        }
        return $methods;
    }
}
