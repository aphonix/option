<?php

namespace Aphonix\Option\Tests;

use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Import the helper functions from our namespace.
 * This is the idiomatic way to use the library.
 */

use function Aphonix\Option\{Some, None};

/**
 * Unit tests for the Option implementation.
 * Ensures parity with Rust's Option<T> behavior.
 */
class OptionTest extends TestCase
{
    /**
     * Test basic creation and Boolean checks (is_some, is_none).
     */
    public function testBasicStates()
    {
        $some = Some(42);
        $this->assertTrue($some->is_some(), "Some(42) should be some");
        $this->assertFalse($some->is_none(), "Some(42) should not be none");
        $this->assertEquals(42, $some->unwrap());

        $none = None();
        $this->assertTrue($none->is_none(), "None() should be none");
        $this->assertFalse($none->is_some(), "None() should not be some");
    }

    /**
     * Test value extraction with defaults (unwrap_or, unwrap_or_else).
     */
    public function testUnwrapDefaults()
    {
        // Test unwrap_or
        $this->assertEquals(42, Some(42)->unwrap_or(0));
        $this->assertEquals(0, None()->unwrap_or(0));

        // Test unwrap_or_else (lazy evaluation)
        $this->assertEquals(100, None()->unwrap_or_else(function () {
            return 100;
        }));
    }

    /**
     * Test transformation methods (map, filter).
     */
    public function testTransformations()
    {
        // Test map: applies function to Some, returns None for None
        $mapped = Some(5)->map(function ($x) {
            return $x * 2;
        });
        $this->assertEquals(10, $mapped->unwrap());
        $this->assertTrue(None()->map(function ($x) {
            return $x + 1;
        })->is_none());

        // Test filter: returns None if predicate is false
        $this->assertTrue(Some(10)->filter(function ($x) {
            return $x > 5;
        })->is_some());
        $this->assertTrue(Some(3)->filter(function ($x) {
            return $x > 5;
        })->is_none());
    }

    /**
     * Test monadic operations and logical gates (and_then, or_else, xor).
     */
    public function testLogicalOperations()
    {
        // Test and_then (FlatMap): chaining functions that return Options
        $res = Some(2)->and_then(function ($x) {
            return Some($x * 10);
        });
        $this->assertEquals(20, $res->unwrap());

        // Test or_else: computes alternative if None
        $res = None()->or_else(function () {
            return Some("recovered");
        });
        $this->assertEquals("recovered", $res->unwrap());

        // Test xor: exclusive OR logic
        $this->assertTrue(Some(1)->xor(None())->is_some());
        $this->assertTrue(None()->xor(Some(1))->is_some());
        $this->assertTrue(Some(1)->xor(Some(2))->is_none());
    }

    /**
     * Test "panic" behavior when unwrapping None.
     */
    public function testUnwrapPanic()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Called `Option::unwrap()` on a `None` value");

        None()->unwrap();
    }

    /**
     * Test expect() with a custom error message.
     */
    public function testExpectMessage()
    {
        $customMsg = "Value is mandatory for this operation";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($customMsg);

        None()->expect($customMsg);
    }

    /**
     * Test is_some_and predicate check.
     */
    public function testIsSomeAnd()
    {
        $opt = Some(2);
        $this->assertTrue($opt->is_some_and(function ($x) {
            return $x > 1;
        }));
        $this->assertFalse($opt->is_some_and(function ($x) {
            return $x > 5;
        }));
        $this->assertFalse(None()->is_some_and(function ($x) {
            return $x > 0;
        }));
    }

    /**
     * Test the singleton property of None.
     */
    public function testNoneIsSingleton()
    {
        $none1 = None();
        $none2 = None();
        // Use assertSame to check if they are the exact same instance in memory
        $this->assertSame($none1, $none2);
    }
}