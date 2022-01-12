# laravel-midtrans
A working example of payment gateway integration using midtrans built using laravel

## Requirements ##
- Midtrans sandbox account
- Set Payment Notification URL in order to make auto update transaction working (https://docs.midtrans.com/en/after-payment/http-notification). Use url "/orders/update-order" (check in route/api.php)

## Installation ##
* `git clone https://github.com/muhammadardie/laravel-midtrans.git`
* `cd laravel-midtrans`
* `composer install`
* `cp .env.example .env`
* `Set these environment variables`
    - MIDTRANS_MERCHANT_ID
    - MIDTRANS_CLIENT_KEY
    - MIDTRANS_SERVER_KEY
* `php artisan key:generate`
* Create a database
* `php artisan migrate --seed` to create and populate tables
* `php artisan serve` to start the app on http://localhost:8000/