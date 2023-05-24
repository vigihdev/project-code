<?php

namespace vscomposer;

use helpers\Collection;
use Spatie\Regex\Regex;
use Webmozart\Glob\Glob;
use Webmozart\PathUtil\Path;

final class PsrComposer
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

    public function withLoaderAutoloadPsr4(): array
    {
        $psr4 = [];
        foreach ($this->autoloadPsr4 as $autoloadPsr4) {
            $psr4[] = AutoloadPsr4::from($autoloadPsr4);
        }
        return $psr4;
    }

    /**
     * `$result = ['filename'];`
     * @return array
     */
    public function getAutoloadStatic(): array
    {
        return $this->autoloadStatic;
    }

    /**
     * `$result = ['filename'];`
     * @return array
     */
    public function getAutoloadReal(): array
    {
        return $this->autoloadReal;
    }

    /**
     * `$result = ['namespace' => 'path'];`
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

}
