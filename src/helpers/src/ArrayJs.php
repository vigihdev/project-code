<?php

namespace helpers;

class ArrayJs extends Collection
{

    public function __construct(array $arrayIndex)
    {
        if(!ArrayHelper::isIndexed($arrayIndex)){
            throw new \Exception('Not Suport Array');
        }
        parent::__construct($arrayIndex);
    }

    public function length(){}
}
