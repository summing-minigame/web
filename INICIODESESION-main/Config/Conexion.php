<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "sena";

    $conexion = new mysqli($host, $user, $pass, $db);

    if (!$conexion) {
        echo "Conexion fallida";
    }
?>