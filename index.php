<?php
$tituloPagina = 'Inicio | Transportes ACME S.A.';
require_once __DIR__ . '/includes/header.php';
?>
<head>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<main class="inicio-acme">

    <section class="bienvenida-acme">
        <div class="bienvenida-texto">
            <span class="etiqueta-acme">SISTEMA DE GESTIÓN</span>

            <h1>Bienvenido a Transportes ACME S.A.</h1>

            <p>
                Administra de forma sencilla y organizada la información
                de conductores, propietarios y vehículos de la empresa.
            </p>

            <a href="vehiculos.php" class="boton-acme">
                Consultar vehículos <span>→</span>
            </a>
        </div>

        <div class="bienvenida-icono" aria-hidden="true">
            <div class="circulo-icono">
                <img src="img/coyote.png" alt="Icono del zorro">
            </div>
            <span>Gestión de transporte</span>
            <small>Información organizada en un solo lugar</small>
        </div>
    </section>

    <section class="modulos-acme">
        <div class="titulo-seccion">
            <div>
                <span class="subtitulo-acme">PANEL PRINCIPAL</span>
                <h2>¿Qué deseas administrar?</h2>
                <p>Selecciona uno de los módulos para continuar.</p>
            </div>
        </div>

        <div class="tarjetas-acme">

            <a href="conductores.php" class="tarjeta-acme">
                <div class="icono-tarjeta icono-azul">🐦</div>
                <h3>Conductores</h3>
                <p>Registra y consulta la información personal de los conductores.</p>
                <span class="enlace-tarjeta">Ingresar al módulo <b>→</b></span>
            </a>

            <a href="propietarios.php" class="tarjeta-acme">
                <div class="icono-tarjeta icono-rojo">📋</div>
                <h3>Propietarios</h3>
                <p>Administra los datos de los propietarios vinculados a los vehículos.</p>
                <span class="enlace-tarjeta">Ingresar al módulo <b>→</b></span>
            </a>

            <a href="vehiculos.php" class="tarjeta-acme">
                <div class="icono-tarjeta icono-verde">🚘</div>
                <h3>Vehículos</h3>
                <p>Registra vehículos y relaciona sus conductores y propietarios.</p>
                <span class="enlace-tarjeta">Ingresar al módulo <b>→</b></span>
            </a>

            <a href="informe.php" class="tarjeta-acme">
                <div class="icono-tarjeta icono-gris">📊</div>
                <h3>Informes</h3>
                <p>Consulta la información consolidada de los vehículos registrados.</p>
                <span class="enlace-tarjeta">Ver informe <b>→</b></span>
            </a>

        </div>
    </section>

    <section class="nota-acme">
        <div class="nota-icono">✓</div>
        <div>
            <strong>Todo organizado en un solo sistema</strong>
            <p>Accede a los módulos desde el menú superior o desde este panel principal.</p>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>