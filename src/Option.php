<?php

namespace Aphonix\Option;

/**
 * The Option type represents an optional value:
 * every Option is either Some and contains a value, or None, and does not.
 * This class serves as the abstract base for the Option monad.
 */
abstract class Option
{
    /**
     * Static factory to create a Some instance.
     *
     * @param mixed $value The value to wrap.
     * @return Option
     */
    public static function some($value): Option
    {
        return new Some($value);
    }

    /**
     * Static factory to get the None singleton instance.
     *
     * @return Option
     */
    public static function none(): Option
    {
        return None::getInstance();
    }

    /**
     * Creates None when the value is null, otherwise wraps it in Some.
     *
     * @param mixed $value The value to wrap.
     * @return Option
     */
    public static function from_nullable($value): Option
    {
        return $value === null ? self::none() : self::some($value);
    }

    /**
     * Ensures callbacks that promise an Option return one.
     *
     * @param mixed $value The callback result.
     * @param string $method The Option method that invoked the callback.
     * @return Option
     * @throws \UnexpectedValueException
     */
    protected static function ensure_option($value, string $method): Option
    {
        if (!$value instanceof Option) {
            throw new \UnexpectedValueException(sprintf(
                '%s callback must return an instance of %s',
                $method,
                Option::class
            ));
        }

        return $value;
    }

    /**
     * Returns true if the option is a Some value.
     *
     * @return bool
     */
    abstract public function is_some(): bool;

    /**
     * Returns true if the option is a Some value and the value inside of it
     * matches a predicate.
     *
     * @param callable $f A function that returns a boolean.
     * @return bool
     */
    abstract public function is_some_and(callable $f): bool;

    /**
     * Returns true if the option is a None value.
     *
     * @return bool
     */
    abstract public function is_none(): bool;

    /**
     * Returns the contained Some value.
     *
     * IMPORTANT: Panics (throws Exception) if the value is a None.
     * No return type hint is used here to support any value in PHP 7.1+.
     *
     * @return mixed
     * @throws \Exception
     */
    abstract public function unwrap();

    /**
     * Returns the contained Some value.
     *
     * Panics with a custom message if the value is a None.
     *
     * @param string $msg The error message to throw.
     * @return mixed
     * @throws \Exception
     */
    abstract public function expect(string $msg);

    /**
     * Returns the contained Some value or a provided default.
     *
     * @param mixed $default The fallback value.
     * @return mixed
     */
    abstract public function unwrap_or($default);

    /**
     * Returns the contained Some value or computes it from a closure.
     *
     * @param callable $f A function returning the fallback value.
     * @return mixed
     */
    abstract public function unwrap_or_else(callable $f);

    /**
     * Maps an Option<T> to Option<U> by applying a function to a contained value.
     *
     * @param callable $f Transformation function.
     * @return Option
     */
    abstract public function map(callable $f): Option;

    /**
     * Returns the provided default result (if none),
     * or applies a function to the contained value (if some).
     *
     * @param mixed $default Fallback result.
     * @param callable $f Transformation function.
     * @return mixed
     */
    abstract public function map_or($default, callable $f);

    /**
     * Computes a default function result (if none),
     * or applies a different function to the contained value (if some).
     *
     * @param callable $default Fallback function.
     * @param callable $f Transformation function.
     * @return mixed
     */
    abstract public function map_or_else(callable $default, callable $f);

    /**
     * Returns None if the option is None, otherwise calls predicate
     * with the wrapped value and returns the result.
     *
     * @param callable $f Filtering function.
     * @return Option
     */
    abstract public function filter(callable $f): Option;

    /**
     * Returns None if the option is None, otherwise returns $optB.
     *
     * @param Option $optB The secondary Option.
     * @return Option
     */
    abstract public function and(Option $optB): Option;

    /**
     * Returns None if the option is None,
     * otherwise calls $f with the wrapped value and returns the result.
     *
     * This is also known as "flatMap" in other languages.
     *
     * @param callable $f Function returning an Option.
     * @return Option
     */
    abstract public function and_then(callable $f): Option;

    /**
     * Returns the option if it contains a value, otherwise returns $optB.
     *
     * @param Option $optB The alternative Option.
     * @return Option
     */
    abstract public function or(Option $optB): Option;

    /**
     * Returns the option if it contains a value,
     * otherwise calls $f and returns the result.
     *
     * @param callable $f Function returning an Option.
     * @return Option
     */
    abstract public function or_else(callable $f): Option;

    /**
     * Returns Some if exactly one of self, $optB is Some, otherwise returns None.
     *
     * @param Option $optB The secondary Option.
     * @return Option
     */
    abstract public function xor(Option $optB): Option;
}
