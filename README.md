# ✈️ Flight Booking System - Laravel Backend

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-Ready-blue?style=for-the-badge&logo=docker" alt="Docker">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

A comprehensive, production-ready RESTful API backend for a flight booking platform. Built with Laravel 10, this system provides secure authentication, flight search and booking capabilities, payment integration with Stripe, and complete reservation management.

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [System Architecture](#-system-architecture)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
  - [Local Setup](#local-setup)
  - [Docker Setup](#docker-setup)
  - [Kubernetes Deployment](#kubernetes-deployment)
- [Configuration](#-configuration)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Multi-provider OAuth**: Login with Google, Facebook, and GitHub
- **JWT Authentication**: Secure token-based API authentication
- **Laravel Sanctum**: SPA authentication support
- **Password Recovery**: Secure password reset functionality
- **User Profile Management**: Complete user account management

### ✈️ Flight Management
- **Flight Search**: Search flights by origin, destination, and date
- **Real-time Availability**: Check seat availability and pricing
- **Multi-airline Support**: Integration with multiple airline data
- **Flight Status**: Track and update flight statuses

### 🛒 Booking & Reservation
- **Shopping Cart**: Add multiple flights to cart
- **Reservation System**: Complete booking workflow
- **Passenger Management**: Support for multiple passengers per booking
- **Booking Cancellation**: Cancel reservations with proper handling
- **Booking History**: View past and upcoming reservations

### 💳 Payment Integration
- **Stripe Payment Gateway**: Secure payment processing
- **Multiple Payment Methods**: Support for various payment options
- **Transaction Records**: Complete payment history
- **Refund Management**: Handle cancellations and refunds

### 🌍 Internationalization
- **Country Data**: Comprehensive country information
- **Calling Codes**: International phone number support
- **Multi-currency Support**: Handle different currencies (via exchange.php config)

### 🚀 DevOps & Deployment
- **Docker Support**: Full containerization with Docker Compose
- **Kubernetes Ready**: K8s deployment configurations included
- **Nginx Configuration**: Production-ready web server setup
- **CI/CD Ready**: Easy integration with deployment pipelines

---

## 🛠️ Tech Stack

### Backend Framework
- **Laravel 10.x** - Modern PHP framework
- **PHP 8.1+** - Latest PHP features

### Authentication
- **Laravel Jetstream** - Application scaffolding
- **Laravel Sanctum** - API token authentication
- **JWT Auth (tymon/jwt-auth)** - JSON Web Token authentication
- **Laravel Socialite** - OAuth provider integration

### Database
- **MySQL 8.0** - Primary database
- **Eloquent ORM** - Database abstraction layer

### Payment
- **Stripe PHP SDK** - Payment processing

### Frontend Integration
- **Livewire 3.0** - Dynamic components
- **Vite** - Asset bundling
- **Tailwind CSS** - Utility-first CSS

### Development Tools
- **PHPUnit** - Unit and feature testing
- **Laravel Pint** - Code style fixer
- **Faker** - Test data generation

### DevOps
- **Docker & Docker Compose** - Containerization
- **Kubernetes** - Orchestration
- **Nginx** - Web server

---

## 🏗️ System Architecture

```
┌─────────────────┐
│   Frontend      │
│   (React/Vue)   │
└────────┬────────┘
         │
         │ API Requests
         ▼
┌─────────────────────────────────┐
│      Laravel Backend API        │
│  ┌──────────────────────────┐  │
│  │   Authentication Layer   │  │
│  │  (JWT, Sanctum, OAuth)   │  │
│  └───────────┬──────────────┘  │
│              ▼                  │
│  ┌──────────────────────────┐  │
│  │   Business Logic Layer   │  │
│  │  - Flight Controller     │  │
│  │  - Reservation System    │  │
│  │  - Payment Processing    │  │
│  └───────────┬──────────────┘  │
│              ▼                  │
│  ┌──────────────────────────┐  │
│  │   Data Access Layer      │  │
│  │  (Eloquent ORM Models)   │  │
│  └───────────┬──────────────┘  │
└──────────────┼──────────────────┘
               ▼
      ┌────────────────┐
      │  MySQL Database│
      └────────────────┘
               │
               ▼
      ┌────────────────┐
      │ External APIs  │
      │ - Stripe       │
      │ - OAuth        │
      └────────────────┘
```

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP >= 8.1** with extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  
- **Composer** - PHP dependency manager
- **MySQL 8.0+** or compatible database
- **Node.js & NPM** - For frontend asset compilation (v16+ recommended)
- **Git** - Version control

### Optional (for Docker deployment)
- **Docker** - v20.10+
- **Docker Compose** - v2.0+

---

## 🚀 Installation

### Local Setup

#### 1. Clone the Repository

```bash
git clone https://github.com/htay-htay-thwe/Booking-Flights-Laravel.git
cd Booking-Flights-Laravel
```

#### 2. Install PHP Dependencies

```bash
composer install
```

#### 3. Install Node Dependencies

```bash
npm install
```

#### 4. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

#### 5. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flight_booking_system
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

#### 6. Configure OAuth Providers (Optional)

Add your OAuth credentials in `.env`:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/api/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/api/facebook/callback

# GitHub OAuth
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=http://localhost:8000/api/github/callback
```

#### 7. Configure Stripe Payment

```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
```

#### 8. Run Database Migrations

```bash
php artisan migrate
```

#### 9. Seed Database (Optional)

```bash
php artisan db:seed
```

#### 10. Build Frontend Assets

```bash
npm run build
```

#### 11. Start the Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

---

### Docker Setup

#### 1. Clone the Repository

```bash
git clone https://github.com/htay-htay-thwe/Booking-Flights-Laravel.git
cd Booking-Flights-Laravel
```

#### 2. Configure Environment

```bash
cp .env.example .env
```

Update `.env` with Docker database settings:

```env
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=flight_booking_system
DB_USERNAME=htaythwe
DB_PASSWORD=htaythwe
```

#### 3. Build and Start Containers

```bash
docker-compose up -d --build
```

#### 4. Install Dependencies Inside Container

```bash
# Access Laravel container
docker exec -it laravel_backend_flights bash

# Inside container
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
exit
```

The API will be available at `http://localhost:8000`

---

### Kubernetes Deployment

#### 1. Configure Kubernetes Resources

Update the configuration files in `k8s/` directory:
- `laravel-deployment.yml` - Laravel application deployment
- `mysql-deployment.yml` - MySQL database deployment

#### 2. Deploy to Kubernetes Cluster

```bash
# Apply MySQL deployment
kubectl apply -f k8s/mysql-deployment.yml

# Apply Laravel deployment
kubectl apply -f k8s/laravel-deployment.yml

# Verify deployments
kubectl get deployments
kubectl get services
kubectl get pods
```

---

## ⚙️ Configuration

### Application Settings

Key configuration files:

- **`config/app.php`** - Application settings, timezone, locale
- **`config/database.php`** - Database connections
- **`config/auth.php`** - Authentication guards and providers
- **`config/services.php`** - Third-party service credentials
- **`config/jwt.php`** - JWT authentication settings
- **`config/exchange.php`** - Currency exchange configuration

### API Configuration

The application uses API routes defined in `routes/api.php`. Base URL format:

```
http://your-domain.com/api/{endpoint}
```

---

## 📡 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication Endpoints

#### Register User
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Forgot Password
```http
POST /api/forgot-password
Content-Type: application/json

{
  "email": "john@example.com"
}
```

#### OAuth Login
```http
GET  /api/google/redirect
POST /api/google/callback

GET  /api/facebook/redirect
POST /api/facebook/callback

GET  /api/github/redirect
POST /api/github/callback
```

### Protected Endpoints (Require JWT Token)

Include JWT token in headers:
```http
Authorization: Bearer {your-jwt-token}
```

#### User Information
```http
GET /api/user-info
```

#### Flight Operations
```http
GET  /api/flights              # List all flights
GET  /api/flights/{id}         # Get flight details
POST /api/flights/search       # Search flights
```

#### Cart Operations
```http
GET    /api/cart               # View cart
POST   /api/cart               # Add to cart
PUT    /api/cart/{id}          # Update cart item
DELETE /api/cart/{id}          # Remove from cart
```

#### Reservation Operations
```http
GET    /api/reservations       # List user reservations
POST   /api/reservations       # Create reservation
GET    /api/reservations/{id}  # Get reservation details
DELETE /api/reservations/{id}  # Cancel reservation
```

#### Payment Operations
```http
POST /api/stripe/payment       # Process payment
POST /api/stripe/refund        # Process refund
```

---

## 🗄️ Database Schema

### Core Tables

#### `users`
- User authentication and profile information
- OAuth provider data
- Two-factor authentication columns

#### `flights`
- `id` - Primary key
- `airline` - Airline name
- `from` - Departure airport
- `to` - Arrival airport
- `departure_date` - Flight date
- `fromTime` - Departure time
- `toTime` - Arrival time
- `price` - Ticket price
- `flightStatus` - Current status
- `timestamps`

#### `reserves`
- `id` - Primary key
- `user_id` - Foreign key to users
- `flight_id` - Foreign key to flights
- `cart_id` - Foreign key to carts (nullable)
- `uuid` - Unique booking identifier
- `firstName`, `lastName` - Booker information
- `email`, `country`, `country_code`, `phone_no` - Contact details
- `passenger_first_name`, `passenger_last_name` - Passenger details
- `gender`, `birthday` - Passenger information
- `timestamps`

#### `carts`
- Shopping cart items before checkout
- Links users to flights

#### `cancels`
- Cancellation records
- Refund tracking

#### `sessions`
- User session management

---

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit
```

### Run with Coverage

```bash
php artisan test --coverage
```

### Testing Best Practices

- All API endpoints have corresponding feature tests
- Database uses SQLite in-memory for testing
- Factories available for all models in `database/factories/`

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper `APP_URL`
- [ ] Set secure `APP_KEY`
- [ ] Configure production database
- [ ] Set up SSL/TLS certificates
- [ ] Configure email service (SMTP/Mailgun/etc.)
- [ ] Set up proper logging (Sentry, CloudWatch, etc.)
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Set up regular backups
- [ ] Configure CORS properly in `config/cors.php`

### Deployment Platforms

This application can be deployed to:
- **Traditional VPS** (DigitalOcean, Linode, AWS EC2)
- **Platform as a Service** (Laravel Forge, Laravel Vapor, Heroku)
- **Container Platforms** (AWS ECS, Google Cloud Run, Azure Container Instances)
- **Kubernetes Clusters** (AWS EKS, Google GKE, Azure AKS)

---

## 📁 Project Structure

```
Booking-Flights-Laravel/
├── app/
│   ├── Actions/              # Custom action classes
│   ├── Console/              # Artisan commands
│   ├── Exceptions/           # Exception handlers
│   ├── Http/
│   │   ├── Controllers/      # HTTP controllers
│   │   │   └── Api/         # API controllers
│   │   │       ├── AuthController.php
│   │   │       ├── CartController.php
│   │   │       ├── FlightController.php
│   │   │       ├── ReserveController.php
│   │   │       └── StripeController.php
│   │   ├── Middleware/       # HTTP middleware
│   │   └── Kernel.php
│   ├── Livewire/             # Livewire components
│   ├── Models/               # Eloquent models
│   │   ├── User.php
│   │   ├── Flight.php
│   │   ├── Cart.php
│   │   ├── Reserve.php
│   │   └── Cancel.php
│   ├── Providers/            # Service providers
│   └── View/                 # View composers
├── bootstrap/                # Application bootstrap
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── k8s/                      # Kubernetes configurations
│   ├── laravel-deployment.yml
│   ├── mysql-deployment.yml
│   └── react-deployment.yml
├── nginx/                    # Nginx configurations
├── public/                   # Public assets
│   ├── airlines.json         # Airline data
│   └── airports.json         # Airport data
├── resources/
│   ├── css/                  # Stylesheets
│   ├── data/                 # Static data files
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/
│   ├── api.php               # API routes
│   ├── web.php               # Web routes
│   ├── channels.php          # Broadcast channels
│   └── console.php           # Console commands
├── storage/                  # Application storage
├── tests/                    # Test files
│   ├── Feature/              # Feature tests
│   └── Unit/                 # Unit tests
├── .env.example              # Environment template
├── composer.json             # PHP dependencies
├── docker-compose.yml        # Docker Compose config
├── Dockerfile                # Docker image definition
├── package.json              # Node dependencies
├── phpunit.xml               # PHPUnit configuration
└── README.md                 # This file
```

---

## 🔧 Common Issues & Troubleshooting

### Database Connection Issues

```bash
# Check MySQL is running
# For local MySQL:
mysql -u root -p

# For Docker:
docker-compose ps
docker exec -it flight_mysql mysql -u root -p
```

### Permission Errors

```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### JWT Token Issues

```bash
# Regenerate JWT secret
php artisan jwt:secret

# Clear configuration cache
php artisan config:clear
php artisan cache:clear
```

### Migration Errors

```bash
# Reset and re-run migrations
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Commit your changes**
   ```bash
   git commit -m 'Add some amazing feature'
   ```
4. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
5. **Open a Pull Request**

### Coding Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed
- Run Laravel Pint before committing:
  ```bash
  ./vendor/bin/pint
  ```

---

## 📝 Environment Variables

### Required Variables

```env
APP_NAME=FlightBookingSystem
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flight_booking_system
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your-jwt-secret

STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx
```

### Optional OAuth Variables

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=
```

---

## 🔒 Security

### Reporting Security Vulnerabilities

If you discover a security vulnerability, please send an email to the repository owner. All security vulnerabilities will be promptly addressed.

### Security Best Practices

- All passwords are hashed using bcrypt
- JWT tokens expire after configured time
- CORS configured for production
- SQL injection protection via Eloquent ORM
- XSS protection enabled
- CSRF protection for web routes
- Rate limiting on API endpoints

---

## 📚 Additional Resources

### Laravel Documentation
- [Official Laravel Documentation](https://laravel.com/docs/10.x)
- [Laravel API Authentication](https://laravel.com/docs/10.x/sanctum)
- [Eloquent ORM](https://laravel.com/docs/10.x/eloquent)

### Related Repositories
- [Frontend Repository](https://github.com/htay-htay-thwe/Booking-Flights-Frontend) - React/Vue frontend for this API

### Third-Party Services
- [Stripe Documentation](https://stripe.com/docs/api)
- [Google OAuth Setup](https://developers.google.com/identity/protocols/oauth2)
- [Facebook OAuth Setup](https://developers.facebook.com/docs/facebook-login)
- [GitHub OAuth Setup](https://docs.github.com/en/developers/apps/building-oauth-apps)

---

## 📈 Performance Optimization

### Caching

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### Database Optimization

- Use database indexing on frequently queried columns
- Implement query caching for static data
- Use eager loading to prevent N+1 queries
- Consider Redis for session and cache storage

### Recommended Production Settings

```env
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 📞 Support & Contact

- **Issues**: [GitHub Issues](https://github.com/htay-htay-thwe/Booking-Flights-Laravel/issues)
- **Discussions**: [GitHub Discussions](https://github.com/htay-htay-thwe/Booking-Flights-Laravel/discussions)
- **Repository Owner**: [@htay-htay-thwe](https://github.com/htay-htay-thwe)

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- Laravel Framework - [Laravel Team](https://laravel.com)
- Stripe Payment Gateway
- All open-source contributors

---

## 📊 Project Status

**Status**: Active Development

**Version**: 1.0.0

**Last Updated**: February 2026

---

<p align="center">Made with ❤️ by <a href="https://github.com/htay-htay-thwe">Htay Htay Thwe</a></p>

<p align="center">
  <a href="#-table-of-contents">Back to Top ⬆️</a>
</p>
├── storage/              # Logs and file storage
├── tests/                # Automated tests
├── .bash\_logout          # Bash logout script
├── .bash\_profile         # Bash profile script
├── .bashrc               # Bash configuration script
├── .dockerignore         # Docker ignore file
├── .editorconfig         # Editor configuration
├── .env.example          # Environment variables example
├── .gitattributes        # Git attributes
├── .gitignore            # Git ignore file
├── Dockerfile            # Docker configuration
├── README.md             # Project documentation
├── apache.conf           # Apache configuration
├── artisan               # Laravel command-line tool
├── composer.json         # PHP dependencies
├── composer.lock         # PHP dependency lock file
├── docker-compose.yml    # Docker Compose configuration
└── package-lock.json     # Node.js dependency lock file

````

---

## ⚙️ Installation & Setup

### Clone the repository

```bash
git clone https://github.com/htay-htay-thwe/Booking-Flights-Laravel.git
cd Booking-Flights-Laravel
````

### Copy and configure environment variables

```bash
cp .env.example .env
```

Update the `.env` file with your database credentials and other environment-specific settings.

### Build and start the Docker containers

```bash
docker-compose up -d
```

This command builds the Docker images and starts the containers in detached mode.

### Install PHP dependencies

```bash
docker-compose exec app composer install
```

### Generate the application key

```bash
docker-compose exec app php artisan key:generate
```

### Run database migrations

```bash
docker-compose exec app php artisan migrate
```

### Seed the database with sample data

```bash
docker-compose exec app php artisan db:seed
```

### Access the application

The backend API should now be accessible at `http://localhost:8000`.

---

## 🧪 Running Tests

To run the test suite:

```bash
docker-compose exec app php artisan test
```

This will execute the PHPUnit tests to ensure the integrity of your application.

---

## 📬 Contact

👤 **Your Name**
📧 Email: htayhtaythwe962@gmail.com

---

⭐ If you find this project useful, please consider giving it a **star**!

```

---

### 📌

This backend project is designed to work seamlessly with the [Booking Flights Frontend](https://github.com/htay-htay-thwe/Booking-Flights-Frontend), providing a full-stack solution for flight booking management. The use of Docker ensures a consistent development environment, making it easy to set up and collaborate on.

If you have any questions or need further information, feel free to reach out via the contact details provided above.

---
 
```
