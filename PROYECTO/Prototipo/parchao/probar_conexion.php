
<?php

require_once "config/conexion.php";

$sql = "SELECT * FROM roles";

$resultado = $conexion->query($sql);

echo "<h1>Roles registrados en PARCHAO</h1>";

while ($fila = $resultado->fetch_assoc()) {

    echo "<p>";
    echo $fila["id_rol"] . " - ";
    echo $fila["nombre"];
    echo "</p>";

}

?>
