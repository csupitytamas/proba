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
VALUES  (1, null, 2),
        (2, null, 2),
        (3, null, 2),
        (null, 1, 2),
        (null, 2, 2),
        (null, 3, 2),
        (null, 4, 2);