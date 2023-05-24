<?php

namespace vscomposer;

use Composer\Autoload\ClassLoader;
use helpers\PathsHelper;
use helpers\Collection;
use Webmozart\PathUtil\Path;
use Spatie\Regex\Regex;
use Webmozart\Glob\Glob;

final class Loaders
{
    /** @var string $basepath */
    private $basepath;

    /** @var array $items */
    private $items;

    /** @var array $autoloadPsr4 */
    private $autoloadPsr4;

    /** @var array $autoloadStatic */
    private $autoloadStatic;

    /** @var array $autoloadReal */
    private $autoloadReal;

    /** @var ClassLoader $registeredLoaders */
    private $registeredLoaders;

    /** @var array $prefixLengthsPsr4 `$array = ['a' => 'b']` */
    private $prefixLengthsPsr4;

    public static function from(string $basepath): ?self
    {
        $self = new self($basepath);
        return $self->isValid() ? $self : null;
    }

    public function __construct(string $basepath)
    {
        $this->basepath = $basepath;
        if ($this->isValid()) {
            $this->items = Glob::glob(Path::join(...[$this->basepath, 'vendor/**/composer/**']));

            $this->autoloadReal = Collection::from($this->items)->filter(function ($item, $index) {
                return Regex::match('/\/composer\/autoload_real\.php/', $item)->hasMatch();
            })->all();

            $this->autoloadStatic = Collection::from($this->items)->filter(function ($item, $index) {
                return Regex::match('/\/composer\/autoload_static\.php/', $item)->hasMatch();
            })->all();

            $this->autoloadPsr4 = Collection::from($this->items)->filter(function ($item, $index) {
                return Regex::match('/\/composer\/autoload_psr4\.php/', $item)->hasMatch();
            })->all();
        }
    }

    private function isValid(): bool
    {
        return is_dir($this->basepath) && is_dir(Path::join(...[$this->basepath, 'vendor', 'composer']));
    }

    public function withAutoloadPsr4()
    {
        foreach ($this->autoloadPsr4 as $filename) {
            $files = include $filename;
            $vendorDir = PathsHelper::from($filename)->endOf('vendor');
    
            if (is_array($files) && !empty($files) && is_dir($vendorDir)) {
                $loader = new ClassLoader($vendorDir);
                foreach ($files as $prefix => $paths) {
                    $pathArr = is_array($paths) ? $paths : [$paths];
                    $prefix = str_ends_with($prefix, '\\') ? $prefix : $prefix . '\\';
    
                    $loader->addPsr4($prefix, $pathArr);
                    $this->prefixLengthsPsr4[$prefix[0]][$prefix] = strlen($prefix);
    
                }
                
                $loader->register();
                if (isset($loader->getRegisteredLoaders()[$vendorDir])) {
                    $this->registeredLoaders = $loader->getRegisteredLoaders()[$vendorDir];
                }
            }
        }
       
    }

    public function getRegisteredLoaders(): ?ClassLoader
    {
        return $this->registeredLoaders;
    }

    public function addPsr4(string $prefix, array $pathArr)
    {
        if(is_object($this->registeredLoaders)){
            $this->getRegisteredLoaders()->addPsr4($prefix,$pathArr);
            $this->getRegisteredLoaders()->register();
        }
    }
}
