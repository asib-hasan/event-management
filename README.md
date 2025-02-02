# Event Management System

This is a simple **Event Management System** built with **Raw PHP**, following the **MVC architecture**.

Project Overview

The Event Management System allows users to create, manage, and participate in events. Authenticated users can organize events, while any user can view and register for them. The system ensures smooth event management with essential features like authentication, event registration, and attendee list downloads.

Features

1. Event Creation & Management

Authenticated users can create their own events.

Users can edit, update, and delete their events.

2. Event Participation

Any visitors can view available events, register for them.

Users can register for any event of their choice.

3. Attendee Management

Authenticated users can download the list of attendees for their events in CSV format.

## **Setup Instructions**

### **Prerequisites**
Make sure you have the following installed:

- [XAMPP](https://www.apachefriends.org/download.html) or [WAMP](https://www.wampserver.com/en/)
- PHP 7.4 or higher
- MySQL Database

---

## **Step 1: Clone or Download the Project**

### **Option 1: Clone the repository using Git**
```sh
cd C:\xampp\htdocs

git clone https://github.com/asib-hasan/event-management
```

### **Option 2: Download the ZIP file**
- Download the ZIP file from **GitHub**.
- Extract it inside the `htdocs` (for XAMPP) or `www` (for WAMP) directory.

---

## **Step 2: Configure Database**

1. **Start XAMPP/WAMP** and open **phpMyAdmin** by visiting:  
   `http://localhost/phpmyadmin/`
2. **Create a new database** named `event_management`.
3. **Import the SQL file:**
    - Go to **phpMyAdmin** → Click on `event_management` database.
    - Click the **Import** tab.
    - Select the file located at `root/database.sql`.
    - Click **Go** to import the database.

---

## **Step 3: Configure Database Connection**

1. Open the project folder and navigate to `config/Database.php`.
2. Update the database credentials:

```php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=event_management", "root", "");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
```

If your MySQL has a password, update the `"root", ""` section with your credentials.

---

## **Step 4: Start the Application**

### **For XAMPP Users:**
1. Open **XAMPP Control Panel**.
2. Start **Apache** and **MySQL**.
3. Open your browser and go to:
   ```
   http://localhost/event-management/
   ```

### **For WAMP Users:**
1. Start **WAMP Server**.
2. Click the **WAMP icon** in the system tray and open `localhost`.
3. Open your browser and go to:
   ```
   http://localhost/event-management/
   ```

---

## **Troubleshooting**

### **1. Error: "Database Connection Failed"**
- Make sure MySQL is running.
- Check `config/Database.php` and ensure correct credentials.
- Verify that the `event_management` database exists in **phpMyAdmin**.

### **2. "404 Not Found" Issues**
- Ensure `.htaccess` is properly configured.
- Try enabling `mod_rewrite` in Apache:
    - Open `httpd.conf` (`C:\xampp\apache\conf\httpd.conf`).
    - Find `#LoadModule rewrite_module modules/mod_rewrite.so` and remove `#`.
    - Restart Apache.


    
## **Author**
Developed by **MD. KAIUM HASAN AL ASIB**  
GitHub: [asib-hasan](https://github.com/asib-hasan)

