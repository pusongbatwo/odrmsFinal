<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client as GoogleClient;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $client = new GoogleClient();
            $client->setAuthConfig($config['serviceAccount']);
            $client->addScope('https://www.googleapis.com/auth/drive');

            $service = new \Google\Service\Drive($client);

            $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? null);

            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });
    }
}
