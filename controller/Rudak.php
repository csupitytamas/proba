<?php
try {
    include('../Mysql.php');
    $mysql = new Mysql();
    $hossz = null;
    if (isset($_GET['hossz'])) {
        $hossz = $_GET['hossz'];
    }

    $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
    if(!is_null($hossz)) {
        $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak WHERE hossz = '{$hossz}'";
    }
    $queryRudak = $mysql->query($sqlRudak);
    header("Content-Type: application/json");
    echo json_encode($queryRudak);

} catch (Exception $exception) {
    die( $exception->getMessage() );
}