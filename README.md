# Cavakuz System

Cavakuz System is a Laravel-based business management backend designed to support core operational workflows such as customer management, inventory tracking, sales processing, task management, accounting, and notifications.

This repository contains the server-side application and data layer. The frontend UI is maintained separately in the companion repository:
- https://github.com/jezreelferguson/Cavakuz-sys-ui

## Overview

This system is built around common business operations, including:

- User authentication and role-based access
- Customer management
- Inventory item tracking
- Sales and sales item records
- Task tracking
- Customer notifications
- Expense tracking

The project uses Laravel 13 and is structured as a modular backend application suitable for integration with a separate frontend client.

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL/PostgreSQL/SQLite-compatible database
- Composer
- Vite for frontend asset pipeline
- Blade templating
- PHPUnit for testing

## Features

- Secure authentication and user management
- Role support for users
- Customer records and customer notification tracking
- Inventory management
- Sales and transactional records
- Sales item breakdowns
- Task lifecycle tracking
- Expense logging
- REST-friendly Laravel architecture for API integration

## Project Structure

```text
cavakuz-sys/
├── app/
│   ├── Http/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
├── routes/
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── README.md
├── vite.config.js
└── LICENSE
