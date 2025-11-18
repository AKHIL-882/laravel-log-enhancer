<?php

use AkhilDuggirala\LaravelLogEnhancer\Normalizer;
use Illuminate\Support\Facades\Log;

if (! function_exists('smart_info')) {
    /**
     * Log an informational message with automatic deep normalization.
     *
     * This helper ensures that both the message and the context payload are
     * transformed into log-friendly scalar and array structures before being
     * passed to the underlying logger. It recursively converts Laravel
     * collections, Arrayable objects, Jsonable objects, JsonSerializable
     * instances, Traversable data, and even generic PHP objects into clean,
     * serializable arrays.
     *
     * Use this function when you want consistent, predictable log output
     * without having to manually call ->toArray() or handle nested objects.
     * Particularly useful for debugging responses, model data, collections,
     * or any complex structures that would otherwise appear as unreadable
     * object dumps in your logs.
     *
     * @param  mixed  $message  The log message. Can be a string, array,
     *                          collection, model, or any normalizable object.
     * @param  mixed  $context  Additional context data to include with the log
     *                          entry. Supports complex/nested structures.
     */
    function smart_info($message, $context = []): void
    {
        [$message, $context] = [
            Normalizer::normalize($message),
            Normalizer::normalize($context),
        ];

        Log::info($message, is_array($context) ? $context : []);
    }
}
