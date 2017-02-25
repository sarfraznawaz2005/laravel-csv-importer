<?php

namespace RGilyov\CsvImporter\Test;

use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $cachePath = __DIR__ . DIRECTORY_SEPARATOR .'files' . DIRECTORY_SEPARATOR . 'cache';

    protected $filesPath = __DIR__ . DIRECTORY_SEPARATOR .'files' . DIRECTORY_SEPARATOR . 'import';

    protected $cacheDriver = 'file';

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \RGilyov\CsvImporter\CsvImporterServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('cache.default', $this->cacheDriver);
        $app['config']->set('queue.default', 'redis');
        $app['config']->set('cache.stores.file', [
            'driver' => 'file',
            'path'   => $this->cachePath,
        ]);
        $app['config']->set('filesystems.default', 'local');
        $app['config']->set('filesystems.disks.local', [
            'driver' => 'local',
            'root'   => $this->filesPath,
        ]);
    }

    /**
     * @return string
     */
    protected function setCacheDriver(){}

    public function tearDown()
    {
        \Illuminate\Support\Facades\File::deleteDirectory($this->cachePath, true);
        \Illuminate\Support\Facades\File::deleteDirectory($this->filesPath, true);

        parent::tearDown();
    }
}