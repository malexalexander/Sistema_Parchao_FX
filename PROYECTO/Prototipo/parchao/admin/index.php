
<?php
require_once "../config/conexion.php";

function contar($conexion, $sql) {
    $resultado = $conexion->query($sql);
    if ($resultado) {
        return $resultado->fetch_assoc()["total"];
    }
    return 0;
}

$totalCategorias = contar($conexion,
    "SELECT COUNT(*) AS total FROM categorias WHERE activo = 1");

$totalProductos = contar($conexion,
    "SELECT COUNT(*) AS total FROM productos WHERE activo = 1");

$totalPedidos = contar($conexion,
    "SELECT COUNT(*) AS total FROM pedidos WHERE DATE(fecha_creacion) = CURDATE()");

$pedidosPendientes = contar($conexion,
    "SELECT COUNT(*) AS total FROM pedidos WHERE estado_pedido = 'PENDIENTE'");

$tituloPagina = "Dashboard";
include "includes/encabezado.php";
?>

<div class="cards-dashboard">

    <div class="card">
        <p>Categorías activas</p>
        <h2><?php echo $totalCategorias; ?></h2>
    </div>

    <div class="card">
        <p>Productos activos</p>
        <h2><?php echo $totalProductos; ?></h2>
    </div>

    <div class="card">
        <p>Pedidos de hoy</p>
        <h2><?php echo $totalPedidos; ?></h2>
    </div>

    <div class="card">
        <p>Pedidos pendientes</p>
        <h2><?php echo $pedidosPendientes; ?></h2>
    </div>

</div>

<div class="panel" style="margin-top:25px;">
    <h2>Accesos rápidos</h2>

    <p style="margin-top:15px;">
        Administra las principales funciones de tu restaurante.
    </p>

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
        <a class="btn" href="categorias/">Categorías</a>
        <a class="btn" href="productos/">Productos</a>
        <a class="btn" href="inventario/">Inventario</a>
        <a class="btn" href="pedidos/">Pedidos</a>
    </div>
</div>

<?php include "includes/pie.php"; ?>
