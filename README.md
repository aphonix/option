# **🦀 Rust-style Option for PHP 🐘**

A lightweight, zero-dependency implementation of Rust's Option<T> for PHP 7.1+. This library brings functional programming patterns to PHP, helping you handle optional values explicitly and write more expressive code.

## **✨ Features**

* **PHP 7.1+ Compatibility**: Fully supports all versions from PHP 7.1 up to PHP 8.4+.
* **Strict Rust API**: Ported core methods from the Rust Standard Library (std::option).
* **Singleton None**: Memory-efficient implementation using a singleton for the None type.
* **Global Helper Functions**: Idiomatic Some() and None() functions for a clean development experience.
* **Explicit Optional Values**: Encourages callers to handle values that may be absent instead of relying on implicit null checks.

## **🚀 Installation**

You can install the package via [Composer](https://getcomposer.org/):

```shell
composer require aphonix/option
```

## **📖 Quick Start**

### **Basic Usage**
```php
<?php

use function Aphonix\Option\{Some, None};
use Aphonix\Option\Option;

// Wrap a value that exists
$some = Some("Aphonix");

// Represent the absence of a value
$none = None();

// Convert a nullable value into Some or None
$fromNullable = Option::from_nullable(null);

if ($some->is_some()) {
    echo $some->unwrap(); // Output: Aphonix
}
```

`Some(null)` is allowed and represents a present value whose payload is `null`. Use `None()` when the value is absent.

### **Functional Chaining**

Avoid defensive `is_null()` nesting with monad-style chaining:

```php
$result = Some("  hello world  ")
    ->map('trim')
    ->map('strtoupper')
    ->filter(function ($s) {
        return strlen($s) > 5;
    })
    ->unwrap_or("DEFAULT");

echo $result; // Output: HELLO WORLD
```

### **Advanced: and_then (FlatMap)**

Use `and_then` when your transformation closure returns an Option itself:

```php
function find_user($id) {
    $users = [1 => ['name' => 'Alice']];
    return isset($users[$id]) ? Some($users[$id]) : None();
}

$name = Some(1)
    ->and_then('find_user')
    ->map(function ($user) { return $user['name']; })
    ->unwrap_or("Unknown");

echo $name; // Output: Alice
```

## **🛠 API Reference**

### **Creation Methods**

| Method | Description |
|--------|-------------|
| `Option::some($value): Option` | Wraps a value in `Some`, including `null`. |
| `Option::none(): Option` | Returns the shared `None` instance. |
| `Option::from_nullable($value): Option` | Returns `None` for `null`, otherwise returns `Some($value)`. |

### **Check Methods**

| Method | Description |
|--------|-------------|
| `is_some(): bool` | Returns true if the option is a Some value. |
| `is_none(): bool` | Returns true if the option is a None value. |
| `is_some_and(callable $f): bool` | Returns true if the option is Some and the value inside matches the predicate. |

### **Unwrapping Methods**

| Method | Description |  
|--------|-------------|
| unwrap() | Returns the inner value. Throws an Exception (Panic) if the value is None. |  
| expect(string $msg) | Returns the inner value. Throws an Exception with a custom message if the value is None. |  
| `unwrap_or($default)` | Returns the inner value if it exists, otherwise returns the provided default. |
| `unwrap_or_else(callable $f)` | Returns the inner value if it exists, otherwise returns the result of the closure. |

### **Transformation & Filtering**

| Method | Description |  
|--------|-------------|
| `map(callable $f): Option` | Maps an Option<T> to Option<U> by applying a function to the contained value. |
| `map_or($default, callable $f)` | Returns the provided default result, or applies a function to the contained value. |
| `filter(callable $f): Option` | Returns None if the option is Some and the closure returns false. |
| `and_then(callable $f): Option` | Returns None if the option is None, otherwise calls the closure with the value and returns the result. |

### **Logical Operations**

| Method | Description |  
|--------|-------------|
| `and(Option $optB): Option` | Returns None if the option is None, otherwise returns $optB. |
| `or(Option $optB): Option` | Returns the option if it contains a value, otherwise returns $optB. |
| `xor(Option $optB): Option` | Returns Some if exactly one of self, $optB is Some, otherwise returns None. |

## **🧪 Testing**

This project uses PHPUnit to verify the core behavior:

```bash
composer install
./vendor/bin/phpunit tests
```

## **📄 License**

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
