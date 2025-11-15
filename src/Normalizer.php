<?php

namespace AkhilDuggirala\LaravelLogEnhancer;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Traversable;
use WeakMap;

class Normalizer
{
    /**
     * Normalize message/context into scalar/array structures.
     *
     * @param mixed $item
     * @return mixed
     */
    public static function normalize($item)
    {
        // Scalars and null are fine
        if (is_scalar($item) || is_null($item)) {
            return $item;
        }

        // Arrays: recursively normalize each value
        if (is_array($item)) {
            $normalized = [];

            foreach ($item as $key => $value) {
                $normalized[$key] = self::normalize($value);
            }

            return $normalized;
        }

        // Arrayable (Collections, Eloquent models with toArray etc.)
        if ($item instanceof Arrayable) {
            return self::normalize($item->toArray());
        }

        // Jsonable
        if ($item instanceof Jsonable) {
            return self::normalize(json_decode($item->toJson(), true));
        }

        // JsonSerializable
        if ($item instanceof JsonSerializable) {
            return self::normalize($item->jsonSerialize());
        }

        // Traversable / iterators
        if ($item instanceof Traversable) {
            return self::normalize(iterator_to_array($item));
        }

        // WeakMap
        if ($item instanceof WeakMap) {
            $array = [];
            foreach ($item as $key => $value) {
                $array[$key] = self::normalize($value);
            }

            return $array;
        }

        // Fallback: turn public properties into array and normalize them
        if (is_object($item)) {
            return self::normalize(get_object_vars($item));
        }

        return $item;
    }
}
