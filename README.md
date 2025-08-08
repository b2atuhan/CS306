## 🎯 Project Overview

This project showcases advanced database management concepts through a practical e-commerce and support system. The application demonstrates the implementation of database triggers, stored procedures, and hybrid database architecture using both MySQL and MongoDB.

## 🏗️ Architecture

- **Frontend**: PHP with HTML/CSS
- **Primary Database**: MySQL (for transactional data)
- **Secondary Database**: MongoDB (for support tickets)
- **Dependencies**: MongoDB PHP Driver via Composer

## 📁 Project Structure

```
CS306/
├── composer.json                 # PHP dependencies
├── composer.lock                 # Locked dependency versions
├── CS306_GROUP_4_HW3_SQLDUMP.sql # Database schema and sample data
├── scripts/
│   ├── admin/                    # Admin interface
│   │   ├── index.php            # Admin dashboard
│   │   └── ticket_detail.php    # Ticket management
│   └── user/                     # User interface
│       ├── index.php            # Main user homepage
│       ├── db_mongo.php         # MongoDB connection
│       ├── db_mysql.php         # MySQL connection
│       ├── mongo_test.php       # MongoDB testing
│       ├── phpinfo.php          # PHP configuration
│       ├── support/             # Support system
│       │   ├── create_ticket.php
│       │   ├── ticket_detail.php
│       │   └── tickets.php
│       ├── trigger_*.php        # Trigger demonstrations (4 files)
│       └── procedure_*.php      # Stored procedure demos (4 files)
└── vendor/                       # Composer dependencies
```

## 🚀 Features

### Database Triggers
1. **Trigger 1** - Order Amount Warning (Ahmet Batuhan Baykal)
   - Fires on INSERT to 'orders' table
   - Logs warning if amount > 1000
   - Location: `scripts/user/trigger_1.php`

2. **Trigger 2** - Email Change Tracking (Cağlar Uysal)
   - Fires on UPDATE to 'users' table
   - Tracks email changes in separate table
   - Location: `scripts/user/trigger_2.php`

3. **Trigger 3** - Product Archiving (Doğukan Doğan)
   - Fires on DELETE from 'products' table
   - Archives deleted products
   - Location: `scripts/user/trigger_3.php`

4. **Trigger 4** - Spam Detection (Ahmet Batuhan Baykal)
   - Fires on INSERT to 'reviews' table
   - Checks for spam content
   - Location: `scripts/user/trigger_4.php`

### Stored Procedures
1. **Procedure 1** - Get User Orders (Ahmet Batuhan Baykal)
   - Returns all orders for a specific user
   - Location: `scripts/user/procedure_1.php`

2. **Procedure 2** - Update Product Price (Cağlar Uysal)
   - Updates price for a specific product
   - Location: `scripts/user/procedure_2.php`

3. **Procedure 3** - Get Active Tickets (Doğukan Doğan)
   - Lists all active support tickets
   - Location: `scripts/user/procedure_3.php`

4. **Procedure 4** - Add Review (Ahmet Batuhan Baykal)
   - Adds a review for a product
   - Location: `scripts/user/procedure_4.php`

### Support Ticket System
- **User Interface**: Create and view support tickets
- **Admin Interface**: Manage and resolve tickets
- **MongoDB Integration**: Ticket storage and retrieval
- **Real-time Status**: Active/Resolved ticket tracking

## 🛠️ Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- MongoDB 4.0 or higher
- Composer (for dependency management)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd CS306
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Database Setup**
   - Import the SQL dump:
     ```bash
     mysql -u root -p < CS306_GROUP_4_HW3_SQLDUMP.sql
     ```
   - Ensure MongoDB is running on localhost:27017

4. **Configuration**
   - Update database credentials in `scripts/user/db_mysql.php`:
     ```php
     $host = 'localhost';
     $user = 'your_username';
     $password = 'your_password';
     $database = 'cs306_project';
     ```

5. **Web Server Setup**
   - Point your web server to the `scripts` directory
   - Ensure PHP has MongoDB extension enabled

## 🎮 Usage

### Access Points
- **User Homepage**: `http://localhost/scripts/user/index.php`
- **Admin Dashboard**: `http://localhost/scripts/admin/index.php`
- **Support System**: `http://localhost/scripts/user/support/tickets.php`

### Testing Features
1. **Triggers**: Use the interactive buttons on each trigger page to test different scenarios
2. **Procedures**: Enter parameters and execute stored procedures
3. **Support System**: Create and manage support tickets

## 📊 Database Schema

### MySQL Tables
- `users` - User information
- `orders` - Order transactions
- `products` - Product catalog
- `reviews` - Product reviews
- `support_tickets` - Support ticket metadata
- `user_email_changes` - Email change history
- `spam_reviews` - Detected spam reviews
- `archived_products` - Deleted products archive

### MongoDB Collections
- `tickets` - Support ticket details

## 🔧 Technical Details

### Database Connections
- **MySQL**: Traditional relational database for transactional data
- **MongoDB**: Document database for flexible ticket storage

### Security Features
- SQL injection prevention through prepared statements
- XSS protection with `htmlspecialchars()`
- Input validation and sanitization

### Error Handling
- Comprehensive error reporting
- Graceful failure handling
- User-friendly error messages

## 🐛 Troubleshooting

### Common Issues
1. **MongoDB Connection Failed**
   - Ensure MongoDB service is running
   - Check if MongoDB extension is installed: `php -m | grep mongodb`

2. **MySQL Connection Failed**
   - Verify database credentials in `db_mysql.php`
   - Ensure MySQL service is running

3. **Composer Dependencies**
   - Run `composer install` to install missing dependencies
   - Check PHP version compatibility

## 📝 License

This project is created for educational purposes as part of CS306 Database Management Systems course.

---

**Last Updated**: December 2024  
**Course**: CS306 Database Management Systems  
**Institution**: [Your University Name]
