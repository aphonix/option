<?php

namespace Aphonix\Option;

/**
 * Some implementation of Option.
 * Represents an Option that contains a value.
 */
class Some extends Option
{
    /**
     * @var mixed The wrapped value.
     */
    private $value;

    /**
     * @param mixed $value The value to wrap.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function is_some(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function is_some_and(callable $f): bool
    {
        // Executes the predicate and ensures the result is cast to boolean
        return (bool)$f($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function is_none(): bool
    {
        return false;
    }

    /**
     * Returns the contained value.
     *
     * @return mixed
     */
    public function unwrap()
    {
        return $this->value;
    }

    /**
     * Returns the contained value (ignores the error message).
     *
     * @param string $msg
     * @return mixed
     */
    public function expect(string $msg)
    {
        return $this->value;
    }

    /**
     * Returns the contained value instead of the default.
     *
     * @param mixed $default
     * @return mixed
     */
    public function unwrap_or($default)
    {
        return $this->value;
    }

    /**
     * Returns the contained value instead of computing the default.
     *
     * @param callable $f
     * @return mixed
     */
    public function unwrap_or_else(callable $f)
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f): Option
    {
        // Applies the function and wraps the result in a new Some instance
        return new Some($f($this->value));
    }

    /**
     * Returns the result of applying $f to the value.
     *
     * @param mixed $default
     * @param callable $f
     * @return mixed
     */
    public function map_or($default, callable $f)
    {
        return $f($this->value);
    }

    /**
     * Returns the result of applying $f to the value.
     *
     * @param callable $default
     * @param callable $f
     * @return mixed
     */
    public function map_or_else(callable $default, callable $f)
    {
        return $f($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f): Option
    {
        // If the predicate matches, keep the Some; otherwise, return None
        return $f($this->value) ? $this : Option::none();
    }

    /**
     * Returns $optB since self is Some.
     */
    public function and(Option $optB): Option
    {
        return $optB;
    }

    /**
     * {@inheritdoc}
     */
    public function and_then(callable $f): Option
    {
        // Monadic bind: calls the closure which must return another Option
        return $f($this->value);
    }

    /**
     * Returns self since it's already Some.
     */
    public function or(Option $optB): Option
    {
        return $this;
    }

    /**
     * Returns self since it's already Some.
     */
    public function or_else(callable $f): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function xor(Option $optB): Option
    {
        // XOR: only returns self if optB is None
        return $optB->is_none() ? $this : Option::none();
    }
}
