<?php

namespace AkhilDuggirala\LaravelLogEnhancer\Tests\Support;

use JsonSerializable;

/**
 * Simple JsonSerializable implementation for testing.
 */
class DummyJsonSerializable implements JsonSerializable
{
    public function __construct(
        protected array $data
    ) {}

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
