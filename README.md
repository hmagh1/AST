# Astreinte API

## Overview
A RESTful API built with Symfony 5.4 to manage astreinte operations: administrators, on-call staff (astreignables), HR (DRH), main logs (main courantes), service records, and schedules. Includes unit and integration tests, and a CI-friendly setup.

## Requirements
- **PHP** >= 7.2.5 (tested on PHP 7.4.33) - [Download](https://www.php.net/downloads.php)
- **Composer** >= 1.17 - [Download](https://getcomposer.org/download/)
- **MySQL** >= 5.7 or MariaDB equivalent - [Download MySQL](https://dev.mysql.com/downloads/) / [MariaDB](https://mariadb.org/download/)
- **Symfony CLI** (optional) - [Install](https://symfony.com/download)
- **Node.js** & **npm** (for frontend) - [Download](https://nodejs.org/)

---

## Installation

1. Clone the repository and install PHP dependencies:
   ```bash
   git clone <repo-url> back
   cd back
   composer install
   ```

2. Copy `.env` and configure database URLs:
   ```bash
   cp .env .env.local
   # .env.local:
   # DATABASE_URL="mysql://db_user:db_pass@127.0.0.1:3306/astreinte"
   # TEST_TOKEN=test
   # CORS_ALLOW_ORIGIN="^https?://localhost(:[0-9]+)?$"
   ```

3. Create & migrate main database:
   ```bash
   php bin/console doctrine:database:create --env=dev
   php bin/console doctrine:migrations:migrate --env=dev
   ```

4. (Optional) Load fixtures:
   ```bash
   php bin/console doctrine:fixtures:load --env=dev
   ```

---

## API Routes

| Entity         | Base URL             | Example Endpoint         |
|----------------|----------------------|--------------------------|
| Admins         | `/api/admins`        | `GET /api/admins`        |
| Astreignables  | `/api/astreignables` | `POST /api/astreignables`|
| DRHs           | `/api/drhs`          | `GET /api/drhs/1`        |
| Main Courantes | `/api/maincourantes` | `DELETE /api/maincourantes/1` |
| Plannings      | `/api/plannings`     | `PUT /api/plannings/1`   |
| Services       | `/api/services`      | `GET /api/services`      |

Each resource supports typical REST verbs: `GET`, `POST`, `PUT`, `DELETE`.

---

## Project Structure

| Folder                         | Purpose                                |
|--------------------------------|----------------------------------------|
| `src/Entity/`                  | Doctrine entities (business objects)   |
| `src/Controller/`              | API controllers                        |
| `src/Repository/`              | Doctrine repositories (DB access)      |
| `src/Command/`                 | Symfony console commands (e.g. data copy) |
| `config/`                      | App configuration                      |
| `tests/AllUnitTests.php`       | Unit tests                             |
| `tests/AllIntegrationTests.php`| Integration tests                      |

---

## Testing

### Unit tests
```bash
php bin/phpunit --testsuite=Unit
```

### Integration tests
```bash
php bin/console doctrine:database:create --env=test --if-not-exists
php bin/console doctrine:schema:update --force --env=test

php bin/phpunit --testsuite=Integration
```

---

## CI / GitHub Actions

Sample `.github/workflows/ci.yml`:
```yaml
name: CI

on: [push, pull_request]

jobs:
  build:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:5.7
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: astreinte_test
        ports: ["3306:3306"]
        options: >-
          --health-cmd="mysqladmin ping -uroot -proot"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
          extensions: mbstring, intl, pdo_mysql

      - name: Install Composer deps
        run: composer install --prefer-dist --no-progress --no-suggest

      - name: Create & migrate test DB
        run: |
          php bin/console doctrine:database:create --env=test --if-not-exists
          php bin/console doctrine:schema:update --force --env=test

      - name: Run unit tests
        run: php bin/phpunit --testsuite=Unit

      - name: Run integration tests
        run: php bin/phpunit --testsuite=Integration
```

---

## CORS

Configured via **nelmio/cors-bundle** in `config/packages/nelmio_cors.yaml`:
```yaml
nelmio_cors:
    defaults:
        origin_regex: true
        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
        allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
        allow_headers: ['Content-Type', 'Authorization']
        expose_headers: ['Link']
        max_age: 3600
    paths:
        '^/': null
```

---

## Migrate test-to-prod data

To copy validated data from the test database (`astreinte_test`) to the main production database (`astreinte`), run the custom Symfony console command found in:

```
src/Command/ImportTestedDataCommand.php
```

Usage example:
```bash
php bin/console app:import-tested-data
```

---

## License
Proprietary – for internal use only.