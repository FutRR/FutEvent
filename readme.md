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
