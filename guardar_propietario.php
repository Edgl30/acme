
<?php
require_once __DIR__ . '/config/conexion.php';

// Aceptar únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: propietarios.php');
    exit;
}

// Recibir y limpiar los datos
$datos = [
    'cedula' => trim($_POST['cedula'] ?? ''),
    'primer_nombre' => trim($_POST['primer_nombre'] ?? ''),
    'segundo_nombre' => trim($_POST['segundo_nombre'] ?? ''),
    'apellidos' => trim($_POST['apellidos'] ?? ''),
    'direccion' => trim($_POST['direccion'] ?? ''),
    'telefono' => trim($_POST['telefono'] ?? ''),
    'ciudad' => trim($_POST['ciudad'] ?? '')
];

// Validar campos obligatorios
if (
    $datos['cedula'] === '' ||
    $datos['primer_nombre'] === '' ||
    $datos['apellidos'] === '' ||
    $datos['direccion'] === '' ||
    $datos['telefono'] === '' ||
    $datos['ciudad'] === ''
) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Patrones de validación
$soloNumeros = '/\A[0-9]+\z/';
$soloLetras = '/\A[\p{L} ]+\z/u';

// Documento: solo números, máximo 20 caracteres
if (
    !preg_match($soloNumeros, $datos['cedula']) ||
    strlen($datos['cedula']) > 20
) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Nombres, apellidos y ciudad: solo letras y espacios
if (
    !preg_match($soloLetras, $datos['primer_nombre']) ||
    !preg_match($soloLetras, $datos['apellidos']) ||
    !preg_match($soloLetras, $datos['ciudad'])
) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Segundo nombre: opcional
if (
    $datos['segundo_nombre'] !== '' &&
    !preg_match($soloLetras, $datos['segundo_nombre'])
) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Teléfono: exactamente 10 dígitos
if (!preg_match('/\A[0-9]{10}\z/', $datos['telefono'])) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Validar longitudes máximas de la base de datos
if (
    mb_strlen($datos['primer_nombre']) > 60 ||
    mb_strlen($datos['segundo_nombre']) > 60 ||
    mb_strlen($datos['apellidos']) > 120 ||
    mb_strlen($datos['direccion']) > 180 ||
    mb_strlen($datos['ciudad']) > 80
) {
    header('Location: propietarios.php?estado=datos');
    exit;
}

// Guardar propietario
try {
    $sql = "INSERT INTO propietarios (
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

    // Segundo nombre opcional: guardar NULL si está vacío
    $datos['segundo_nombre'] =
        $datos['segundo_nombre'] === ''
            ? null
            : $datos['segundo_nombre'];

    $stmt->execute($datos);

    header('Location: propietarios.php?estado=guardado');
    exit;

} catch (PDOException $e) {
    error_log($e->getMessage());

    if ($e->getCode() === '23000') {
        header('Location: propietarios.php?estado=duplicado');
    } else {
        header('Location: propietarios.php?estado=bd');
    }

    exit;
}
