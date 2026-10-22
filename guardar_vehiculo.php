
<?php
require_once __DIR__ . '/config/conexion.php';

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vehiculos.php');
    exit;
}

// Recibir y normalizar datos
$datos = [
    'placa' => strtoupper(trim($_POST['placa'] ?? '')),
    'color' => trim($_POST['color'] ?? ''),
    'marca' => trim($_POST['marca'] ?? ''),
    'tipo' => trim($_POST['tipo'] ?? ''),
    'conductor_cedula' => trim($_POST['conductor_cedula'] ?? ''),
    'propietario_cedula' => trim($_POST['propietario_cedula'] ?? '')
];

// Validar campos obligatorios
foreach ($datos as $valor) {
    if ($valor === '') {
        header('Location: vehiculos.php?estado=datos');
        exit;
    }
}

// Validar placa: letras, números y guiones
if (
    !preg_match('/\A[A-Z0-9-]+\z/', $datos['placa']) ||
    strlen($datos['placa']) > 10
) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar color: letras y espacios
if (!preg_match('/\A[\p{L} ]+\z/u', $datos['color'])) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar marca: letras, números, espacios, puntos y guiones
if (!preg_match('/\A[\p{L}0-9 .-]+\z/u', $datos['marca'])) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar longitudes según la base de datos
if (
    mb_strlen($datos['color']) > 40 ||
    mb_strlen($datos['marca']) > 60
) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar el tipo de vehículo
if (!in_array($datos['tipo'], ['particular', 'publico'], true)) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Verificar que el conductor exista
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM conductores WHERE cedula = :cedula'
);
$stmt->execute(['cedula' => $datos['conductor_cedula']]);

if ((int) $stmt->fetchColumn() === 0) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Verificar que el propietario exista
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM propietarios WHERE cedula = :cedula'
);
$stmt->execute(['cedula' => $datos['propietario_cedula']]);

if ((int) $stmt->fetchColumn() === 0) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Insertar vehículo
try {
    $sql = "INSERT INTO vehiculos (
        placa,
        color,
        marca,
        tipo,
        conductor_cedula,
        propietario_cedula
    ) VALUES (
        :placa,
        :color,
        :marca,
        :tipo,
        :conductor_cedula,
        :propietario_cedula
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($datos);

    header('Location: vehiculos.php?estado=guardado');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());

    if ($e->getCode() === '23000') {
        header('Location: vehiculos.php?estado=duplicado');
    } else {
        header('Location: vehiculos.php?estado=bd');
    }

    exit;
}
