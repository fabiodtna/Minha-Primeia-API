<?php

$host = "localhost";
$usuario = "id16238932_cliente";
$senha = "Wclick@2020!" ;
$bd = "id16238932_wclickbd";

$strcon = mysqli_connect($host, $usuario, $senha, $bd);


if (!$strcon) {
    die("Connection failed: " . mysqli_connect_error());
}


?>