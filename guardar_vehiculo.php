
<?php
require_once __DIR__ . '/config/conexion.php';

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vehiculos.php');
    exit;
}

// Recibir y normalizar los datos
$datos = [
    'placa' => strtoupper(trim($_POST['placa'] ?? '')),
    'color' => trim($_POST['color'] ?? ''),
    'marca' => trim($_POST['marca'] ?? ''),
    'tipo' => trim($_POST['tipo'] ?? ''),
    'conductor_cedula' => trim($_POST['conductor_cedula'] ?? ''),
    'propietario_cedula' => trim($_POST['propietario_cedula'] ?? '')
];

// Validar que todos los campos estén completos
foreach ($datos as $valor) {
    if ($valor === '') {
        header('Location: vehiculos.php?estado=datos');
        exit;
    }
}

// Validar placa: ABC123 o ABC12D
if (
    !preg_match(
        '/\A(?:[A-Z]{3}[0-9]{3}|[A-Z]{3}[0-9]{2}[A-Z])\z/',
        $datos['placa']
    )
) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar color: únicamente opciones permitidas
$coloresPermitidos = [
    'Blanco',
    'Negro',
    'Gris',
    'Plateado',
    'Rojo',
    'Azul',
    'Verde',
    'Amarillo',
    'Naranja',
    'Café',
    'Beige',
    'Morado',
    'Rosado'
];

if (!in_array($datos['color'], $coloresPermitidos, true)) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar marca: letras, números, espacios, puntos y guiones
if (
    !preg_match('/\A[\p{L}0-9 .-]+\z/u', $datos['marca']) ||
    mb_strlen($datos['marca']) > 60
) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Validar tipo de vehículo
if (!in_array($datos['tipo'], ['particular', 'publico'], true)) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Verificar que el conductor exista
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM conductores WHERE cedula = :cedula'
);
$stmt->execute([
    'cedula' => $datos['conductor_cedula']
]);

if ((int) $stmt->fetchColumn() === 0) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Verificar que el propietario exista
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM propietarios WHERE cedula = :cedula'
);
$stmt->execute([
    'cedula' => $datos['propietario_cedula']
]);

if ((int) $stmt->fetchColumn() === 0) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

// Insertar el vehículo en la base de datos
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
