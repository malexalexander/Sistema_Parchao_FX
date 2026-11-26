
<?php

require_once "../../config/conexion.php";

// Consultar pedidos
$pedidos = $conexion->query(
    "SELECT *
     FROM pedidos
     ORDER BY fecha_creacion DESC"
);

$tituloPagina = "Administración de pedidos";
include "../includes/encabezado.php";

?>

<div class="panel">

    <h2>Administración de pedidos</h2>

    <p style="margin-top:10px;">
        Consulta los pedidos registrados y administra sus estados.
    </p>

</div>

<div class="table-container" style="margin-top:25px;">

    <table>

        <tr>
            <th>Código</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

        <?php if ($pedidos && $pedidos->num_rows > 0): ?>

            <?php while ($pedido = $pedidos->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($pedido["codigo"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($pedido["nombre_cliente"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($pedido["tipo_pedido"]); ?>
                    </td>

                    <td>
                        $<?php echo number_format($pedido["total"], 2); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($pedido["estado_pedido"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($pedido["fecha_creacion"]); ?>
                    </td>

                    <td>
                        <a
                            href="editar.php?id=<?php echo $pedido["id_pedido"]; ?>"
                            class="btn"
                        >
                            Editar
                        </a>
                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">
                    Todavía no existen pedidos registrados.
                </td>
            </tr>

        <?php endif; ?>

    </table>

</div>

<?php include "../includes/pie.php"; ?>
