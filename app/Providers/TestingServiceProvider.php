<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;
use Inertia\Testing\AssertableInertia;
use Override;

class TestingServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void {}

    public function boot(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource): object {
            expect($this->prop($key))->toEqual($resource->response()->getData(true));

            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource): object {
            $this->hasResource("{$key}.data", $resource);
            expect($this->prop($key))->toHaveKeys(['dat', 'links', 'meta']);

            return $this;
        });
    }
}
