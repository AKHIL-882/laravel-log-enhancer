# 📦 Laravel Log Enhancer

Effortless deep normalization for Laravel logging

Laravel Log Enhancer provides a convenient helper function (smart_info()) that automatically normalizes complex data structures before passing them to Laravel's logging system.

Instead of dumping raw objects, collections, models, or other structures into log files, this package converts them into clean, serializable arrays — deeply and recursively.

Perfect for debugging, API responses, model data, or anything that ends up messy when logged normally.

# 🚀 Features

🔁 Deep recursive normalization
Converts nested structures into log-friendly arrays.

# 📦 Supports:

- Collection

- Arrayable

- Jsonable

- JsonSerializable

- Traversable

- WeakMap

- Generic objects

# 🎯 Simple helper:

-- smart_info($message, $context);

# 🎉 Fully tested (PHPUnit + Orchestra Testbench)

🔧 Zero configuration needed — works instantly via Laravel auto-discovery

# 📥 Installation

`composer require akhilduggirala/laravel-log-enhancer`

Laravel’s package auto-discovery will register everything automatically.
No manual setup required.

# 🧠 What Problem Does This Solve?

Logging in Laravel works great for simple messages — but breaks when you try to log objects, collections, or deep nested arrays:

`Log::info('data', ['payload' => collect([1,2,3])]);`

This results in:

`payload: Collection { ... }`

Hard to read. Not JSON-friendly. Not searchable.

This package fixes that.

# ✨ Usage

Use the global helper:

`smart_info($message, $context = []);`

### Example 1 — Logging Collections

```
smart_info('User data', [
    'roles' => collect(['admin', 'editor']),
]);

Result logged:

{
    "message": "User data",
    "roles": ["admin", "editor"]
}

```

### Example 2 — Logging Models

```
smart_info('Created user', $user);
```

Model gets converted automatically using toArray().

### Example 3 — Nested Arrays + Objects

```

smart_info('Checkout processed', [
    'cart' => [
    'items' => collect([1,2,3]),
    'user' => $user,
    ]
]);

Becomes:

{
    "cart": {
    "items": [1,2,3],
    "user": { ...model attributes... }
    }
}
```

# 🔍 How It Works

The Normalizer class recursively normalizes any data type using:

- Arrayable::toArray()

- Jsonable::toJson()

- JsonSerializable::jsonSerialize()

- Traversable::iterator_to_array()

- Reflection on generic objects (get_object_vars())

- Everything becomes clean arrays/scalars before logging.

# 🧪 Running Tests

Clone the repo and install dependencies:

`composer install`

Run the test suite:

`./vendor/bin/phpunit`

Tests include:

Normalization of message

Normalization of nested context

Logging behavior using Laravel’s Log façade with Mockery

# 🤝 Contributing

Pull requests are welcome!

Fork the repository

Create a new branch

Add your changes + tests

Submit a pull request

Please follow PSR-12 coding standards.

# 📄 License

This package is open-source and licensed under the MIT License.
See the LICENSE.

⭐️ Support the Project

If this package helps you write cleaner logs:

Star the repo on GitHub ❤️

Share it with your Laravel dev friends

### Developed by Akhil Duggirala

Inputs:: Mail- akhilduggirala882@gmail.com
