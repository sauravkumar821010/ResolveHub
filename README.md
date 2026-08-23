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
   Email: admin@resolvehub.local
   Password: Admin@123
8. Delete `seed_admin.php` after creating the admin.
9. Open `http://localhost/ResolveHub_Full_Portal/`.

## Important
This is an academic project prototype. Before real-world deployment, add CSRF protection, stronger access-control rules, rate limiting, secure file upload handling, email/SMS notifications, audit logging and production HTTPS configuration.

## Suggested team mapping
- Developer / Technical Expert: complaint workflow + integration
- Tester / QA: test cases and bug verification
- Business / Requirements Analyst: requirements, user stories, documentation
- Project Manager / Team Lead: coordination, schedule, integration tracking
