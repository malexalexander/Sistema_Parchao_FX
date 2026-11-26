
<?php

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "parchao_db";

// Crear conexion
$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

// Comprobar conexion
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

// Configurar caracteres
$conexion->set_charset("utf8mb4");

?>
