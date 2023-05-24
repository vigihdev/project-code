<?php

use PHPUnit\Framework\TestCase;
use helpers\ArrayHelper;
use helpers\Collection;
use helpers\PathsHelper;

class HelperTest extends TestCase
{
    static $ArrAssociative = ['a' => '1', 'b' => '2', 'c' => '3', 'd' => '4', 'e' => '5'];
    static $ArrIsIndexed = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

    public function testArray()
    {
        $diranme = __DIR__;
        $isIndexed = ArrayHelper::isIndexed(self::$ArrIsIndexed);
        $isAssociative = ArrayHelper::isAssociative(self::$ArrAssociative);
        
        $this->assertEquals(true, $isIndexed);
        $this->assertEquals(true, $isAssociative);
        $this->assertEquals('10', Collection::from(self::$ArrIsIndexed)->pop());

        $this->assertEquals('tests', PathsHelper::from($diranme)->end());
        $this->assertEquals('helpers', PathsHelper::from($diranme)->slice(-2,1)->end() );
    }
}
