
<?php
require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: vehiculos.php');
    exit;
}

$datos = [
    'placa' => strtoupper(trim($_POST['placa'] ?? '')),
    'color' => trim($_POST['color'] ?? ''),
    'marca' => trim($_POST['marca'] ?? ''),
    'tipo' => trim($_POST['tipo'] ?? ''),
    'conductor_cedula' => trim($_POST['conductor_cedula'] ?? ''),
    'propietario_cedula' => trim($_POST['propietario_cedula'] ?? '')
];

foreach ($datos as $campo => $valor) {
    if ($valor === '') {
        header('Location: vehiculos.php?estado=datos');
        exit;
    }
}

if (!in_array($datos['tipo'], ['particular', 'publico'], true)) {
    header('Location: vehiculos.php?estado=datos');
    exit;
}

try {
    $sql = "INSERT INTO vehiculos (
        placa, color, marca, tipo,
        conductor_cedula, propietario_cedula
    ) VALUES (
        :placa, :color, :marca, :tipo,
        :conductor_cedula, :propietario_cedula
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
