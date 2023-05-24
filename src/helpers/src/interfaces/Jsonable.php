<?php

namespace helpers\interfaces;

interface Jsonable
{
    public function toJson(int $options = 0): string;
}
