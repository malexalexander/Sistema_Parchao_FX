
<?php

require_once "../../config/conexion.php";

$mensaje = "";

// Obtener categorías activas
$sql_categorias = "SELECT * FROM categorias
                   WHERE activo = 1
                   ORDER BY nombre ASC";

$categorias = $conexion->query($sql_categorias);

// Registrar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {

    $id_categoria = intval($_POST["id_categoria"]);
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $ingredientes = trim($_POST["ingredientes"]);
    $precio = floatval($_POST["precio"]);
    $stock = intval($_POST["stock"]);
    $controla_stock = isset($_POST["controla_stock"]) ? 1 : 0;
    $disponible = isset($_POST["disponible"]) ? 1 : 0;

    $imagen = "";

    // Comprobar la imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {

        $extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $extensiones_permitidas = ["jpg", "jpeg", "png", "webp"];

        if (in_array($extension, $extensiones_permitidas)) {

            if ($_FILES["imagen"]["size"] <= 3 * 1024 * 1024) {

                $nombre_imagen = uniqid("producto_", true) . "." . $extension;

                $ruta = "../../uploads/productos/" . $nombre_imagen;

                if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta)) {
                    $imagen = "uploads/productos/" . $nombre_imagen;
                } else {
                    $mensaje = "No se pudo guardar la imagen.";
                }

            } else {
                $mensaje = "La imagen no debe superar los 3 MB.";
            }

        } else {
            $mensaje = "Formato no permitido. Usa JPG, PNG o WEBP.";
        }
    }

    // Guardar producto
    if ($mensaje == "" && $nombre != "" && $precio >= 0 && $stock >= 0 && $id_categoria > 0) {

        $sql = "INSERT INTO productos
        (id_categoria, nombre, descripcion, ingredientes, precio,
         imagen, stock, controla_stock, disponible)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $consulta = $conexion->prepare($sql);

        $consulta->bind_param(
            "isssdsiii",
            $id_categoria,
            $nombre,
            $descripcion,
            $ingredientes,
            $precio,
            $imagen,
            $stock,
            $controla_stock,
            $disponible
        );

        if ($consulta->execute()) {
            $mensaje = "Producto registrado correctamente.";
        } else {
            $mensaje = "Error al registrar el producto.";
        }

        $consulta->close();

    } elseif ($mensaje == "") {
        $mensaje = "Revisa los datos ingresados.";
    }

    // Actualizar categorías
    $categorias = $conexion->query($sql_categorias);
}

// Consultar productos y sus categorías
$sql_productos = "SELECT productos.*, categorias.nombre AS categoria
                  FROM productos
                  INNER JOIN categorias
                  ON productos.id_categoria = categorias.id_categoria
                  ORDER BY productos.id_producto DESC";

$productos = $conexion->query($sql_productos);

// Cargar diseño administrativo
$tituloPagina = "Productos";
include "../includes/encabezado.php";

?>

<!-- FORMULARIO DE REGISTRO -->

<div class="panel">

    <h2>Registrar producto</h2>

    <p>Agrega nuevos productos al menú del restaurante.</p>

    <?php if ($mensaje != ""): ?>

        <div style="
            margin-top: 15px;
            padding: 12px;
            background: #FFF4E1;
            border-radius: 7px;
        ">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">

        <label>Categoría</label>

        <select name="id_categoria" required>

            <option value="">Seleccionar categoría</option>

            <?php while ($categoria = $categorias->fetch_assoc()): ?>

                <option value="<?php echo $categoria["id_categoria"]; ?>">
                    <?php echo htmlspecialchars($categoria["nombre"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Nombre del producto</label>

        <input
            type="text"
            name="nombre"
            placeholder="Ejemplo: Hamburguesa clásica"
            maxlength="120"
            required
        >

        <label>Descripción</label>

        <textarea
            name="descripcion"
            rows="3"
            placeholder="Descripción del producto"
        ></textarea>

        <label>Ingredientes</label>

        <textarea
            name="ingredientes"
            rows="3"
            placeholder="Ingredientes del producto"
        ></textarea>

        <label>Precio ($)</label>

        <input
            type="number"
            name="precio"
            step="0.01"
            min="0"
            placeholder="0.00"
            required
        >

        <label>Fotografía del producto</label>

        <input
            type="file"
            name="imagen"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <label>Stock</label>

        <input
            type="number"
            name="stock"
            min="0"
            value="0"
        >

        <div style="margin-top: 15px;">

            <label>
                <input type="checkbox" name="controla_stock">
                Controlar stock
            </label>

        </div>

        <div style="margin-top: 12px;">

            <label>
                <input type="checkbox" name="disponible" checked>
                Producto disponible
            </label>

        </div>

        <button type="submit" name="registrar" style="margin-top: 20px;">
            Registrar producto
        </button>

    </form>

</div>


<!-- LISTADO DE PRODUCTOS -->

<div class="panel" style="margin-top: 25px;">

    <h2>Productos registrados</h2>

    <p>Consulta y modifica los productos del restaurante.</p>

    <div class="table-container" style="margin-top: 20px;">

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Disponible</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($producto = $productos->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $producto["id_producto"]; ?>
                        </td>

                        <td>

                            <?php if ($producto["imagen"] != ""): ?>

                                <img
                                    src="../../<?php echo htmlspecialchars($producto["imagen"]); ?>"
                                    width="80"
                                    alt="Fotografía del producto"
                                >

                            <?php else: ?>

                                Sin imagen

                            <?php endif; ?>

                        </td>

                        <td>
                            <?php echo htmlspecialchars($producto["nombre"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($producto["categoria"]); ?>
                        </td>

                        <td>
                            $<?php echo number_format($producto["precio"], 2); ?>
                        </td>

                        <td>
                            <?php echo $producto["stock"]; ?>
                        </td>

                        <td>

                            <?php echo $producto["disponible"] ? "Sí" : "No"; ?>

                        </td>

                        <td>

                            <a
                                href="editar.php?id=<?php echo $producto["id_producto"]; ?>"
                                class="btn"
                            >
                                Editar
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "../includes/pie.php"; ?>
