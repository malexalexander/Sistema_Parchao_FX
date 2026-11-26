<?php

require_once "../../config/conexion.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Producto no válido.");
}

$id = intval($_GET["id"]);

// Obtener categorías
$categorias = $conexion->query(
    "SELECT id_categoria, nombre 
     FROM categorias 
     WHERE activo = 1
     ORDER BY nombre"
);

// Obtener producto
$stmt = $conexion->prepare(
    "SELECT * FROM productos WHERE id_producto = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("El producto no existe.");
}

$producto = $resultado->fetch_assoc();


// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $id_categoria = intval($_POST["id_categoria"]);
    $descripcion = trim($_POST["descripcion"]);
    $ingredientes = trim($_POST["ingredientes"]);
    $precio = floatval($_POST["precio"]);
    $stock = intval($_POST["stock"]);

    $disponible = isset($_POST["disponible"]) ? 1 : 0;
    $controla_stock = isset($_POST["controla_stock"]) ? 1 : 0;

    $stmt = $conexion->prepare(
        "UPDATE productos
         SET id_categoria = ?,
             nombre = ?,
             descripcion = ?,
             ingredientes = ?,
             precio = ?,
             stock = ?,
             controla_stock = ?,
             disponible = ?
         WHERE id_producto = ?"
    );

    $stmt->bind_param(
        "isssdiiii",
        $id_categoria,
        $nombre,
        $descripcion,
        $ingredientes,
        $precio,
        $stock,
        $controla_stock,
        $disponible,
        $id
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar el producto.";
    }
}

$tituloPagina = "Editar producto";
include "../includes/encabezado.php";
?>

<div class="panel">

    <h2>Editar producto</h2>

    <form method="POST">

        <label>Nombre del producto</label>
        <input
            type="text"
            name="nombre"
            value="<?php echo htmlspecialchars($producto["nombre"]); ?>"
            required
        >

        <label>Categoría</label>
        <select name="id_categoria" required>

            <?php while ($categoria = $categorias->fetch_assoc()): ?>

                <option
                    value="<?php echo $categoria["id_categoria"]; ?>"
                    <?php
                    if ($categoria["id_categoria"] == $producto["id_categoria"]) {
                        echo "selected";
                    }
                    ?>
                >
                    <?php echo htmlspecialchars($categoria["nombre"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Descripción</label>

        <textarea name="descripcion" rows="4"><?php
            echo htmlspecialchars($producto["descripcion"]);
        ?></textarea>

        <label>Ingredientes</label>

        <textarea name="ingredientes" rows="4"><?php
            echo htmlspecialchars($producto["ingredientes"]);
        ?></textarea>

        <label>Precio</label>

        <input
            type="number"
            name="precio"
            step="0.01"
            min="0"
            value="<?php echo $producto["precio"]; ?>"
            required
        >

        <label>Stock</label>

        <input
            type="number"
            name="stock"
            min="0"
            value="<?php echo $producto["stock"]; ?>"
        >

        <label>
            <input
                type="checkbox"
                name="controla_stock"
                <?php
                if ($producto["controla_stock"]) {
                    echo "checked";
                }
                ?>
            >
            Controlar stock
        </label>

        <br><br>

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
            Producto disponible
        </label>

        <br><br>

        <button type="submit">
            Guardar cambios
        </button>

        <a href="index.php" class="btn">
            Cancelar
        </a>

    </form>

</div>

<?php include "../includes/pie.php"; ?>