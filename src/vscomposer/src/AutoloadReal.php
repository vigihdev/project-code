<?php

namespace vscomposer;

use Webmozart\PathUtil\Path;
use Composer\Autoload\ClassLoader;
use helpers\PathsHelper;

/**
 * Class detail from file `composer/autoload_real.php`
 */

final class AutoloadReal
{
    /** @var string $filename */
    private $filename;

    /**
     * from file `composer/autoload_real.php`
     * 
     * @param string $filename
     * @return self|null
     */
    public static function from(string $filename): ?self
    {
        return file_exists($filename) && Path::getFilename($filename) === 'autoload_real.php' ? new self($filename) : null;
    }

    public function __construct(string $filename)
    {
        if (file_exists($filename)) {
            include $filename;
            $classes = get_declared_classes();
            $getLoader = end($classes);
            $vendorDir = PathsHelper::from($filename)->endOf('vendor');
            if(is_callable(array($getLoader,'getLoader')) ){
                $exLoader = $getLoader::getLoader();
                if($exLoader instanceof ClassLoader){
                    $loader = new ClassLoader($vendorDir);
                }
            }

        }
    }
}
