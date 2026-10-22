
<?php
$tituloPagina = $tituloPagina ?? 'Transportes ACME';
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<header class="encabezado">
    <div class="contenedor-header">
        <a class="logo-acme" href="index.php">
            <span class="logo-icono">ACME</span>
            <span class="logo-texto">
                <strong>Transportes ACME S.A.</strong>
                <small>Gestión de transporte</small>
            </span>
        </a>

        <nav class="navegacion-principal" aria-label="Menú principal">
            <a href="index.php"
               <?= $paginaActual === 'index.php' ? 'aria-current="page"' : '' ?>>
                Inicio
            </a>

            <a href="conductores.php"
               <?= $paginaActual === 'conductores.php' ? 'aria-current="page"' : '' ?>>
                Conductores
            </a>

            <a href="propietarios.php"
               <?= $paginaActual === 'propietarios.php' ? 'aria-current="page"' : '' ?>>
                Propietarios
            </a>

            <a href="vehiculos.php"
               <?= $paginaActual === 'vehiculos.php' ? 'aria-current="page"' : '' ?>>
                Vehículos
            </a>

            <a href="informe.php"
               <?= $paginaActual === 'informe.php' ? 'aria-current="page"' : '' ?>>
                Informe
            </a>
        </nav>
    </div>
</header>

<div class="contenido-principal">
