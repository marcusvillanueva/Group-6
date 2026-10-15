-- Activity 2 upgrade for a database that was already created during Activity 1.
-- Run this only once against the existing sun_son_solar database.
USE sun_son_solar;

-- Add a role field so the login logic can distinguish employees, HR, and Admin.
ALTER TABLE employees
ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'employee';

-- Create or reset the initial Admin account from the interview notes.
-- Username: admin
-- Password: admin123
INSERT INTO employees
(firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department, role)
VALUES
('System', 'Administrator', NULL, '2000-01-01', 'Prefer not to say', 'admin@example.com', '0000000000', 'Sun Son Solar Office', 'admin', '$2y$12$zLJOetPUKrLHSX5t1E6EWu7SBy9EDhMqKPhImhynF73ejRY32s8rO', 'Human Resources', 'admin')
ON DUPLICATE KEY UPDATE
password = '$2y$12$zLJOetPUKrLHSX5t1E6EWu7SBy9EDhMqKPhImhynF73ejRY32s8rO',
role = 'admin',
department = 'Human Resources';
