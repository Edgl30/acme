
<?php
require_once __DIR__ . '/config/conexion.php';

$errores = [];
$datos = [
    'cedula' => '',
    'primer_nombre' => '',
    'segundo_nombre' => '',
    'apellidos' => '',
    'direccion' => '',
    'telefono' => '',
    'ciudad' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => $valor) {
        $datos[$campo] = trim($_POST[$campo] ?? '');
    }

    foreach ([
        'cedula' => 'La cédula',
        'primer_nombre' => 'El primer nombre',
        'apellidos' => 'Los apellidos',
        'direccion' => 'La dirección',
        'telefono' => 'El teléfono',
        'ciudad' => 'La ciudad'
    ] as $campo => $nombre) {
        if ($datos[$campo] === '') {
            $errores[] = "$nombre es obligatorio.";
        }
    }

    if ($datos['segundo_nombre'] === '') {
        $datos['segundo_nombre'] = null;
    }

    if (!$errores) {
        try {
            $sql = "INSERT INTO propietarios
                (cedula, primer_nombre, segundo_nombre, apellidos,
                 direccion, telefono, ciudad)
                VALUES
                (:cedula, :primer_nombre, :segundo_nombre, :apellidos,
                 :direccion, :telefono, :ciudad)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);

            header('Location: propietarios.php?guardado=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errores[] = 'La cédula ya está registrada.';
            } else {
                error_log($e->getMessage());
                $errores[] = 'No fue posible guardar el propietario.';
            }
        }
    }
}

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$propietarios = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos,
            direccion, telefono, ciudad
     FROM propietarios
     ORDER BY primer_nombre, apellidos"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propietarios | Transportes ACME</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main>
    <h1>Registro de propietarios</h1>
    <p>Transportes ACME S.A.</p>

    <p><a href="index.php">Volver al menú principal</a></p>

    <?php if (isset($_GET['guardado'])): ?>
        <p role="status">Propietario registrado correctamente.</p>
    <?php endif; ?>

    <?php if ($errores): ?>
        <div role="alert">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="propietarios.php">
        <label for="cedula">Número de cédula *</label>
        <input id="cedula" name="cedula" maxlength="20"
               value="<?= e($datos['cedula']) ?>" required>

        <label for="primer_nombre">Primer nombre *</label>
        <input id="primer_nombre" name="primer_nombre" maxlength="60"
               value="<?= e($datos['primer_nombre']) ?>" required>

        <label for="segundo_nombre">Segundo nombre</label>
        <input id="segundo_nombre" name="segundo_nombre" maxlength="60"
               value="<?= e($datos['segundo_nombre']) ?>">

        <label for="apellidos">Apellidos *</label>
        <input id="apellidos" name="apellidos" maxlength="120"
               value="<?= e($datos['apellidos']) ?>" required>

        <label for="direccion">Dirección *</label>
        <input id="direccion" name="direccion" maxlength="180"
               value="<?= e($datos['direccion']) ?>" required>

        <label for="telefono">Teléfono *</label>
        <input id="telefono" name="telefono" maxlength="25"
               value="<?= e($datos['telefono']) ?>" required>

        <label for="ciudad">Ciudad *</label>
        <input id="ciudad" name="ciudad" maxlength="80"
               value="<?= e($datos['ciudad']) ?>" required>

        <button type="submit">Guardar propietario</button>
    </form>

    <h2>Propietarios registrados</h2>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre completo</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propietarios as $p): ?>
                    <tr>
                        <td><?= e($p['cedula']) ?></td>
                        <td><?= e(trim(
                            $p['primer_nombre'] . ' ' .
                            ($p['segundo_nombre'] ?? '') . ' ' .
                            $p['apellidos']
                        )) ?></td>
                        <td><?= e($p['direccion']) ?></td>
                        <td><?= e($p['telefono']) ?></td>
                        <td><?= e($p['ciudad']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$propietarios): ?>
                    <tr>
                        <td colspan="5">
                            Todavía no hay propietarios registrados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
