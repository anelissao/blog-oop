# PHP Blog System

A simple blog system built with PHP, featuring user authentication, article management, and a clean interface.

## Features

- User Authentication (Register/Login/Logout)
- Article Management (Create, Read, Update, Delete)
- Clean and Responsive Design
- No Framework Dependencies

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- PDO PHP Extension

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/blog-system.git
cd blog-system
```

2. Import the database schema:
```bash
mysql -u your_username -p < config/schema.sql
```

3. Update database configuration:
Edit `config/database.php` with your database credentials.

4. Start the PHP development server:
```bash
cd public
php -S localhost:8000
```

5. Visit `http://localhost:8000` in your browser

## Project Structure

```
blog-system/
├── config/
│   ├── database.php
│   └── schema.sql
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── index.php
├── src/
│   └── Models/
├── templates/
│   ├── header.php
│   ├── footer.php
│   └── ...
└── README.md
```

## Usage

1. Register a new account
2. Log in with your credentials
3. Create, edit, and delete articles
4. View articles on the homepage

## Security

- Password hashing using bcrypt
- PDO prepared statements for SQL injection prevention
- XSS protection with output escaping
- CSRF protection for forms

## License

MIT License
