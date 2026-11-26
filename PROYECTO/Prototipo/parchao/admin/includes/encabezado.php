
<?php
$tituloPagina = $tituloPagina ?? "Panel administrativo";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina); ?> | PARCHAO</title>
    <link rel="stylesheet" href="/parchao/css/admin.css">
</head>
<body>

<div class="admin-layout">

    <aside class="sidebar">
        <div class="logo">PARCHAO<span>.</span></div>
        <div class="sidebar-subtitle">GESTIÓN DEL RESTAURANTE</div>

        <nav>
            <a href="/parchao/admin/" class="nav-link">⌂ Dashboard</a>

            <div class="nav-title">CATÁLOGO</div>
            <a href="/parchao/admin/categorias/" class="nav-link">▦ Categorías</a>
            <a href="/parchao/admin/productos/" class="nav-link">▣ Productos</a>
            <a href="/parchao/admin/inventario/" class="nav-link">▤ Inventario</a>

            <div class="nav-title">OPERACIONES</div>
            <a href="/parchao/admin/pedidos/" class="nav-link">▧ Pedidos</a>
            <a href="/parchao/admin/cocina/" class="nav-link">♨ Cocina</a>
            <a href="/parchao/admin/caja/" class="nav-link">▣ Caja y pagos</a>
            <a href="/parchao/admin/entregas/" class="nav-link">➤ Entregas</a>

            <div class="nav-title">ADMINISTRACIÓN</div>
            <a href="/parchao/admin/usuarios/" class="nav-link">♙ Usuarios</a>
            <a href="/parchao/admin/reportes/" class="nav-link">▥ Reportes</a>
        </nav>

        <div class="sidebar-bottom">
            <span>Sistema de restaurante</span>
            <strong>Versión de desarrollo</strong>
        </div>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <span class="breadcrumb">PARCHAO / ADMINISTRACIÓN</span>
                <h1><?php echo htmlspecialchars($tituloPagina); ?></h1>
            </div>

            <div class="admin-user">
                <div class="user-avatar">A</div>
                <div>
                    <strong>Administrador</strong>
                    <small>Panel de control</small>
                </div>
            </div>
        </header>

        <section class="page-content">
