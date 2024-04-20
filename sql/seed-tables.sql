-- Drop the 'reservations' table first because it has foreign keys referencing the other two tables
DROP TABLE IF EXISTS reservations;

-- Then drop the 'flights' and 'passengers' tables
DROP TABLE IF EXISTS flights;
DROP TABLE IF EXISTS passengers;

-- Create the flights table
CREATE TABLE flights (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(20) NOT NULL, origin VARCHAR(3) NOT NULL, destination VARCHAR(3) NOT NULL, departure_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', arrival_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Create the passengers table
CREATE TABLE passengers (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(20) NOT NULL, firstNames VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, passportNumber VARCHAR(20) DEFAULT NULL, dateOfBirth DATE NOT NULL, nationality VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E3578E8AAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Create the reservations table
CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, flight_id INT DEFAULT NULL, passenger_id INT DEFAULT NULL, reference VARCHAR(20) NOT NULL, seatNumber VARCHAR(10) NOT NULL, travelClass VARCHAR(20) NOT NULL, createdAt DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', cancelledAt DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_4DA239AEA34913 (reference), INDEX IDX_4DA23991F478C5 (flight_id), INDEX IDX_4DA2394502E565 (passenger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE reservations ADD CONSTRAINT FK_4DA23991F478C5 FOREIGN KEY (flight_id) REFERENCES flights (id);
ALTER TABLE reservations ADD CONSTRAINT FK_4DA2394502E565 FOREIGN KEY (passenger_id) REFERENCES passengers (id);

-- Seed the flights table
INSERT INTO flights (number, origin, destination, departure_time, arrival_time)
VALUES
    ('JF1001-20250101', 'ABC', 'DEF', '2025-01-01 08:00:00', '2025-01-01 10:00:00'),
    ('JF1002-20250102', 'GHI', 'JKL', '2025-01-02 09:00:00', '2025-01-02 11:30:00'),
    ('JF1003-20250103', 'MNO', 'PQR', '2025-01-03 10:00:00', '2025-01-03 12:00:00'),
    ('JF1004-20250104', 'STU', 'VWX', '2025-01-04 11:00:00', '2025-01-04 13:30:00'),
    ('JF1005-20250105', 'YZA', 'BCD', '2025-01-05 12:00:00', '2025-01-05 14:00:00'),
    ('JF1006-20250106', 'EFG', 'HIJ', '2025-01-06 13:00:00', '2025-01-06 15:30:00'),
    ('JF1007-20250107', 'KLM', 'NOP', '2025-01-07 14:00:00', '2025-01-07 16:00:00'),
    ('JF1008-20250108', 'QRS', 'TUV', '2025-01-08 15:00:00', '2025-01-08 17:30:00'),
    ('JF1009-20250109', 'WXY', 'ZAB', '2025-01-09 16:00:00', '2025-01-09 18:00:00'),
    ('JF1010-20250110', 'CDE', 'FGH', '2025-01-10 17:00:00', '2025-01-10 19:30:00');

-- Seed the passengers table
INSERT INTO passengers (reference, firstNames, lastName, passportNumber, dateOfBirth, nationality)
VALUES
    ('1713363801SMI', 'John', 'Smith', 'PS100001', '1980-05-15', 'USA'),
    ('1713363802DOE', 'Jane', 'Doe', 'PS100002', '1985-08-22', 'UK'),
    ('1713363803LEE', 'Bruce', 'Lee', 'PS100003', '1973-11-27', 'Canada'),
    ('1713363804KHA', 'Amir', 'Khan', 'PS100004', '1992-01-12', 'India'),
    ('1713363805BRO', 'Charlie', 'Brown', 'PS100005', '1990-07-09', 'USA'),
    ('1713363806JOH', 'Michael', 'Johnson', 'PS100006', '1975-02-17', 'Australia'),
    ('1713363807WIL', 'Jessica', 'Wilson', 'PS100007', '1988-03-23', 'UK'),
    ('1713363808GAR', 'Elena', 'Garcia', 'PS100008', '1994-12-15', 'Spain'),
    ('1713363809CHA', 'Sophie', 'Chapman', 'PS100009', '1987-05-25', 'France'),
    ('1713363810MAR', 'Lucas', 'Martin', 'PS100010', '1991-09-30', 'Germany'),
    ('1713363811RIC', 'Julia', 'Richards', 'PS100011', '1983-04-11', 'Canada'),
    ('1713363812MOR', 'Edward', 'Moore', 'PS100012', '1979-07-19', 'USA'),
    ('1713363813DAV', 'Olivia', 'Davis', 'PS100013', '1986-10-28', 'Australia'),
    ('1713363814MIL', 'George', 'Miller', 'PS100014', '1993-02-05', 'New Zealand'),
    ('1713363815AND', 'Isabella', 'Anderson', 'PS100015', '1984-12-03', 'UK'),
    ('1713363816TAY', 'Matthew', 'Taylor', 'PS100016', '1990-03-07', 'USA'),
    ('1713363817THO', 'Liam', 'Thompson', 'PS100017', '1978-01-26', 'Ireland'),
    ('1713363818WAL', 'Grace', 'Walker', 'PS100018', '1992-11-09', 'UK'),
    ('1713363819LEE', 'Hannah', 'Lee', 'PS100019', '1995-06-20', 'South Korea'),
    ('1713363820KIM', 'David', 'Kim', 'PS100020', '1989-04-14', 'South Korea'),
    ('1713363821LI', 'Xin', 'Li', 'PS100021', '1974-03-15', 'China'),
    ('1713363822ZHA', 'Mei', 'Zhao', 'PS100022', '1981-08-22', 'China'),
    ('1713363823QUE', 'Carlos', 'Quero', 'PS100023', '1987-07-13', 'Mexico'),
    ('1713363824FUE', 'Maria', 'Fuente', 'PS100024', '1994-10-17', 'Spain'),
    ('1713363825SIL', 'Pedro', 'Silva', 'PS100025', '1980-02-26', 'Brazil'),
    ('1713363826ROS', 'Anna', 'Rossi', 'PS100026', '1977-11-03', 'Italy'),
    ('1713363827BER', 'Nora', 'Bernard', 'PS100027', '1983-06-14', 'France'),
    ('1713363828VAN', 'Willem', 'Van Dijk', 'PS100028', '1976-09-29', 'Netherlands'),
    ('1713363829HOL', 'Sven', 'Holm', 'PS100029', '1971-05-07', 'Sweden'),
    ('1713363830KOV', 'Ivan', 'Kovac', 'PS100030', '1965-12-18', 'Croatia');

-- Seed the reservations table
INSERT INTO reservations (flight_id, passenger_id, reference, seatNumber, travelClass, createdAt)
VALUES
    (1, 1, '1713363801JF1001', '1A', 'Economy', '2025-01-01 08:00:00'),
    (1, 2, '1713363802JF1001', '1B', 'First', '2025-01-01 08:05:00'),
    (1, 3, '1713363803JF1001', '1C', 'Business', '2025-01-01 08:10:00'),
    (1, 4, '1713363804JF1001', '1D', 'Economy', '2025-01-01 08:15:00'),
    (1, 5, '1713363805JF1001', '1E', 'First', '2025-01-01 08:20:00'),
    (1, 6, '1713363806JF1001', '1F', 'Business', '2025-01-01 08:25:00'),
    (1, 7, '1713363807JF1001', '2A', 'Economy', '2025-01-01 08:30:00'),
    (1, 8, '1713363808JF1001', '2B', 'First', '2025-01-01 08:35:00'),
    (1, 9, '1713363809JF1001', '2C', 'Business', '2025-01-01 08:40:00'),
    (1, 10, '1713363810JF1001', '2D', 'Economy', '2025-01-01 08:45:00'),
    (1, 11, '1713363811JF1001', '2E', 'First', '2025-01-01 08:50:00'),
    (1, 12, '1713363812JF1001', '2F', 'Business', '2025-01-01 08:55:00'),
    (1, 13, '1713363813JF1001', '3A', 'Economy', '2025-01-01 09:00:00'),
    (1, 14, '1713363814JF1001', '3B', 'First', '2025-01-01 09:05:00'),
    (1, 15, '1713363815JF1001', '3C', 'Business', '2025-01-01 09:10:00'),
    (1, 16, '1713363816JF1001', '3D', 'Economy', '2025-01-01 09:15:00'),
    (1, 17, '1713363817JF1001', '3E', 'First', '2025-01-01 09:20:00'),
    (1, 18, '1713363818JF1001', '3F', 'Business', '2025-01-01 09:25:00'),
    (1, 19, '1713363819JF1001', '4A', 'Economy', '2025-01-01 09:30:00'),
    (1, 20, '1713363820JF1001', '4B', 'First', '2025-01-01 09:35:00'),
    (1, 21, '1713363821JF1001', '4C', 'Business', '2025-01-01 09:40:00'),
    (1, 22, '1713363822JF1001', '4D', 'Economy', '2025-01-01 09:45:00'),
    (1, 23, '1713363823JF1001', '4E', 'First', '2025-01-01 09:50:00'),
    (1, 24, '1713363824JF1001', '4F', 'Business', '2025-01-01 09:55:00'),
    (1, 25, '1713363825JF1001', '5A', 'Economy', '2025-01-01 10:00:00'),
    (1, 26, '1713363826JF1001', '5B', 'First', '2025-01-01 10:05:00'),
    (1, 27, '1713363827JF1001', '5C', 'Business', '2025-01-01 10:10:00'),
    (1, 28, '1713363828JF1001', '5D', 'Economy', '2025-01-01 10:15:00'),
    (1, 29, '1713363829JF1001', '5E', 'First', '2025-01-01 10:20:00'),
    (1, 30, '1713363830JF1001', '5F', 'Business', '2025-01-01 10:25:00');

