<?php

namespace AkhilDuggirala\LaravelLogEnhancer\Tests;

use AkhilDuggirala\LaravelLogEnhancer\LogEnhancerServiceProvider;
use AkhilDuggirala\LaravelLogEnhancer\Tests\Support\DummyArrayable;
use AkhilDuggirala\LaravelLogEnhancer\Tests\Support\DummyJsonable;
use AkhilDuggirala\LaravelLogEnhancer\Tests\Support\DummyJsonSerializable;
use ArrayIterator;
use Illuminate\Support\Facades\Log;
use Mockery;
use Orchestra\Testbench\TestCase;
use stdClass;
use WeakMap;

class SmartInfoTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LogEnhancerServiceProvider::class,
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function it_normalizes_collection_in_message(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with(['foo' => 'bar'], []);

        smart_info(collect(['foo' => 'bar']));

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_collection_in_context(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('test', ['numbers' => [1, 2, 3]]);

        smart_info('test', ['numbers' => collect([1, 2, 3])]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_logs_scalar_message_and_array_context_unchanged(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('simple message', ['foo' => 'bar', 'count' => 1]);

        smart_info('simple message', ['foo' => 'bar', 'count' => 1]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_discards_non_array_context_after_normalization(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('scalar context', []);

        smart_info('scalar context', 'not-an-array');

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_arrayable_message_and_context(): void
    {
        $message = new DummyArrayable(['foo' => 'bar']);
        $contextArrayable = new DummyArrayable(['baz' => 'qux']);

        Log::shouldReceive('info')
            ->once()
            ->with(['foo' => 'bar'], ['data' => ['baz' => 'qux']]);

        smart_info($message, ['data' => $contextArrayable]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_jsonable_message_and_context(): void
    {
        $message = new DummyJsonable(['foo' => 'bar']);
        $contextJsonable = new DummyJsonable(['baz' => 'qux']);

        Log::shouldReceive('info')
            ->once()
            ->with(['foo' => 'bar'], ['payload' => ['baz' => 'qux']]);

        smart_info($message, ['payload' => $contextJsonable]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_jsonserializable_message_and_context(): void
    {
        $message = new DummyJsonSerializable(['foo' => 'bar']);
        $contextJsonSerializable = new DummyJsonSerializable(['baz' => 'qux']);

        Log::shouldReceive('info')
            ->once()
            ->with(['foo' => 'bar'], ['payload' => ['baz' => 'qux']]);

        smart_info($message, ['payload' => $contextJsonSerializable]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_traversable_in_context(): void
    {
        $iterator = new ArrayIterator([1, 2, 3]);

        Log::shouldReceive('info')
            ->once()
            ->with('iterator test', ['items' => [1, 2, 3]]);

        smart_info('iterator test', ['items' => $iterator]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_stdclass_in_context(): void
    {
        $obj = new stdClass;
        $obj->foo = 'bar';
        $obj->count = 3;

        Log::shouldReceive('info')
            ->once()
            ->with('stdclass test', ['object' => ['foo' => 'bar', 'count' => 3]]);

        smart_info('stdclass test', ['object' => $obj]);

        $this->addToAssertionCount(1);
    }

    /** @test */
    public function it_normalizes_weakmap_in_context_to_an_array(): void
    {
        $key = new stdClass;
        $key->id = 1;

        $weakMap = new WeakMap;
        $weakMap[$key] = new DummyArrayable(['foo' => 'bar']);

        Log::shouldReceive('info')
            ->once()
            ->with('weakmap test', Mockery::on(function ($context) {
                // context should be an array with key 'map'
                if (! is_array($context)) {
                    return false;
                }

                if (! isset($context['map']) || ! is_array($context['map'])) {
                    return false;
                }

                // We don't assert exact keys because object keys in arrays are cast to strings,
                // but we do want to ensure values inside are normalized.
                $values = array_values($context['map']);
                $first = $values[0] ?? null;

                return is_array($first) && ($first['foo'] ?? null) === 'bar';
            }));

        smart_info('weakmap test', ['map' => $weakMap]);

        $this->addToAssertionCount(1);
    }
}
