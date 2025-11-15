<?php

namespace AkhilDuggirala\LaravelLogEnhancer\Tests;

use AkhilDuggirala\LaravelLogEnhancer\LogEnhancerServiceProvider;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\TestCase;

class SmartInfoTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LogEnhancerServiceProvider::class,
        ];
    }

    /** @test */
    public function it_normalizes_collection_in_message(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with(['foo' => 'bar'], []);

        smart_info(collect(['foo' => 'bar']));
    }

    /** @test */
    public function it_normalizes_collection_in_context(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('test', ['numbers' => [1, 2, 3]]);

        smart_info('test', ['numbers' => collect([1, 2, 3])]);
    }
}
