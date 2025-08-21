<?php

namespace fmfrlx\Filamentsecretfield;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use fmfrlx\Filamentsecretfield\Commands\FilamentsecretfieldModuleCommand;
use fmfrlx\Filamentsecretfield\Testing\TestsFilamentsecretfieldModule;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentsecretfieldModuleServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filamentsecretfield';

    public static string $viewNamespace = 'filamentsecretfield';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToRunMigrations();
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filamentsecretfield/{$file->getFilename()}"),
                ], 'filamentsecretfield-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentsecretfieldModule);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'fmfrlx/filamentsecretfield';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [

        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            //   'create_nlmn-filamentsecretfield-module_table',
        ];
    }

    /**
     * @return array<string>
     */
    public static function getSeeder(): array
    {
        return [
            // 'NameOfSeeders',
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            // FilamentsecretfieldModuleCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
