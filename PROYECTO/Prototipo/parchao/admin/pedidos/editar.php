
<?php

require_once "../../config/conexion.php";

// Validar ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Pedido no válido.");
}

$id = intval($_GET["id"]);

$mensaje = "";

// Actualizar estado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $estado = $_POST["estado"];

    $estadosPermitidos = [
        "PENDIENTE",
        "PAGADO",
        "EN_PREPARACION",
        "LISTO",
        "EN_CAMINO",
        "ENTREGADO",
        "ANULADO"
    ];

    if (in_array($estado, $estadosPermitidos, true)) {

        $stmt = $conexion->prepare(
            "UPDATE pedidos
             SET estado_pedido = ?
             WHERE id_pedido = ?"
        );

        $stmt->bind_param("si", $estado, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "No se pudo actualizar el pedido.";
        }

    } else {
        $mensaje = "Estado no válido.";
    }
}

// Consultar pedido
$stmt = $conexion->prepare(
    "SELECT *
     FROM pedidos
     WHERE id_pedido = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("El pedido no existe.");
}

$pedido = $resultado->fetch_assoc();

// Consultar productos del pedido
$stmtDetalle = $conexion->prepare(
    "SELECT *
     FROM detalle_pedido
     WHERE id_pedido = ?"
);

$stmtDetalle->bind_param("i", $id);
$stmtDetalle->execute();

$detalles = $stmtDetalle->get_result();

$tituloPagina = "Editar pedido";
include "../includes/encabezado.php";

?>

<div class="panel">

    <h2>Información del pedido</h2>

    <p style="margin-top:15px;">
        <strong>Código:</strong>
        <?php echo htmlspecialchars($pedido["codigo"]); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Cliente:</strong>
        <?php echo htmlspecialchars($pedido["nombre_cliente"]); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Teléfono:</strong>
        <?php echo htmlspecialchars($pedido["telefono"] ?? "No registrado"); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Tipo de pedido:</strong>
        <?php echo htmlspecialchars($pedido["tipo_pedido"]); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Dirección:</strong>
        <?php echo htmlspecialchars($pedido["direccion"] ?? "No aplica"); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Observaciones:</strong>
        <?php echo htmlspecialchars($pedido["observaciones"] ?? "Ninguna"); ?>
    </p>

    <p style="margin-top:10px;">
        <strong>Total:</strong>
        $<?php echo number_format($pedido["total"], 2); ?>
    </p>

</div>

<div class="table-container" style="margin-top:25px;">

    <h2>Productos del pedido</h2>

    <table style="margin-top:15px;">

        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>

        <?php if ($detalles->num_rows > 0): ?>

            <?php while ($detalle = $detalles->fetch_assoc()): ?>

                <tr>
                    <td>
                        <?php echo htmlspecialchars($detalle["nombre_producto"]); ?>
                    </td>

                    <td>
                        $<?php echo number_format($detalle["precio_unitario"], 2); ?>
                    </td>

                    <td>
                        <?php echo $detalle["cantidad"]; ?>
                    </td>

                    <td>
                        $<?php echo number_format($detalle["subtotal"], 2); ?>
                    </td>
                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="4">Este pedido no tiene productos registrados.</td>
            </tr>

        <?php endif; ?>

    </table>

</div>

<div class="panel" style="margin-top:25px;">

    <h2>Modificar estado del pedido</h2>

    <?php if ($mensaje != ""): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Estado del pedido</label>

        <select name="estado" required>

            <?php

            $estados = [
                "PENDIENTE",
                "PAGADO",
                "EN_PREPARACION",
                "LISTO",
                "EN_CAMINO",
                "ENTREGADO",
                "ANULADO"
            ];

            foreach ($estados as $estado):

            ?>

                <option
                    value="<?php echo $estado; ?>"
                    <?php
                    if ($pedido["estado_pedido"] == $estado) {
                        echo "selected";
                    }
                    ?>
                >
                    <?php echo $estado; ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit">
            Guardar cambios
        </button>

        <a href="index.php" class="btn">
            Regresar
        </a>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
