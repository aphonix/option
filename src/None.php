<?php

namespace Aphonix\Option;

use Exception;

/**
 * None implementation of Option.
 * Represents the absence of a value.
 */
class None extends Option
{
    /**
     * @var None|null The singleton instance.
     */
    private static $instance = null;

    /**
     * Internal constructor to enforce singleton pattern.
     */
    private function __construct()
    {
    }

    /**
     * Prevent cloning the singleton instance.
     */
    private function __clone()
    {
    }

    /**
     * Prevent restoring a separate None instance through unserialize().
     *
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize None singleton; use None() instead');
    }

    /**
     * Returns the singleton instance of None.
     *
     * @return None
     */
    public static function getInstance(): None
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function is_some(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function is_some_and(callable $f): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function is_none(): bool
    {
        return true;
    }

    /**
     * Panic: Throws an exception because None contains no value.
     *
     * @throws Exception
     */
    public function unwrap()
    {
        throw new Exception("Called `Option::unwrap()` on a `None` value");
    }

    /**
     * Panic: Throws an exception with a custom message.
     *
     * @param string $msg
     * @throws Exception
     */
    public function expect(string $msg)
    {
        throw new Exception($msg);
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap_or($default)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap_or_else(callable $f)
    {
        return $f();
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f): Option
    {
        // Mapping over None always returns None
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function map_or($default, callable $f)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function map_or_else(callable $default, callable $f)
    {
        return $default();
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function and(Option $optB): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function and_then(callable $f): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function or(Option $optB): Option
    {
        // If self is None, return the alternative option
        return $optB;
    }

    /**
     * {@inheritdoc}
     */
    public function or_else(callable $f): Option
    {
        // If self is None, compute the alternative option via closure
        return self::ensure_option($f(), 'Option::or_else()');
    }

    /**
     * {@inheritdoc}
     */
    public function xor(Option $optB): Option
    {
        // XOR: returns optB if it is Some, otherwise returns None
        return $optB->is_some() ? $optB : $this;
    }
}
