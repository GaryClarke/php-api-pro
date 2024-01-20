-- 4-flight-entity

CREATE TABLE flights (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(8) NOT NULL, origin VARCHAR(3) NOT NULL, destination VARCHAR(3) NOT NULL, departure_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', arrival_time DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

INSERT INTO flights (number, origin, destination, departure_time, arrival_time)
VALUES
    ('JF1001', 'ABC', 'DEF', '2024-01-21 08:00:00', '2024-01-21 10:00:00'),
    ('JF1002', 'GHI', 'JKL', '2024-01-21 09:00:00', '2024-01-21 11:30:00'),
    ('JF1003', 'MNO', 'PQR', '2024-01-21 10:00:00', '2024-01-21 12:00:00'),
    ('JF1004', 'STU', 'VWX', '2024-01-21 11:00:00', '2024-01-21 13:30:00'),
    ('JF1005', 'YZA', 'BCD', '2024-01-21 12:00:00', '2024-01-21 14:00:00'),
    ('JF1006', 'EFG', 'HIJ', '2024-01-21 13:00:00', '2024-01-21 15:30:00'),
    ('JF1007', 'KLM', 'NOP', '2024-01-21 14:00:00', '2024-01-21 16:00:00'),
    ('JF1008', 'QRS', 'TUV', '2024-01-21 15:00:00', '2024-01-21 17:30:00'),
    ('JF1009', 'WXY', 'ZAB', '2024-01-21 16:00:00', '2024-01-21 18:00:00'),
    ('JF1010', 'CDE', 'FGH', '2024-01-21 17:00:00', '2024-01-21 19:30:00');
