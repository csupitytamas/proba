SELECT * FROM  kitoro; /*legyüjti az összes kitöröt*/

SELECT * FROM rudak; --az összes rudat

SELECT * FROM palyak; -- az összes pályat gyűjti

SELECT * FROM raktar; -- visszadja az raktárban lévő adatokat

SELECT * FROM palyan; -- visszaadja ami nem a raktárban van

SELECT * FROM kitoro where id = 11; -- ez egy kitoro adatai lesznek ( a 11 egy változo kell majd, hogy legyen )

SELECT * FROM rudak where id = 11; -- ez egy rudak adatai lesznek ( a 11 egy változo kell majd, hogy legyen )

SELECT * FROM palyak where id = 11; -- a palya meghatározása ( a 11 egy változo kell majd, hogy legyen )

SELECT * FROM palyan where palya = 1 ; -- az adott pályán megtaláható rudak és kitötöket adja visza( a 1 az a palya id-ja)

SELECT * FROM raktar where rudak = 1 ; -- raktárban megtaláható rudat  adja visza( a 1 az a palya id-ja )

SELECT * FROM raktar where  kitoro = 1 ; --raktárban megtaláható kitorot adja visza( a 1 az a palya id-ja)

INSERT INTO kitoro (neve,db,kep) VALUES ("Példa1",1, "/img/kep1.jpg"); -- az uj cucc hozzáadasa ha még nem létezett( a zárojelben változok kellenek)

INSERT INTO rudak (neve,db, hossz,kep) VALUES ("Példa1",1,2.5, "/img/kep1.jpg");

INSERT INTO raktar (kitoro) VALUES ("lambay");

INSERT INTO raktar (rudak) VALUES ("piros");

INSERT INTO palyan (palya,kitoro) VALUES (1,11); -- az 1 es a palya id-ja, a kitoro id-ja

INSERT INTO palyan (palya,rudak) VALUES (1,11); -- az 1 es a palya id-ja, a rudak id-ja
