<?php

namespace AkhilDuggirala\LaravelLogEnhancer\Tests\Support;

use Illuminate\Contracts\Support\Jsonable;

/**
 * Simple Jsonable implementation for testing.
 */
class DummyJsonable implements Jsonable
{
    public function __construct(
        protected array $data
    ) {}

    public function toJson($options = 0)
    {
        return json_encode($this->data, $options);
    }
}
