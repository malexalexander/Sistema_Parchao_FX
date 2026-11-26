
<?php

require_once "../../config/conexion.php";

// Verificar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST["id"] ?? 0);
    $estado = intval($_POST["estado"] ?? -1);

    // Validar ID y estado
    if ($id > 0 && ($estado == 0 || $estado == 1)) {

        $sql = "UPDATE categorias
                SET activo = ?
                WHERE id_categoria = ?";

        $consulta = $conexion->prepare($sql);

        $consulta->bind_param(
            "ii",
            $estado,
            $id
        );

        $consulta->execute();
        $consulta->close();
    }
}

// Regresar al listado
header("Location: index.php");
exit;

?>
