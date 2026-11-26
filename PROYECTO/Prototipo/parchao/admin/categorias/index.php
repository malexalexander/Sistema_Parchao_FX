
<?php

require_once "../../config/conexion.php";

$mensaje = "";

// Registrar categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);

    if ($nombre != "") {

        $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";

        $consulta = $conexion->prepare($sql);
        $consulta->bind_param("ss", $nombre, $descripcion);

        if ($consulta->execute()) {
            $mensaje = "Categoría registrada correctamente.";
        } else {
            $mensaje = "Error al registrar la categoría.";
        }

        $consulta->close();

    } else {

        $mensaje = "El nombre es obligatorio.";

    }
}

// Consultar categorías
$sql = "SELECT * FROM categorias ORDER BY id_categoria DESC";
$resultado = $conexion->query($sql);

// Cargar diseño administrativo
$tituloPagina = "Categorías";
include "../includes/encabezado.php";

?>

<div class="panel">

    <h2>Registrar categoría</h2>

    <p>Agrega nuevas categorías para organizar los productos del restaurante.</p>

    <?php if ($mensaje != ""): ?>

        <div style="
            margin-top: 15px;
            padding: 12px;
            background: #e8f5e9;
            border-radius: 7px;
        ">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

    <form method="POST" style="margin-top: 20px;">

        <label>Nombre de la categoría</label>

        <input
            type="text"
            name="nombre"
            placeholder="Ejemplo: Bebidas"
            required
        >

        <label>Descripción</label>

        <textarea
            name="descripcion"
            placeholder="Descripción de la categoría"
            rows="3"
        ></textarea>

        <button type="submit" name="registrar">
            Registrar categoría
        </button>

    </form>

</div>

<div class="panel" style="margin-top: 25px;">

    <h2>Categorías registradas</h2>

    <p>Consulta y modifica las categorías existentes.</p>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($categoria = $resultado->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $categoria["id_categoria"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($categoria["nombre"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($categoria["descripcion"] ?? ""); ?>
                        </td>

                        <td>
                            <?php if ($categoria["activo"]): ?>
                                <span>Activa</span>
                            <?php else: ?>
                                <span>Inactiva</span>
                            <?php endif; ?>
                        </td>

                        <td>

                            <a
                                href="editar.php?id=<?php echo $categoria["id_categoria"]; ?>"
                                class="btn"
                            >
                                Editar
                            </a>

                            <form
                                method="POST"
                                action="cambiar_estado.php"
                                style="display: inline;"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo $categoria["id_categoria"]; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="estado"
                                    value="<?php echo $categoria["activo"] ? 0 : 1; ?>"
                                >

                                <button type="submit">
                                    <?php echo $categoria["activo"] ? "Desactivar" : "Activar"; ?>
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include "../includes/pie.php"; ?>
