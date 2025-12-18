# Hipstr Laravel Setup - Installed Packages

## Core Laravel Framework
- **Laravel Framework**: v12.42.0 - The latest Laravel framework
- **Laravel Breeze**: v2.3.8 - Authentication scaffolding with Blade + Alpine.js
- **Laravel Sanctum**: v4.2.1 - API authentication system
- **Laravel Telescope**: v5.16.0 - Debug assistant for Laravel applications

## Essential Packages

### Authentication & Authorization
- **Spatie Laravel Permission**: v6.23.0 - Role and permission management
- **Laravel Sanctum**: API token authentication

### Development & Debugging
- **Laravel Debugbar**: v3.16.1 - Debug bar for development
- **Laravel Telescope**: Application debugging and monitoring
- **Laravel Pint**: Code style fixer
- **Laravel Tinker**: REPL for Laravel

### Image Processing
- **Intervention Image**: v3.11.5 - Image manipulation library

### Data Management
- **Yajra DataTables**: v12.6.3 - Server-side DataTables processing
- **Spatie Laravel Backup**: v9.3.6 - Database and file backup solution
- **Spatie Laravel Activity Log**: v4.10.2 - Activity logging

### Frontend
- **Vite**: Modern build tool
- **Alpine.js**: Lightweight JavaScript framework
- **Tailwind CSS**: Utility-first CSS framework (via Breeze)

## Database
- **SQLite**: Default database (database/database.sqlite)
- All migrations have been run successfully

## Configuration Notes
- Dark mode support enabled in Breeze
- PHPUnit selected as testing framework
- Process timeout increased to 600 seconds for Composer
- All package configurations published where applicable

## Getting Started
1. Copy `.env.example` to `.env` and configure your environment
2. Run `php artisan serve` to start the development server
3. Visit `/register` to create your first user account
4. Access Telescope at `/telescope` for debugging (development only)

## Useful Commands
```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Create new migration
php artisan make:migration create_table_name

# Create new model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller ControllerName

# Run tests
php artisan test

# Build assets
npm run build

# Watch assets for changes
npm run dev
```
