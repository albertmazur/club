<h1 align="center">Real ASC</h1>

<p align="center">
    <img src="storage/images/logo.ico"/>
</p>

Real ASC is a web application designed to manage a sports club. It allows users to add stadiums, create and manage events, comment on events, and handle payments using Stripe.

## Features
- Manage stadiums (add, edit, delete)
- Manage events (add, edit, delete)
- Manage users (add, edit, delete)
- Comment on events
- Role-based access control (admin, moderator, user)
- Payment system integrated

## Technologies
- PHP 8.2 (https://www.php.net)
- Laravel 10 (https://laravel.com)
- Bootstrap 5 (https://getbootstrap.com)
- MySQL (https://www.mysql.com) (or other supported databases)
- Stripe (https://stripe.com) for payments
- Deepl (https://www.deepl.com) for translate

## Installation & Setup

### Clone the Repository
```bash
git clone https://github.com/albertmazur/real-asc.git
cd real-asc
```

### Install Dependencies
```bash
composer install
npm install && npm run dev
```

### Configure Environment
Copy the `.env.example` file and update database credentials.
```bash
cp .env.example .env
```

### Generate Application Key
```bash
php artisan key:generate
```

### Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### Create Storage Link
```bash
php artisan storage:link
```

### Start the Application
Run the application using Laravel Herd or the command below.
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Payment System
The application integrates with [Stripe](https://stripe.com/) to handle event payments. Ensure you have a valid Stripe API key configured in your `.env` file:
```
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```

## DeepL Integration
The application integrates with [DeepL](https://www.deepl.com/) to handle automatic translations of content.  
Ensure you have a valid DeepL API key configured in your `.env` file:

- **DEEPL_KEY** – Your API key obtained from the [DeepL API console](https://www.deepl.com/pro-api).  
- **DEEPL_API** – The API endpoint:  
  - Use `https://api-free.deepl.com/v2/translate` for the free version.  
  - Use `https://api.deepl.com/v2/translate` for the Pro version.  

The key will be automatically used in the application for translating supported fields.

## Admin Credentials
- **Email:** admin@example.com
- **Password:** Admin123

For any questions or suggestions, contact the project administrator!