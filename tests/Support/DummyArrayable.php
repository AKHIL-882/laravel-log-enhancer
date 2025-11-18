<?php

namespace AkhilDuggirala\LaravelLogEnhancer\Tests\Support;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Simple Arrayable implementation for testing.
 */
class DummyArrayable implements Arrayable
{
    public function __construct(
        protected array $data
    ) {}

    public function toArray()
    {
        return $this->data;
    }
}
