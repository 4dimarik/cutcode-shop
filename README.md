## Installation

```bash
composer create-project laravel/laravel .

composer require barryvdh/laravel-debugbar --dev

composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

### Base config

```dotenv
.env
____________

APP_NAME=
APP_URL=

DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

FILESYSTEM_DISK=public
```

```xml
phpunit.xml
        ____________

<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="cutcode_shop_test"/>
<env name="DB_USERNAME" value="root"/>
<env name="DB_PASSWORD" value=""/>

```

```bash
php artisan key:generate
php artisan storage:link
```

```php
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        DB::whenQueryingForLongerThan(
            500,
            function (Connection $connection, QueryExecuted $event) {
                // Notify development team...
            }
        );
    }
```

```bash
npm i sass
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

tailwind.config.js

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

add to ./resources/css/app.css

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```
