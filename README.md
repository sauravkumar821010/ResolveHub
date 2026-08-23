# 🏛️ ResolveHub — Full Complaint Management Portal

<p align="center">
  <strong>A centralized web-based platform for submitting, tracking, managing, and resolving complaints.</strong>
</p>

<p align="center">
  PHP 8+ • MySQL • HTML5 • CSS3 • JavaScript • XAMPP
</p>

---

# 📸 Screenshots

## 🏠 Landing Page

<p align="center">
  <img src="https://github.com/user-attachments/assets/2ca11e3b-56c0-45f5-aee6-21810051b0e4" alt="ResolveHub Landing Page" width="900">
</p>

<br>

## 📊 Dashboard

<p align="center">
  <img src="https://github.com/user-attachments/assets/8389d4c3-fd3b-4f0c-bced-e72c009e025b" alt="ResolveHub Dashboard" width="900">
</p>

<br>

## 📝 Complaint Management

<p align="center">
  <img src="https://github.com/user-attachments/assets/699623ab-e5d2-44b8-8aa0-819fbe1be7f8" alt="ResolveHub Complaint Management" width="900">
</p>

---
# Website Link:- https://resolvehub.great-site.net/

# ✨ Features

## 👤 Citizen Features

- Citizen registration and login
- Secure logout
- Citizen dashboard
- Complaint submission
- Auto-generated complaint ticket ID
- Complaint history
- Complaint tracking
- Complaint timeline
- Complaint status monitoring
- Account settings
- Password recovery

<br>

## 🧑‍💼 Admin & Staff Features

- Admin dashboard
- Staff role management
- Department management
- Complaint assignment
- Department-wise complaint management
- Staff-wise complaint assignment
- Complaint status updates
- Resolution remarks
- Complaint monitoring and tracking

<br>

## 🗄️ Database Features

- MySQL database integration
- Structured complaint records
- User and role management
- Department records
- Complaint status management
- PDO-based database connectivity

<br>

## 🎨 Interface

- Modern and clean user interface
- Responsive design
- Dashboard-based navigation
- Structured complaint workflow
- User-friendly forms
- Consistent visual design

---

# 🔄 Complaint Workflow

```text
Citizen
   │
   ▼
Register / Login
   │
   ▼
Submit Complaint
   │
   ▼
Generate Ticket ID
   │
   ▼
Department Assignment
   │
   ▼
Staff/Admin Review
   │
   ▼
Status Update
   │
   ▼
Resolution & Remarks
   │
   ▼
Citizen Tracks Complaint
```

---

# 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| 🐘 PHP 8+ | Backend development |
| 🗄️ MySQL | Database management |
| 🌐 HTML5 | Page structure |
| 🎨 CSS3 | Styling and responsive UI |
| ⚡ JavaScript | Client-side interactions |
| 🖥️ XAMPP | Local development environment |
| 🔧 Git | Version control |
| 🐙 GitHub | Source code hosting |

---

# 📁 Project Structure

```text
ResolveHub/
│
├── assets/
│   └── style.css
│
├── partials/
│   ├── header.php
│   └── footer.php
│
├── admin.php
├── auth.php
├── complaint.php
├── complaints.php
├── config.example.php
├── dashboard.php
├── db.php
├── departments.php
├── forgot_password.php
├── index.php
├── login.php
├── logout.php
├── manage_complaint.php
├── my_complaints.php
├── new_complaint.php
├── register.php
├── reset_password.php
├── schema.sql
├── settings.php
├── track.php
├── .htaccess
├── .gitignore
└── README.md
```

---

# 🚀 Setup with XAMPP

## 1. Clone the Repository

```bash
git clone https://github.com/sauravkumar821010/ResolveHub.git
```

Or download the repository as a ZIP file from GitHub.

<br>

## 2. Move the Project

Place the project inside:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\ResolveHub\
```

<br>

## 3. Start XAMPP

Open the XAMPP Control Panel and start:

- Apache
- MySQL

<br>

## 4. Create the Database

Open:

```text
http://localhost/phpmyadmin
```

Create the required MySQL database and import:

```text
schema.sql
```

<br>

## 5. Configure Database Connection

Create your local:

```text
config.php
```

using:

```text
config.example.php
```

as the template.

Add your own database credentials:

```php
define('DB_HOST', 'your_database_host');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
```

> ⚠️ **Important:** `config.php` contains local database credentials and should never be committed to the public repository.

<br>

## 6. Run ResolveHub

Open:

```text
http://localhost/ResolveHub/
```

---

# 🔐 Security

ResolveHub includes several security mechanisms:

- 🔑 Password hashing
- 👤 Authentication
- 👥 Role-based access control
- 🔒 Session-based authorization
- 🛡️ Protected administrative pages
- 🔐 Password recovery
- 🗝️ Database credentials separated from the public repository

<br>

## 🔒 Production Security Improvements

Before production deployment, the following improvements are recommended:

- CSRF protection
- Stronger access-control policies
- Rate limiting
- Secure file upload handling
- Audit logging
- Production HTTPS configuration
- Secure email/SMS notification systems

---

# 📈 Future Enhancements

Possible future improvements include:

- 📧 Email notifications
- 📱 SMS notifications
- 📎 Complaint attachment support
- 🔔 Real-time complaint status notifications
- 📊 Advanced analytics and charts
- 🗺️ Location-based complaint tracking
- ⭐ Citizen feedback and satisfaction ratings
- 📱 Dedicated mobile application
- 🤖 AI-assisted complaint categorization
- 🔍 Advanced search and filtering
- ⏰ SLA and complaint deadline monitoring

---

# 🎓 Project Purpose

ResolveHub was developed as a practical web development project to demonstrate:

- Full-stack web development
- Database management
- Authentication and authorization
- Role-based access control
- CRUD operations
- PHP–MySQL integration
- Dashboard development
- Complaint workflow management
- Git and GitHub version control
- Web application deployment

---

# 👨‍💻 Developer

## **Saurav Kumar**

**Project:** ResolveHub — Full Complaint Management Portal

ResolveHub is a practical implementation of:

- Web development
- Database management
- Authentication
- Authorization
- Role-based access control
- Complaint workflow management

---

# 📄 License

This project is developed primarily for **educational and portfolio purposes**.

---

# ⭐ Support

If you find **ResolveHub** useful or interesting, consider giving the repository a ⭐ on GitHub.

---

<p align="center">

<strong>🏛️ ResolveHub</strong>

<br><br>

<strong>Making Complaint Management Simpler, Faster & More Transparent.</strong>

</p>
