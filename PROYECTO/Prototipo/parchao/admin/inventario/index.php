<?php

require_once "../../config/conexion.php";

$mensaje = "";

// Actualizar inventario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_producto = intval($_POST["id_producto"]);
    $stock = intval($_POST["stock"]);
    $disponible = isset($_POST["disponible"]) ? 1 : 0;

    $stmt = $conexion->prepare(
        "UPDATE productos
         SET stock = ?, disponible = ?
         WHERE id_producto = ?"
    );

    $stmt->bind_param(
        "iii",
        $stock,
        $disponible,
        $id_producto
    );

    if ($stmt->execute()) {
        $mensaje = "Inventario actualizado correctamente.";
    }
}

// Obtener productos
$productos = $conexion->query(
    "SELECT 
        p.id_producto,
        p.nombre,
        p.stock,
        p.controla_stock,
        p.disponible,
        c.nombre AS categoria
     FROM productos p
     INNER JOIN categorias c
        ON p.id_categoria = c.id_categoria
     WHERE p.activo = 1
     ORDER BY p.nombre"
);

$tituloPagina = "Inventario";
include "../includes/encabezado.php";
?>

<div class="panel">

    <h2>Control de inventario</h2>

    <p style="margin-top:10px;">
        Consulta y actualiza las existencias de los productos.
    </p>

    <?php if ($mensaje != ""): ?>

        <div style="
            margin-top:20px;
            padding:12px;
            background:#e8f7e8;
            border-radius:7px;
        ">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

</div>

<div class="table-container" style="margin-top:25px;">

    <table>

        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Control de stock</th>
            <th>Disponible</th>
            <th>Acción</th>
        </tr>

        <?php if ($productos && $productos->num_rows > 0): ?>

            <?php while ($producto = $productos->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $producto["id_producto"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($producto["nombre"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($producto["categoria"]); ?>
                    </td>

                    <td>

                        <form method="POST" style="display:flex; gap:8px;">

                            <input
                                type="hidden"
                                name="id_producto"
                                value="<?php echo $producto["id_producto"]; ?>"
                            >

                            <input
                                type="number"
                                name="stock"
                                min="0"
                                value="<?php echo $producto["stock"]; ?>"
                                style="width:100px; margin:0;"
                            >

                    </td>

                    <td>
                        <?php
                        echo $producto["controla_stock"]
                            ? "Sí"
                            : "No";
                        ?>
                    </td>

                    <td>

                        <label>

                            <input
                                type="checkbox"
                                name="disponible"
                                <?php
                                if ($producto["disponible"]) {
                                    echo "checked";
                                }
                                ?>
                            >

                            Disponible

                        </label>

                    </td>

                    <td>

                            <button type="submit">
                                Actualizar
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">
                    No existen productos registrados.
                </td>
            </tr>

        <?php endif; ?>

    </table>

</div>

<?php include "../includes/pie.php"; ?>