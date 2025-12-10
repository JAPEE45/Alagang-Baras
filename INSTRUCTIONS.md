# Alagang Baras - Livestock Management System Instructions

## Prerequisites
- **XAMPP** (or any PHP/MySQL development environment) installed.
- Web browser.

## Setup & Installation
1. **Start Servers:** Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. **Database Setup:**
   - Open your browser and go to `http://localhost/phpmyadmin`.
   - Create a new database named `baras`.
   - Import the SQL file located at: `c:\Users\Admin\Documents\xamp\htdocs\Alagang-Baras\baras (4).sql`.
3. **Configuration:**
   - Ensure the database connection settings in `config/db.php` match your environment (Default XAMPP settings are usually correct: User=`root`, Password=``).

## Accessing the System
- Open your web browser and navigate to:
  `http://localhost/Alagang-Baras/html/index.php`

## User Roles & Default Credentials
The system has three main user roles. You can use the following default credentials to log in:

### 1. Municipal Agriculture Officer (MAO)
*Admin-level access to manage the system, staff, and overall monitoring.*
- **Username:** `japee`
- **Password:** `japee`
- **Features:**
    - Dashboard Overview
    - Manage Coordinators (Register/Update)
    - Manage Veterinarians (Register/Update)
    - Livestock Health Monitoring
    - Generate Reports

### 2. Coordinator
*Field-level access for profiling owners and livestock.*
- **Username:** `coordinator`
- **Password:** `coordinator`
- **Features:**
    - Dashboard Overview
    - Owner Registration & Management
    - Livestock Profiling & Listing
    - Health Monitoring View
    - Generate Reports

### 3. Veterinarian (Vet)
*Access for managing animal health records.*
- **Username:** `vet123`
- **Password:** `vet4545`
- **Features:**
    - Dashboard Overview
    - Livestock Health Monitoring
    - Add New Health Records

## Troubleshooting
- **Database Connection Error:** Check `config/db.php` and ensure your MySQL server is running.
- **Login Failed:** Ensure you have imported the `baras (4).sql` file correctly into the `baras` database.
