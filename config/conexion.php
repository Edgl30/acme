
<?php

$esProduccion = !in_array(
    $_SERVER['HTTP_HOST'] ?? '',
    ['localhost', '127.0.0.1', 'localhost:80'],
    true
);

if ($esProduccion) {
    // DATOS DE INFINITYFREE: reemplaza los cuatro valores.
    $host = 'sql103.infinityfree.com';
    $baseDatos = 'if0_43248754_acme';
    $usuario = 'if0_43248754';
    $contrasena = 'H0mBaff3AweGN';
} else {
    // DATOS LOCALES DE XAMPP
    $host = 'localhost';
    $baseDatos = 'acme';
    $usuario = 'root';
    $contrasena = '';
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$baseDatos;charset=utf8mb4",
        $usuario,
        $contrasena,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    exit('No fue posible conectar con la base de datos. Verifica la configuración.');
}
