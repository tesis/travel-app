# Laravel API application

> APIs application for a travel agency

- **Travel** is the main unit: it contains all the necessary information, like the number of days, the images, title, etc.
- **Tour** is a specific dates-range of a travel with its own price and details

- Use UUIDs as primary keys instead of incremental IDs
- Tours prices are integer multiplied by 100: for example, €999 euro will be 99900, but, when returned to Frontends, they will be formatted (99900 / 100);
- Tours names inside the samples are a kind-of what we use internally


## Tools

### Laravel Pint

This tool helps identify various code styling issues like unused imports we left, line/symbol spaces here and there, etc.

Laravel Pint is an official first-party tool built on top of PHP-CS-Fixer and makes it simple to ensure that your code style stays clean and consistent.

To fix your code styling with Pint, you only need to install it with Composer and run its command in Terminal. That's it. Let's do that and see what Pint will say about the errors in our code.

```shell
composer require laravel/pint --dev

// run in terminal
// will fix everything
./vendor/bin/pint

// will show issues
./vendor/bin/pint --test

```

### Larastan

>  is about how the code works (structure)

```shell

composer require nunomaduro/larastan --dev

```
### API Docs with Scribe

```shell
composer require --dev knuckleswtf/scribe
php artisan vendor:publish --tag=scribe-config
php artisan scribe:generate
```

Access documentation `/docs`