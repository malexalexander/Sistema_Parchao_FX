
<?php

require_once "../../config/conexion.php";

// Obtener ID de la categoría
$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    die("Categoría no válida.");
}

// Consultar categoría
$consulta = $conexion->prepare(
    "SELECT * FROM categorias WHERE id_categoria = ?"
);

$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();
$categoria = $resultado->fetch_assoc();

$consulta->close();

if (!$categoria) {
    die("Categoría no encontrada.");
}

$mensaje = "";

// Actualizar categoría
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);

    if ($nombre != "") {

        $sql = "UPDATE categorias
                SET nombre = ?, descripcion = ?
                WHERE id_categoria = ?";

        $consulta = $conexion->prepare($sql);

        $consulta->bind_param(
            "ssi",
            $nombre,
            $descripcion,
            $id
        );

        if ($consulta->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Error al actualizar la categoría.";
        }

        $consulta->close();

    } else {

        $mensaje = "El nombre es obligatorio.";

    }
}

$tituloPagina = "Editar categoría";
include "../includes/encabezado.php";

?>

<div class="panel">

    <h2>Modificar categoría</h2>

    <p style="margin-top:10px;">
        Actualiza la información de la categoría seleccionada.
    </p>

    <?php if ($mensaje != ""): ?>

        <div style="
            margin-top:20px;
            padding:12px;
            background:#ffe8e8;
            border-radius:7px;
        ">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

    <form method="POST" style="margin-top:20px;">

        <label>Nombre de la categoría</label>

        <input
            type="text"
            name="nombre"
            value="<?php echo htmlspecialchars($categoria["nombre"]); ?>"
            required
        >

        <label>Descripción</label>

        <textarea
            name="descripcion"
            rows="4"
        ><?php echo htmlspecialchars($categoria["descripcion"] ?? ""); ?></textarea>

        <div style="
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:20px;
        ">

            <button type="submit">
                Guardar cambios
            </button>

            <a href="index.php" class="btn">
                Cancelar
            </a>

        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
