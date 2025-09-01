# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MenooElo is a multi-tenant digital menu system for restaurants built with PHP 7.4+ using MVC architecture. The system has three access levels:
- **Super Admin Panel**: System-wide management of all restaurants
- **Restaurant Admin Panel**: Individual restaurant menu and settings management  
- **Public Frontend**: Customer-facing digital menus

## Development Environment Setup

The project runs on XAMPP/WAMP with Apache and MySQL:

1. **Database Setup**: 
   - Run `install.php` to create database schema and initial data
   - Database config is in `config/config.php`
   - Schema is defined in `database/schema.sql`

2. **File Structure**:
   - `index.php` - Main entry point with routing
   - `includes/` - Core system classes (Database, Auth, Router, BaseController)
   - `controllers/` - MVC controllers (AdminController, SuperAdminController, etc.)
   - `views/` - PHP templates organized by user type
   - `uploads/` - User-uploaded images (logos, categories, menu items)
   - `config/config.php` - Database and system configuration

## Architecture Patterns

**MVC Structure**: 
- `BaseController` provides common functionality (database, auth, views, file uploads)
- Controllers extend BaseController for specific user types
- Views are organized by user role (`admin/`, `superadmin/`, `public/`)

**Database Access**:
- Singleton `Database` class with PDO
- Prepared statements for security
- Multi-tenant architecture with `restaurant_id` foreign keys

**Authentication System**:
- Session-based auth with CSRF protection
- Three user types: `super_admin`, `restaurant_admin`, `public`
- Role-based access control through `Auth` class

**Router System**:
- Custom PHP router in `includes/router.php`
- Pattern matching for dynamic routes like `/restaurant/{slug}`
- Controller-method mapping

## Key Development Commands

Since this is a PHP project without build tools:

- **Local Development**: Use XAMPP/WAMP, access via `http://localhost/menooelo`
- **Database Reset**: Re-run `install.php` (delete it afterward for security)
- **Logs**: Check Apache error logs for PHP errors
- **File Permissions**: Ensure `uploads/` directory is writable (755)

## Multi-Tenant Architecture

The system supports multiple restaurants with data isolation:
- Each restaurant has a unique `slug` for public URLs
- All menu data is scoped by `restaurant_id`
- Restaurant admins can only access their own data
- Super admins can manage all restaurants

## Image Management

- Images uploaded to `uploads/` with subdirectories by type
- File validation in `BaseController::uploadImage()`
- Supported formats: JPEG, PNG, WebP (max 5MB)
- Integration with Unsplash API for image suggestions

## Security Features

- Password hashing with bcrypt
- CSRF token protection on forms
- SQL injection protection via prepared statements
- XSS protection via input sanitization
- Activity logging for audit trails
- Session security configurations

## Database Schema Key Tables

- `restaurants` - Tenant data and settings
- `restaurant_admins` - Admin users per restaurant  
- `menu_categories` - Menu organization
- `menu_items` - Individual dishes/products
- `menu_item_variants` - Size variations (small/large)
- `menu_item_extras` - Add-ons and extras
- `activity_logs` - System audit trail