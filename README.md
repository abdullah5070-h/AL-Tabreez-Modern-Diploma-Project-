# Al Tabreez Modern – Online Ordering System

A PHP/MySQL web application that lets customers browse and order traditional Omani products (musar, caps, sticks) online, with an admin area for managing the store. Built as my diploma project at UTAS Muscat.

## Features

**Customers**
- Registration and login (passwords stored hashed with `password_hash`)
- Product catalogue with categories and images
- Shopping cart and order placement with a chosen delivery date
- Payment by cash or card, with order status tracking (pending, paid, shipped, delivered, cancelled)

**Admin**
- Separate admin login
- Product and order management

## Tech Stack

- PHP 8 (PDO with prepared statements)
- MySQL
- HTML, CSS
- XAMPP for local development

## Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)

### Installation

1. Clone or download this repository into your XAMPP `htdocs` folder:
   ```bash
   cd C:/xampp/htdocs
   git clone https://github.com/abdullah5070-h/AL-Tabreez-Modern-Diploma-Project-.git
   ```
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`) and import `onlineorderingsystem.sql`. It creates the `OnlineOrderingSystem` database with all tables and sample products.
4. Database settings are in `dbconnect.php` (defaults: host `localhost`, user `root`, empty password). Change them if your setup differs.
5. Open `http://localhost/AL-Tabreez-Modern-Diploma-Project-/` in your browser.

### Demo admin account

- Page: `admin_login.php`
- Username: `admin`
- Password: `admin123`

All data in the SQL file is dummy test data.

## Database

Main tables: `users`, `products`, `orders`, `order_details`, `payments`, `staff`, `user_bank`.

## Security Notes

- SQL queries use PDO prepared statements.
- User and admin passwords are hashed; legacy plain-text admin passwords are upgraded to a hash on first login.
- This is a student project: card payment is simulated and no real card data should ever be entered.

## Screenshots

_Add screenshots of the home page, cart and admin dashboard here._

## Author

**Abdullah Alqassabi**
Information Systems graduate, UTAS Muscat
GitHub: [abdullah5070-h](https://github.com/abdullah5070-h)
