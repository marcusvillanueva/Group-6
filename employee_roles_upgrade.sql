USE sun_son_solar;

ALTER TABLE employees
ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'employee';