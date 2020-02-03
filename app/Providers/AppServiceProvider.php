<?php

namespace App\Providers;

use App\Services\Classifier\ClassifierServiceInterface;
use App\Services\Classifier\FakeClassifierService;
use App\Services\Exporter\ExporterCsv;
use App\Services\Exporter\ExporterInterface;
use App\Services\Importer\CsvImporter;
use App\Services\Importer\ImporterInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Service providers, registered only in 'local' environment
     *
     * @var array
     */
    private $localProviders = [
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        \Huangdijia\IdeHelper\IdeHelperServiceProvider::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLocalProviders();
    }

    /**
     * Registering service providers only in local environment
     */
    private function registerLocalProviders(): void
    {
        if ($this->app->isLocal()) {
            foreach ($this->localProviders as $localProvider) {
                if (class_exists($localProvider)) {
                    $this->app->register($localProvider);
                }
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ImporterInterface::class, function ($app) {
            return new CsvImporter(Auth::user());
        });

        $this->app->bind(ClassifierServiceInterface::class, FakeClassifierService::class);
        $this->app->bind(ExporterInterface::class, ExporterCsv::class);
    }
}
