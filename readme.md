![CI](https://github.com/FutRR/FutEvent/actions/workflows/ci.yml/badge.svg)

# Event Management Application

A Symfony 7.3 web application for managing events and categories with user authentication.

## Features

- **Event Management**: Create, read, update, and delete events
- **Category Management**: Organize events by categories
- **User Authentication**: Secure login and registration system
- **Database**: SQLite database for data persistence
- **Modern UI**: Twig templating with asset management

## Requirements

- PHP 8.2 or higher
- Composer
- SQLite extension enabled

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env .env.local
   ```
   Update `.env.local` with your specific configuration if needed.

4. **Create the database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Load fixtures (optional)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

## Running the Application

### Development Server

Start the Symfony development server:

```bash
    symfony server:start
   ```

## Docker

This project includes a Docker setup (see `docker-compose.yml` and the `docker/` directory).

### Prerequisites

- Docker + Docker Compose (Compose v2 recommended)

### Start containers

From the project root:

```bash
docker compose up -d --build
```

Check status:

```bash
docker compose ps
```

### Install PHP dependencies (inside the container)

```bash
docker compose exec php composer install
```

### Database & migrations (inside the container)

```bash
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate
```

(Optional) load fixtures:

```bash
docker compose exec php php bin/console doctrine:fixtures:load
```

### Open the application

- Use the port exposed by `docker-compose.yml` (typically `http://localhost:xxxx`).

### Useful commands

Stop containers:

```bash
docker compose down
```

View logs:

```bash
docker compose logs -f
```

Run Symfony console commands:

```bash
docker compose exec php php bin/console
```

Run tests:

```bash
docker compose exec php ./vendor/bin/phpunit
```
