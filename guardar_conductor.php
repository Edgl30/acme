
<?php
require_once __DIR__ . '/config/conexion.php';

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: conductores.php');
    exit;
}

$datos = [
    'cedula' => trim($_POST['cedula'] ?? ''),
    'primer_nombre' => trim($_POST['primer_nombre'] ?? ''),
    'segundo_nombre' => trim($_POST['segundo_nombre'] ?? ''),
    'apellidos' => trim($_POST['apellidos'] ?? ''),
    'direccion' => trim($_POST['direccion'] ?? ''),
    'telefono' => trim($_POST['telefono'] ?? ''),
    'ciudad' => trim($_POST['ciudad'] ?? '')
];

if (
    $datos['cedula'] === '' ||
    $datos['primer_nombre'] === '' ||
    $datos['apellidos'] === '' ||
    $datos['direccion'] === '' ||
    $datos['telefono'] === '' ||
    $datos['ciudad'] === ''
) {
    header('Location: conductores.php?estado=datos');
    exit;
}

$soloNumeros = '/\A[0-9]+\z/';
$soloLetras = '/\A[\p{L} ]+\z/u';

if (
    !preg_match($soloNumeros, $datos['cedula']) ||
    strlen($datos['cedula']) > 20
) {
    header('Location: conductores.php?estado=datos');
    exit;
}

if (
    !preg_match($soloLetras, $datos['primer_nombre']) ||
    !preg_match($soloLetras, $datos['apellidos']) ||
    !preg_match($soloLetras, $datos['ciudad'])
) {
    header('Location: conductores.php?estado=datos');
    exit;
}

if (
    $datos['segundo_nombre'] !== '' &&
    !preg_match($soloLetras, $datos['segundo_nombre'])
) {
    header('Location: conductores.php?estado=datos');
    exit;
}

if (!preg_match('/\A[0-9]{10}\z/', $datos['telefono'])) {
    header('Location: conductores.php?estado=datos');
    exit;
}

if (
    mb_strlen($datos['primer_nombre']) > 60 ||
    mb_strlen($datos['segundo_nombre']) > 60 ||
    mb_strlen($datos['apellidos']) > 120 ||
    mb_strlen($datos['direccion']) > 180 ||
    mb_strlen($datos['ciudad']) > 80 ||
    strlen($datos['telefono']) > 25
) {
    header('Location: conductores.php?estado=datos');
    exit;
}

try {
    $sql = "INSERT INTO conductores (
        cedula,
        primer_nombre,
        segundo_nombre,
        apellidos,
        direccion,
        telefono,
        ciudad
    ) VALUES (
        :cedula,
        :primer_nombre,
        :segundo_nombre,
        :apellidos,
        :direccion,
        :telefono,
        :ciudad
    )";

    $stmt = $pdo->prepare($sql);

    $datos['segundo_nombre'] =
        $datos['segundo_nombre'] === ''
            ? null
            : $datos['segundo_nombre'];

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