# Modern PHP Blog CMS

A high-performance, modern PHP blog CMS built with performance and scalability in mind.

## Features

- Modern PHP 8.1+ with strict typing
- PSR-4 autoloading
- MVC architecture
- Database abstraction with PDO
- Multi-database support (MySQL, PostgreSQL, SQLite)
- Secure authentication system
- Responsive design with TailwindCSS
- Composer for dependency management
- Performance optimized queries
- Persistent database connections
- Comprehensive logging system
- Flexible caching system
- File-based queue system
- Complete admin panel
- RESTful routing
- Model relationships

## Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.2+ (if using MySQL)
- PostgreSQL 9.6+ (if using PostgreSQL)
- Composer

## Database Support

This application supports three database systems:

1. **MySQL** - Default option, most widely used
2. **PostgreSQL** - Advanced open-source database
3. **SQLite** - Lightweight file-based database, good for development

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Configure your database:
   
   ### For MySQL:
   ```
   DB_DRIVER=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=blog
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
   
   ### For PostgreSQL:
   ```
   DB_DRIVER=pgsql
   DB_HOST=localhost
   DB_PORT=5432
   DB_DATABASE=blog
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
   
   ### For SQLite:
   ```
   DB_DRIVER=sqlite
   DB_DATABASE=./database/blog.sqlite
   ```

4. Run database migrations:
   ```
   php cli.php migrate:up
   ```

5. (Optional) Seed test data:
   ```
   php seed.php
   ```

6. Start the development server:
   ```
   composer start
   ```

7. Visit `http://localhost:8000` in your browser.

## Project Structure

```
app/              # Application source code
  Controllers/    # Controller classes
  Core/           # Core framework classes
  Database/       # Database related classes
  Models/         # Model classes
bootstrap/        # Application bootstrap files
config/           # Configuration files
database/         # Database migrations
public/           # Publicly accessible files
resources/        # Views and other resources
routes/           # Route definitions
storage/          # Storage for logs, cache, etc.
tests/            # Test files
```

## Performance Optimizations

- Persistent database connections
- Prepared statements for all queries
- Efficient query building
- Proper indexing in database schema
- Lazy loading of services
- Caching system for improved response times

## System Components

### Logging System
- PSR-3 compliant logger
- Configurable log levels
- File-based logging

### Cache System
- File-based caching
- Configurable TTL
- Support for cache tags

### Queue System
- File-based queue implementation
- Queue worker for processing jobs
- Support for delayed jobs

### Admin Panel
- Dashboard with statistics
- CRUD operations for posts
- User management
- Category and tag management

## Testing

Run the test suite:
```
composer test
```

## License

This project is open-source and available under the MIT License.