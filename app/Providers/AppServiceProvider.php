<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\FileBasedMaintenanceMode;
use Illuminate\Foundation\CacheBasedMaintenanceMode;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind MaintenanceMode explicitly (mirrors framework default)
        $this->app->singleton(MaintenanceMode::class, function ($app) {
            $config = $app['config']->get('app.maintenance', ['driver' => 'file']);
            $driver = $config['driver'] ?? 'file';

            if ($driver === 'cache') {
                /** @var CacheFactory $factory */
                $factory = $app->make(CacheFactory::class);

                // signature in your version: __construct(CacheFactory $cache, string $key, int $ttlSeconds)
                return new CacheBasedMaintenanceMode($factory, 'maintenance:down', 60);
            }

            // default: file driver
            return new FileBasedMaintenanceMode(
                $app->storagePath().'/framework/down'
            );
        });
    }

    public function boot(): void {}
}
