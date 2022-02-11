<?php

$host = "localhost";
$usuario = "root";
$senha = "root" ;
$bd = "wclickbd";

$strcon = mysqli_connect($host, $usuario, $senha, $bd);


if (!$strcon) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
