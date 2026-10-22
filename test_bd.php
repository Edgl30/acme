
<?php

require_once __DIR__ . '/config/conexion.php';

try {
    $consulta = $pdo->query('SELECT DATABASE() AS base_datos');
    $resultado = $consulta->fetch();

    echo '<h1>Conexión exitosa</h1>';
    echo '<p>Base de datos conectada: '
        . htmlspecialchars($resultado['base_datos'], ENT_QUOTES, 'UTF-8')
        . '</p>';

    $tablas = $pdo->query('SHOW TABLES')->fetchAll();

    echo '<h2>Tablas disponibles</h2><ul>';

    foreach ($tablas as $tabla) {
        echo '<li>' . htmlspecialchars(
            array_values($tabla)[0],
            ENT_QUOTES,
            'UTF-8'
        ) . '</li>';
    }

    echo '</ul>';

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'No se pudo completar la prueba.';
}
