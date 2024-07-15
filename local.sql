CREATE DATABASE myDB;

USE myDB;

CREATE TABLE kitoro (
	id BIGINT AUTO_INCREMENT UNIQUE,
    name_en VARCHAR(255),
    name_hu VARCHAR(255),
    db int,
    kep VARCHAR(255)
);
CREATE TABLE rudak (
	id BIGINT AUTO_INCREMENT UNIQUE,
    name_en VARCHAR(255),
    name_hu VARCHAR(255),
    db int,
    hossz float,
    kep VARCHAR(255)
);
CREATE TABLE palyak (
	id BIGINT AUTO_INCREMENT UNIQUE,
    neve VARCHAR(255)
);
CREATE TABLE raktar (
	id BIGINT AUTO_INCREMENT UNIQUE,
    kitoro int default(null),
    rudak int default(null),
    db int,
    hossz float default(null)
);
CREATE TABLE palyan (
	id BIGINT AUTO_INCREMENT UNIQUE,
    kitoro int default(null),
    rudak int default(null),
	palya int,
    db int,
    hossz float default(null)
);

INSERT INTO palyak (id, neve)
VALUES  (1,"stroge"),
        (2,"main"),
        (3,"respect"),
        (4,"farriers");

INSERT INTO palyan (kitoro, rudak, palya, db, hossz)
VALUES  (1, null, 2, 4, null),
        (2, null, 2, 2, null),
        (3, null, 2, 6, null),
        (null, 1, 2, 6, 3.5),
        (null, 2, 2, 9, 3),
        (null, 3, 2, 12, 2.5),
        (null, 4, 2, 8, 2);

INSERT INTO kitoro (name_hu, name_en, db, kep)
VALUES  ('samorin', 'samorin', 2, 'test.jpg'),
        ('playboy', 'playboy', 4, 'test.jpg'),
        ('érme', 'coin', 6, 'test.jpg'),
        ('euro', 'euro', 8, 'test.jpg');

INSERT INTO rudak (name_hu, name_en, db, hossz, kep)
VALUES  ('kek', 'blue', 12, 2.5, 'test.jpg'),
        ('fekete', 'black', 10, 3.5, 'test.jpg'),
        ('sárga', 'yellow', 6, 2.5, 'test.jpg'),
        ('zöld', 'green', 15, 3, 'test.jpg'),
        ('piros', 'red', 7, 2, 'test.jpg');