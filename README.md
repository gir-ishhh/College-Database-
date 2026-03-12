# College Student Database Management System

A simple web-based application for managing student records including personal information and academic marks.

## 📚 Project Overview

This is a college database management system built with PHP and MySQL. It allows administrators to:
- View student grades and academic performance
- Update student personal information (name, class, DOB, contact)
- Update student marks across three subjects
- Delete student records from the database

## 🛠️ Technology Stack

- **Backend**: PHP (Procedural with MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Server**: XAMPP (Apache + MySQL)

## 📋 Prerequisites

- XAMPP installed (includes Apache and MySQL)
- PHP 7.2 or higher
- MySQL Server running
- Web browser (Chrome, Firefox, Edge, Safari)

## 🚀 Installation & Setup

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

### Step 2: Create Database
1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Create a new database named `college_db`
3. Import the SQL file:
   - Go to Import tab
   - Click "Choose File"
   - Select `database_setup.sql`
   - Click Import

### Step 3: Configure Database Connection
The database configuration is pre-set in `config.php`:
- Server: localhost
- Username: root
- Password: (empty)
- Database: college_db

If your MySQL setup is different, edit `config.php` with your credentials.

### Step 4: Access the Application
Open your browser and navigate to:
```
http://localhost/Project/
```

## 📖 How to Use

### 1. Display Results
- Click "Display Results" from the main menu
- Enter student roll number
- View student information and calculated grade
- Grades are calculated as:
  - **Distinction**: Average > 75
  - **First Class**: Average > 60
  - **Second Class**: Average > 50
  - **Fail**: Average ≤ 50

### 2. Update Student
- Click "Update Student" from the main menu
- Enter roll number to fetch student data
- Update personal information (Name, Class, DOB, Contact)
- Update marks for three subjects (0-100 range)
- Click respective update buttons

### 3. Delete Student
- Click "Delete Student" from the main menu
- Enter roll number
- Review student details
- Confirm deletion
- Student record and associated marks are removed

## 📁 Project Structure

```
Project/
├── index.html                    # Main menu page
├── display_result.php            # Student grades display
├── update_student.php            # Update student information & marks
├── delete_student.php            # Delete student records
├── config.php                    # Database configuration
├── database_setup.sql            # Database schema & sample data
├── style.css                     # Styling for all pages
├── README.md                     # This file
├── CODE_DOCUMENTATION.md         # Technical code documentation
├── STUDENT_GUIDE.md             # Student usage guide
└── STUDENT_GUIDE.md             # User guide
```

## 🔒 Security Features

- **SQL Injection Prevention**: All queries use prepared statements with parameterized queries
- **Input Validation**: Roll numbers, names, and marks are validated
- **Output Encoding**: All displayed data is HTML-encoded to prevent XSS
- **Database Constraints**: Foreign key relationships with CASCADE delete

## 📊 Database Schema

### Student Table
- Roll_Number (Primary Key)
- Name
- Class
- Date of Birth (DOB)
- Contact Number

### Marks Table
- Roll_Number (Foreign Key)
- M1, M2, M3 (Subject marks)

## 💡 Sample Data

The database includes sample students:
- Roll Number Examples: 119, 120, 121, 122, 123
- Sample Marks: 60-90 range

Use these for testing the application.

## ⚙️ Configuration

### Changing Database Credentials
Edit `config.php`:
```php
define('SERVER', 'localhost');        // Your MySQL server
define('USERNAME', 'root');           // Your MySQL username
define('PASSWORD', '');               // Your MySQL password
define('DATABASE', 'college_db');      // Your database name
```

### Changing Application Title
Edit `style.css` for styling changes:
- Colors in `:root` selector
- Font sizes for responsive design
- Button and form styling

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Page shows "Database connection failed" | Check if MySQL service is running in XAMPP |
| "No student found" message | Verify roll number exists in database with sample data |
| CSS not loading properly | Clear browser cache (Ctrl+Shift+Delete) and refresh |
| Forms not submitting | Ensure Apache and MySQL services are both running |

## 📝 Sample Roll Numbers for Testing

Try these roll numbers to test the system:
- 119
- 120
- 121
- 122
- 123

## 🎓 Learning Outcomes

This project demonstrates:
- PHP procedural programming with MySQLi
- HTML form handling and validation
- CSS responsive design
- MySQL database operations (CRUD)
- SQL prepared statements
- Web server configuration with XAMPP
- Security best practices in web applications

## 📧 Project Information

**Developer**: Girish Sapkale  
**Year**: Second Year (SY)  
**Subject**: Database Management / Web Development  
**Status**: Fully Functional ✅

## 📚 Additional Documentation

- See [CODE_DOCUMENTATION.md](CODE_DOCUMENTATION.md) for technical implementation details
- See [STUDENT_GUIDE.md](STUDENT_GUIDE.md) for detailed user instructions

## 📄 License

This is a college project for educational purposes.

---

**Last Updated**: March 2026  
**Version**: 1.0
