[![CI](https://github.com/FutRR/FutEvent/actions/workflows/docker-image.yml/badge.svg)](https://github.com/FutRR/FutEvent/actions/workflows/docker-image.yml) [![CD](https://github.com/FutRR/FutEvent/actions/workflows/docker-publish.yml/badge.svg)](https://github.com/FutRR/FutEvent/actions/workflows/docker-publish.yml)

# Event Management Application

A Symfony 7.3 web application for managing events and categories with user authentication.

This project is **Docker-based** and uses **GitHub Actions CI** to ensure build and dependency stability.

---

## Features

- **Event Management**: Create, read, update, and delete events
- **Category Management**: Organize events by categories
- **User Authentication**: Secure login and registration system
- **Database**: MySQL (default) with Doctrine ORM
- **Modern UI**: Twig templating with asset management

---

## Requirements

### Recommended (Docker)

- Docker
- Docker Compose (v2)

> No local PHP or Composer installation is required when using Docker.

### Optional (without Docker)

- PHP 8.2 or higher
- Composer
- MySQL extension enabled
- Required PHP extensions (intl, pdo, gd, zip, etc.)

---

## Development (Docker – recommended)

### Start the application

From the project root:

```bash
docker compose up -d --build
```

Check container status

```bash
docker compose ps
```

---

### Install PHP dependencies

```bash
docker compose exec php composer install
```

---

### Database setup

Create the database and run migrations:

```bash
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate
```

(Optional) Load fixtures:

```bash
docker compose exec php php bin/console doctrine:fixtures:load
```

---

## Access the application

- Open the URL exposed in docker-compose.yml

- Usually: http://localhost:xxxx

---

## Useful docker commands

Stop containers

```bash
docker compose down
```

View logs

```bash
docker compose logs -f
```

Run Symfony console

```bash
docker compose exec php php bin/console
```

---

## CI

This project uses **GitHub Actions** to automatically:

Build the Docker image

Install Composer dependencies

Verify required PHP extensions (including gd)

The CI runs on every push and pull request to the master branch.

---

## Licence

MIT

---
