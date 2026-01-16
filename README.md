# Pharmacy Management System - Backend API

Laravel 10+ API for Pharmacy Management System

## Technology Stack
- Laravel 10+
- MySQL 8.0+
- Docker
- AWS Ready

## Setup Instructions

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Run migrations:
```bash
php artisan migrate
```

5. Seed database (optional):
```bash
php artisan db:seed
```

6. Start server:
```bash
php artisan serve
```

## API Documentation

All API endpoints are prefixed with `/api`

### Authentication
- POST /api/register
- POST /api/login
- POST /api/logout
- GET /api/user

### Medicines
- GET /api/medicines
- POST /api/medicines
- GET /api/medicines/{id}
- PUT /api/medicines/{id}
- DELETE /api/medicines/{id}
- GET /api/medicines/barcode/{barcode}

### Barcode Entries
- GET /api/barcode-entries
- POST /api/barcode-entries
- GET /api/barcode-entries/{id}

### Stock Monitoring
- GET /api/stock/alerts
- POST /api/stock/check
- GET /api/stock/dashboard

### Subscriptions
- GET /api/subscriptions
- POST /api/subscriptions
- GET /api/subscriptions/invoice/{id}
- PUT /api/admin/subscriptions/{id}/approve

### Notifications
- GET /api/notifications
- PUT /api/notifications/{id}/read
- GET /api/notifications/unread-count

### Admin
- GET /api/admin/users
- GET /api/admin/users/{id}
- PUT /api/admin/users/{id}
- GET /api/admin/dashboard
- GET /api/admin/subscriptions/pending

