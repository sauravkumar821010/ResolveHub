

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(30) NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('citizen','staff','admin') NOT NULL DEFAULT 'citizen',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS complaints (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id VARCHAR(30) NOT NULL UNIQUE,
  user_id INT UNSIGNED NOT NULL,
  department_id INT UNSIGNED NULL,
  assigned_to INT UNSIGNED NULL,
  subject VARCHAR(180) NOT NULL,
  category VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  priority ENUM('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  status ENUM('Pending','In Progress','Resolved','Closed','Rejected') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_complaint_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_complaint_department FOREIGN KEY(department_id) REFERENCES departments(id) ON DELETE SET NULL,
  CONSTRAINT fk_complaint_staff FOREIGN KEY(assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS complaint_updates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  complaint_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  status VARCHAR(30) NOT NULL,
  remark TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_update_complaint FOREIGN KEY(complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
  CONSTRAINT fk_update_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT IGNORE INTO departments(name) VALUES
('Public Works'),('Water Supply'),('Electricity'),('Waste Management'),('Public Safety'),('General Administration');
