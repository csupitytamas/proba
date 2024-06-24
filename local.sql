CREATE DATABASE myDB;

USE myDB;

CREATE TABLE kitoro (
	id BIGINT AUTO_INCREMENT UNIQUE,
    neve VARCHAR(255),
    db int,
    kep VARCHAR(255)
    
);
CREATE TABLE rudak (
	id BIGINT AUTO_INCREMENT UNIQUE,
    neve VARCHAR(255),
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
    rudak int default(null)

);
CREATE TABLE palyan (
	id BIGINT AUTO_INCREMENT UNIQUE,
    kitoro int default(null),
    rudak int default(null),
	palya int
);
INSERT INTO kitoro (neve,db,kep)
VALUES ("Példa1", 1, "img/kep1.jpg"),
        ("Példa2", 2, "img/kep2.jpg"),
        ("Példa3", 2, "img/kep3.jpg"),
        ("Példa4", 2, "img/kep4.jpg") ;

INSERT INTO rudak (neve,db, hossz,kep)
VALUES  ("Példa1", 1, 2.5, "img/kep1.jpg"),
        ("Példa2", 2, 2.5, "img/kep2.jpg"),
        ("Példa3", 2, 2.5, "img/kep3.jpg"),
        ("Példa4", 2, 2.5, "img/kep4.jpg") ;

INSERT INTO rudak (neve) VALUES
        ("stroge"),
        ("main"),
        ("respect"),
        ("farriers");

INSERT INTO palyak(id, neve)
VALUES  (1,"stroge"),
        (2,"main"),
        (3,"respect"),
        (4,"farriers");