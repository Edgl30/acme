
<?php
require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: conductores.php');
    exit;
}

$campos = [
    'cedula',
    'primer_nombre',
    'segundo_nombre',
    'apellidos',
    'direccion',
    'telefono',
    'ciudad'
];

$datos = [];

foreach ($campos as $campo) {
    $datos[$campo] = trim($_POST[$campo] ?? '');
}

foreach ([
    'cedula',
    'primer_nombre',
    'apellidos',
    'direccion',
    'telefono',
    'ciudad'
] as $campo) {
    if ($datos[$campo] === '') {
        header('Location: conductores.php?estado=datos');
        exit;
    }
}

if ($datos['segundo_nombre'] === '') {
    $datos['segundo_nombre'] = null;
}

try {
    $sql = "INSERT INTO conductores (
        cedula, primer_nombre, segundo_nombre, apellidos,
        direccion, telefono, ciudad
    ) VALUES (
        :cedula, :primer_nombre, :segundo_nombre, :apellidos,
        :direccion, :telefono, :ciudad
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($datos);

    header('Location: conductores.php?estado=guardado');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());

    if ($e->getCode() === '23000') {
        header('Location: conductores.php?estado=duplicado');
    } else {
        header('Location: conductores.php?estado=bd');
    }

    exit;
}
