
<?php
require_once __DIR__ . '/config/conexion.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$conductores = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos,
            direccion, telefono, ciudad
     FROM conductores
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$mensajes = [
    'guardado' => 'Conductor registrado correctamente.',
    'datos' => 'Completa todos los campos obligatorios.',
    'duplicado' => 'La cédula ya está registrada.',
    'bd' => 'No fue posible guardar el conductor. Revisa los registros de PHP.'
];

$codigo = $_GET['estado'] ?? '';
$mensaje = $mensajes[$codigo] ?? null;
$esError = in_array($codigo, ['datos', 'duplicado', 'bd'], true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conductores | Transportes ACME</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main>
    <h1>Registro de conductores</h1>
    <p>Transportes ACME S.A.</p>
    <p><a href="index.php">Volver al menú principal</a></p>

    <?php if ($mensaje !== null): ?>
        <p role="<?= $esError ? 'alert' : 'status' ?>">
            <?= e($mensaje) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="guardar_conductor.php">
        <label for="cedula">Número de cédula *</label>
        <input id="cedula" name="cedula" maxlength="20" required>

        <label for="primer_nombre">Primer nombre *</label>
        <input id="primer_nombre" name="primer_nombre" maxlength="60" required>

        <label for="segundo_nombre">Segundo nombre</label>
        <input id="segundo_nombre" name="segundo_nombre" maxlength="60">

        <label for="apellidos">Apellidos *</label>
        <input id="apellidos" name="apellidos" maxlength="120" required>

        <label for="direccion">Dirección *</label>
        <input id="direccion" name="direccion" maxlength="180" required>

        <label for="telefono">Teléfono *</label>
        <input id="telefono" name="telefono" maxlength="25" required>

        <label for="ciudad">Ciudad *</label>
        <input id="ciudad" name="ciudad" maxlength="80" required>

        <button type="submit">Guardar conductor</button>
    </form>

    <h2>Conductores registrados</h2>

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
                <?php foreach ($conductores as $c): ?>
                    <tr>
                        <td><?= e($c['cedula']) ?></td>
                        <td><?= e(trim(
                            $c['primer_nombre'] . ' ' .
                            ($c['segundo_nombre'] ?? '') . ' ' .
                            $c['apellidos']
                        )) ?></td>
                        <td><?= e($c['direccion']) ?></td>
                        <td><?= e($c['telefono']) ?></td>
                        <td><?= e($c['ciudad']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$conductores): ?>
                    <tr>
                        <td colspan="5">Todavía no hay conductores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
