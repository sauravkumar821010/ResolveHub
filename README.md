
<img width="2873" height="1612" alt="Screenshot 2026-08-23 161401" src="https://github.com/user-attachments/assets/2ca11e3b-56c0-45f5-aee6-21810051b0e4" />

<img width="2869" height="1555" alt="Screenshot 2026-08-23 161416" src="https://github.com/user-attachments/assets/8389d4c3-fd3b-4f0c-bced-e72c009e025b" />

<img width="2863" height="1549" alt="Screenshot 2026-08-23 161445" src="https://github.com/user-attachments/assets/699623ab-e5d2-44b8-8aa0-819fbe1be7f8" />

# ResolveHub — Full Complaint Management Portal

## Included
- Modern landing page matching the ResolveHub visual direction
- Citizen registration/login/logout
- Citizen dashboard
- Complaint submission
- Auto-generated complaint ticket ID
- Complaint history and tracking
- Complaint timeline
- Admin dashboard
- Staff role
- Department management
- Complaint assignment to department/staff
- Status updates and remarks
- MySQL database
- Responsive UI

## Stack
PHP 8+ / MySQL / HTML / CSS / JavaScript (small UI script)

## Setup with XAMPP
1. Copy the `ResolveHub_Full_Portal` folder into `C:/xampp/htdocs/`.
2. Start Apache and MySQL from XAMPP.
3. Open phpMyAdmin.
4. Import `schema.sql`.
5. Check `config.php` and make sure MySQL username/password match your XAMPP setup.
6. Open `http://localhost/ResolveHub_Full_Portal/seed_admin.php` once.
7. Use:
   Email: xxxxxxxx
   Password: xxxxxxx
8. Delete `seed_admin.php` after creating the admin.
9. Open `https://resolvehub.great-site.net/`.

## Important
This is an academic project prototype. Before real-world deployment, add CSRF protection, stronger access-control rules, rate limiting, secure file upload handling, email/SMS notifications, audit logging and production HTTPS configuration.

## Suggested team mapping
- Developer / Technical Expert: complaint workflow + integration
- Tester / QA: test cases and bug verification
- Business / Requirements Analyst: requirements, user stories, documentation
- Project Manager / Team Lead: coordination, schedule, integration tracking

📈 Future Enhancements

Possible future improvements include:

📧 Email notifications
📱 SMS notifications
📎 Complaint attachment support
🔔 Real-time status notifications
📊 Advanced analytics and charts
🗺️ Location-based complaint tracking
⭐ Citizen feedback and satisfaction ratings
📱 Mobile application
🤖 AI-assisted complaint categorization
🔍 Advanced search and filtering
📅 SLA and complaint deadline monitoring
🎓 Project Purpose

ResolveHub was developed as a practical web development project to demonstrate the implementation of:

Full-stack web development
Database management
Authentication and authorization
Role-based access control
CRUD operations
PHP–MySQL integration
Dashboard development
Complaint workflow management
Git and GitHub version control
Deployment of a web application
👨‍💻 Developer
Saurav Kumar

Computer Science / Engineering Student

Project: ResolveHub — Complaint Management Portal

The project was developed as a hands-on implementation of web development, database management, authentication, and software engineering concepts.

📄 License

This project is intended primarily for educational and portfolio purposes.

You are welcome to explore the source code and learn from the implementation.

⭐ Support

If you find this project useful or interesting, consider giving the repository a ⭐ on GitHub.
