<?php


use PHPUnit\Framework\TestCase;
use helpers\PathInfos;
use vscomposer\Loaders;
use Webmozart\PathUtil\Path;

class VscomposerTest extends TestCase
{
    public function testLoaders()
    {
        $vendor = Path::join(...[__DIR__,'../../..','vendor']);
        $basePaht = Path::join([$vendor,'..']);

        $this->assertEquals(PathInfos::from($vendor)->isDir(),true);
        $this->assertEquals(PathInfos::from($basePaht)->isDir(),true);

        $loader = Loaders::from($basePaht);
    }
}
