# Project Overview

This project is a Laravel 11 application integrated with Inertia.js, React, Tailwind CSS, and Socialite for social authentication. It also includes Docker support for containerization, a GitHub Actions workflow for continuous integration/deployment, and is configured to deploy on Fly.io using the `fly.toml` file.

## Features

- **Laravel 11** with [laravel/framework](https://github.com/laravel/framework)
- **React**-based frontend in `resources`
- **Tailwind CSS** for styling
- **Socialite** for social authentication
- **Inertia.js** for server-side rendering (`inertiajs/inertia-laravel`)
- **Docker** & **GitHub Actions** for CI/CD
- **Deployment** via Fly.io (`fly.toml`)

## Directory Structure

- `app` – Application logic, including Models, Policies, Services.
- `bootstrap` – Laravel bootstrapping scripts.
- `config` – Framework and package configuration.
- `database` – Migrations, seeders, and factories.
- `public` – Publicly accessible files and the front controller.
- `resources` – React components, views, and Tailwind CSS files.
- `routes` – Route definitions (`web.php` is the main entry point).
- `tests` – Feature and unit tests (`ProfileControllerTest.php`, etc.).
- `Dockerfile` – Multi-stage Docker build.
- `fly.toml` – Configuration for Fly.io deployment.
- `composer.json` & `composer.lock` – PHP dependencies.
- `package.json` – Node.js dependencies.
- `phpunit.xml` – PHPUnit test configuration.
- `vite.config.js` – Frontend build configuration using Vite.

## Installation

1. **Clone the repo** and switch to the project directory.
2. **Copy `.env.example`** to `.env` and update environment variables:
   ```bash
   cp .env.example .env
   ```
3. **Install Composer dependencies**:
   ```bash
   composer install
   ```
4. **Install Node.js dependencies**:
   ```bash
   npm install
   ```
5. **Generate an application key**:
   ```bash
   php artisan key:generate
   ```
6. **Run database migrations**:
   ```bash
   php artisan migrate
   ```

## Local Development

Use **Vite** to build and watch assets:

```bash
  npm run dev
```

Serve the application locally:

```bash
  php artisan serve
```

## Testing

Use the Laravel test runner:

```bash
  php artisan test
```

Or **PHPUnit** directly:

```bash
  vendor/bin/phpunit
```

## Docker & Deployment

A [`Dockerfile`](Dockerfile) is provided for containerization. GitHub Actions builds and pushes images to Fly.io. The [`fly.toml`](fly.toml) file has Fly.io deployment config. To deploy manually:
  ```
  docker build -t your-app-image .
  docker tag your-app-image registry.fly.io/your-fly-app:latest
  docker push registry.fly.io/your-fly-app:latest
  fly deploy
  ```