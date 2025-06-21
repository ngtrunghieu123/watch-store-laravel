# ⌚ Watch Store - Laravel E-commerce Project

This is a Laravel-based e-commerce website for selling watches, built as part of an internship and learning project.

## 🚀 Features

- Product management (CRUD)
- Categories, promotions, and user reviews
- Shopping cart and online checkout
- User authentication (Laravel Breeze + Google OAuth)
- Order management and shipping tracking
- RESTful APIs for order and feedback
- Admin dashboard & customer frontend

## 🛠 Technologies Used

**Frontend:**
- HTML, CSS, JavaScript, Bootstrap, Blade

**Backend:**
- PHP, Laravel Framework, MySQL, RESTful API

**Others:**
- Laravel Artisan CLI
- Google OAuth
- Laravel Breeze

## 📦 How to run

```bash
git clone https://github.com/ngtrunghieu123/watch-store-laravel.git
cd watch-store-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
