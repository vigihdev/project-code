<?php

namespace vscomposer;

/**
 * Class detail from file `composer/autoload_static.php` Composer
 */

final class AutoloadStaticComposer
{
    /** @var string $filename */
    private $filename;

    /**
     * from file `composer/autoload_static.php`
     * 
     * @param string $filename
     * @return self|null
     */
    public static function from(string $filename): ?self
    {
        return new self($filename);
    }

    public function __construct(string $filename)
    {
    }
}
