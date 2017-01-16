# FastFrame Kernel

## Kernel

The Kernel interface defines two methods, `load()` and `run()`. These
allow interacting with the second level (Http or Console) level kernels
on their own, or hands off.

### `load($type)`

You would use load when you do not want to call the kernel right away. This
method SHOULD call the provider's `define()` methods. 

### `run($type = null)`

This loads the kernel and then *SHOULD* call the provider's `modify()` methods,
after which it *SHOULD* handle all requests and responses.

## Environment

The kernel Environment loads from `$_ENV`, and also from a `.env` file.

It is possible to skip loading the `.env` by providing a non empty array
to the `load()` call.

```php
$al           = require __DIR__ . '/vendor/autoload.php';
$env          = new \FastFrame\Kernel\Environment(__DIR__, $al);
$isProduction = (getenv('FASTFRAME_ENV') ?: 'development') === 'production';
if ($isProduction && file_exists($file = "{$env->rootPath()}/storage/cache/config.php")) {
    $env->load(require_once($file));
}
else {
    $env->load(); // default: __DIR__/.env
}
```

## ProviderList

The provider list is an attempt to make it easier to maintain a list of 
container providers that can be called as defined during a define/modify
phase of container configuration.

The concept of define and modify is taken from Aura's DI, as it provided
a cleaner separation of how and when the container is "locked".
