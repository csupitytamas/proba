# Samorin Show jumpoff

## Telepítés
1. A projekt CLI könyvtárában ki kell adni a következő parancsot:
```shell
composer install
```
2. Ez követően pedig meg kell határozni az adatbázis kapcsolatot. 
* Első lépésként navigáljunk az app/Database könyvtárba (CLI command hozzá:)
```shell
cd app/Database
```
   * Második lépés létre kell hozni a Credentials.php file-t
```shell
touch Credentials.php
```
   * A Credentials.php.example tartalmát át kell másolni a korábban létrehozott Credentials.php-ba
```shell
cp Credentials.php.example Credentials.php
```
   * A Credentials.php-ban az adatbázis kapcsolathoz szükséges adatokat meg kell adni.