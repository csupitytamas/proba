# A 3306-os portot használó szolgáltalás ID-ját adja vissza.
netstat -ano | findstr :3306
# A visszakapott ID-val azonosítjuk mi a szolgáltatás, local mysql.exe lehet, azt is ki kell csapni
tasklist /FI "PID eq 6644"
# Folyamat kilövésem, hogy a XAMPP Mysql-t el lehessen indítani.
taskkill /F /PID 6644