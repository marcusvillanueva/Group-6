CREATE DATABASE IF NOT EXISTS sun_son_solar;
USE sun_son_solar;

CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50) NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50) NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'employee'
);

-- Initial customer user required by the activity.
-- Password: SunSolar123!
INSERT IGNORE INTO customers
(firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password)
VALUES
('Maria', 'Santos', 'Lopez', '2002-05-14', 'Female', 'maria.santos@example.com', '09171234567', 'Pasig City, Metro Manila', 'customer01', '$2y$12$RqodnYYWeMruNd2KO7OVeu7ExESAaLEW9mXoNrvbicPkIzZo8oZjS');

-- Initial employee user required by the activity.
-- Password: Employee123!
INSERT IGNORE INTO employees
(firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department)
VALUES
('Juan', 'Dela Cruz', 'Reyes', '1998-09-21', 'Male', 'juan.delacruz@example.com', '09181234567', 'Mandaluyong City, Metro Manila', 'employee01', '$2y$12$uA4cVDb64F6sxgOkzNPPLusZniX16tezuHZkrZipAEs1Y1aKCzLKC', 'Operations');
