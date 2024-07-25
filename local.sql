CREATE TABLE users (
    id BIGINT AUTO_INCREMENT UNIQUE,
    username VARCHAR(255),
    password CHAR(255)
);
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
    kitoro int default NULL,
    rudak int default NULL,
    db int,
    hossz float default NULL
);
CREATE TABLE palyan (
	id BIGINT AUTO_INCREMENT UNIQUE,
    kitoro int default NULL,
    rudak int default NULL,
	palya int,
    db int,
    hossz float default NULL
);

INSERT INTO palyak (neve)
VALUES  ('main'),
        ('respect'),
        ('farriers');

INSERT INTO raktar (kitoro, rudak, db, hossz)
VALUES  (1, null, 2, null),
        (2, null, 4, null),
        (3, null, 4, null),
        (4, null, 8, null),
        (null, 1, 12, 2.5),
        (null, 2, 10, 3.5),
        (null, 3, 6, 2.5),
        (null, 4, 15, 3),
        (null, 5, 7, 2);


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