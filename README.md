## Subscriptions

Simple setup using Laravel, Cashier & Stripe

To setup a user

### 1) Set these values in the .env file:
    INITIAL_FIRST_NAME
    INITIAL_LAST_NAME
    INITIAL_USERNAME
    INITIAL_EMAIL
    INITIAL_PASSWORDHASH

### 2) run the admin seeder

``
php artisan db:seed --class=AdminSeeder
``
