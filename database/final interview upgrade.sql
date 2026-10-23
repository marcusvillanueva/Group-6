USE sun_son_solar;

ALTER TABLE employees ADD COLUMN IF NOT EXISTS role VARCHAR(20) NOT NULL DEFAULT 'employee';
ALTER TABLE employees ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE employees ADD COLUMN IF NOT EXISTS created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE customers ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE customers ADD COLUMN IF NOT EXISTS receive_reminders TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE customers ADD COLUMN IF NOT EXISTS receive_promotions TINYINT(1) NOT NULL DEFAULT 1;
ALTER TABLE customers ADD COLUMN IF NOT EXISTS created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP;

-- Services and products 
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    price DECIMAL(10,2) NULL,              
    is_available TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL,
    description VARCHAR(255) NULL,
    price DECIMAL(10,2) NULL,              
    is_available TINYINT(1) NOT NULL DEFAULT 1
);


-- Customer bookings 
CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    schedule_date DATE NOT NULL,
    schedule_time TIME NOT NULL,
    address VARCHAR(255) NOT NULL,
    notes TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    technician_id INT NULL,
    admin_note VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    FOREIGN KEY (technician_id) REFERENCES employees(employee_id) ON DELETE SET NULL
);

-- Payments and receipts 
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    customer_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method VARCHAR(30) NOT NULL,
    receipt_no VARCHAR(30) NOT NULL UNIQUE,
    recorded_by VARCHAR(100) NULL,
    paid_at DATETIME NULL,
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

-- Customer notifications 
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    type VARCHAR(20) NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

-- Support tickets 
CREATE TABLE IF NOT EXISTS support_tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    subject VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ticket_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    sender_type VARCHAR(20) NOT NULL,       
    sender_name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NULL,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(ticket_id) ON DELETE CASCADE
);

-- announcements, site time-in, problem reports
CREATE TABLE IF NOT EXISTS announcements (
    announcement_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    category VARCHAR(30) NOT NULL,          
    posted_by VARCHAR(100) NOT NULL,
    created_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    employee_name VARCHAR(100) NOT NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    time_in DATETIME NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

CREATE TABLE IF NOT EXISTS issue_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    employee_name VARCHAR(100) NOT NULL,
    schedule_id INT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'open',
    created_at DATETIME NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id) ON DELETE SET NULL
);

-- Free quote requests from the homepage 
CREATE TABLE IF NOT EXISTS quote_requests (
    quote_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NOT NULL,
    service_interest VARCHAR(100) NOT NULL,
    monthly_bill DECIMAL(10,2) NULL,
    message TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    created_at DATETIME NULL
);

-- System settings 
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL
);

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('auto_schedule_updates', '1'),   
('auto_payment_receipts', '1');   


-- Starting data from the interview
INSERT IGNORE INTO services (name, category, description, price) VALUES
('Consultation', 'Planning', 'We assess your property, usage, and goals to build the right solar plan.', 0.00),
('Designing', 'Planning', 'System layout and sizing based on your roof and energy needs.', NULL),
('Permitting', 'Planning', 'We prepare and follow up the permits needed for your installation.', NULL),
('Installation', 'Installation', 'Safe, planned installation of your panels, inverter, and wiring.', NULL),
('Maintenance', 'Aftercare', 'Regular inspections and cleaning to keep your system performing.', NULL),
('Repair', 'Aftercare', 'Troubleshooting and repair of panels, inverters, and wiring.', NULL),
('Monitoring', 'Aftercare', 'Ongoing performance monitoring of your solar system.', NULL);

-- Products named by the client.
INSERT IGNORE INTO products (name, category, description, price) VALUES
('Solar panels', 'Panels', 'Panels that convert sunlight into electricity for homes and businesses.', NULL),
('Inverters', 'Inverters', 'Convert solar DC power into AC power your appliances can use.', NULL),
('Batteries', 'Storage', 'Store extra solar energy for nighttime use and brownouts.', NULL),
('Racking and mounting', 'Mounting', 'Frames and mounts that hold panels securely on roofs or the ground.', NULL),
('Wires and cables', 'Electrical', 'Solar-rated wires and cables for safe connections.', NULL);

INSERT INTO employees
(firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department, role, is_active)
VALUES
('Sol', 'Solis', 'Sun', '1967-01-08', 'Male', 'sol.solis@sunsonsolar.com', '09291230983', 'Sun Son Solar Office, Pasig City', 'admin', '$2y$12$zLJOetPUKrLHSX5t1E6EWu7SBy9EDhMqKPhImhynF73ejRY32s8rO', 'IT', 'admin', 1)
ON DUPLICATE KEY UPDATE
firstname = 'Sol', lastname = 'Solis', middlename = 'Sun', birthdate = '1967-01-08', gender = 'Male',
email = 'sol.solis@sunsonsolar.com', phone = '09291230983', address = 'Sun Son Solar Office, Pasig City',
department = 'IT', role = 'admin', is_active = 1;

-- Katherine "Kat" Olap Sinagaraw (founder).
-- Username: KittyKat16   Password: K@tSunShine16
-- Phone is the company number, as Kat requested.
INSERT IGNORE INTO employees
(firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department, role, is_active)
VALUES
('Katherine', 'Sinagaraw', 'Olap', '1990-07-01', 'Female', 'katherine.sinagaraw@sunsonsolar.com', '09291230983', 'Sun Son Solar Office, Pasig City', 'KittyKat16', '$2y$10$oZmZ3c6CnyNMaSxViwXU9.RCsJfm461llZ42zXslBPFzV2Hrvn76u', 'Administration', 'admin', 1);
