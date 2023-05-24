<?php

namespace vscomposer;

use Webmozart\PathUtil\Path;
use Composer\Autoload\ClassLoader;
use helpers\PathInfos;
use helpers\PathsHelper;

/**
 * Class detail from file `composer/autoload_psr4.php`
 */

final class AutoloadPsr4
{
    /** @var string $filename */
    private $filename;

    /** @var ClassLoader|object|null $registeredLoaders */
    private $registeredLoaders;

    /** @var array $prefixLengthsPsr4 `$array = ['a' => 'b']` */
    private $prefixLengthsPsr4;

    /**
     * from file `composer/autoload_psr4.php`
     * 
     * @param string $filename
     * @return self|null
     */
    public static function from(string $filename): ?self
    {
        $self = new self($filename);
        return $self->isValid() ? $self : null;
    }

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        if ( $this->isValid() ) {
          
            $files = include $filename;
            $vendorDir = PathsHelper::from($filename)->endOf('vendor');
            
            if( is_array($files) && !empty($files) && is_dir($vendorDir)){
                $loader = new ClassLoader($vendorDir);
                foreach ($files as $prefix => $paths) {
                    $pathArr = is_array($paths) ? $paths : [$paths];
                    $prefix = str_ends_with($prefix,'\\') ? $prefix : $prefix . '\\';

                    $loader->addPsr4($prefix,$pathArr);
                    $this->prefixLengthsPsr4[$prefix[0]][$prefix] = strlen($prefix);
                   
                }
                $loader->register();
                if(isset($loader->getRegisteredLoaders()[$vendorDir])){
                    $this->registeredLoaders = $loader->getRegisteredLoaders()[$vendorDir];
                }
            }
        }
    }

    private function isValid(): bool
    {
        return file_exists($this->filename) && PathInfos::from($this->filename)->basename === 'autoload_psr4.php';
    }

    public function getRegisteredLoaders(): ?ClassLoader
    {
        return $this->registeredLoaders;
    }
}
