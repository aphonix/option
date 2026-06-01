<?php

namespace Aphonix\Option;

/**
 * This file provides global helper functions to mimic Rust's idiomatic syntax.
 * The functions are defined within the Aphonix\Option namespace.
 *
 * Usage:
 * use function Aphonix\Option\{Some, None};
 */

/*
 * Check if the function exists to prevent collisions in case
 * the file is loaded multiple times or in different environments.
 */
if (!function_exists('Aphonix\Option\Some')) {
    /**
     * Wrap a value in a Some instance.
     *
     * Example: $opt = Some(42);
     *
     * @param mixed $value The value to be wrapped.
     * @return Option Returns an instance of Some containing the value.
     */
    function Some($value): Option
    {
        return Option::some($value);
    }
}

if (!function_exists('Aphonix\Option\None')) {
    /**
     * Return the None singleton instance.
     *
     * Example: $opt = None();
     *
     * @return Option Returns the singleton instance of None.
     */
    function None(): Option
    {
        return Option::none();
    }
}
