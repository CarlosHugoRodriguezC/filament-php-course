# Filament PHP Course

## Introduction

This is an application from the Filament PHP Course. It is a management system for employees and departments.

## Installation

1. Clone the repository

```bash
git clone
```

2. Install dependencies

```bash
composer install
```

3. Create a database and configure the `.env` file based on the `.env.example` file

4. Run the migrations

```bash
php artisan migrate
```

5. Seed the database

```bash
php artisan db:seed
```

> [!NOTE]
> Before seeding the database, unzip the `database/data/countries-states-cities.zip` file, in order to seed the database with a bunch of countries, states, and cities.

6. Serve the application

```bash
php artisan serve
```
