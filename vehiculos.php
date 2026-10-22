
<?php
require_once __DIR__ . '/config/conexion.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$conductores = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos
     FROM conductores
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$propietarios = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos
     FROM propietarios
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$sql = "SELECT
            v.placa, v.color, v.marca, v.tipo,
            c.primer_nombre AS conductor_nombre,
            c.segundo_nombre AS conductor_segundo_nombre,
            c.apellidos AS conductor_apellidos,
            p.primer_nombre AS propietario_nombre,
            p.segundo_nombre AS propietario_segundo_nombre,
            p.apellidos AS propietario_apellidos
        FROM vehiculos v
        INNER JOIN conductores c
            ON v.conductor_cedula = c.cedula
        INNER JOIN propietarios p
            ON v.propietario_cedula = p.cedula
        ORDER BY v.placa";

$vehiculos = $pdo->query($sql)->fetchAll();

$mensajes = [
    'guardado' => 'Vehículo registrado correctamente.',
    'datos' => 'Completa todos los campos obligatorios.',
    'duplicado' => 'La placa ya existe o el conductor o propietario no es válido.',
    'bd' => 'No fue posible guardar el vehículo. Revisa los registros de PHP.'
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
    <title>Vehículos | Transportes ACME</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main>
    <h1>Registro de vehículos</h1>
    <p>Transportes ACME S.A.</p>
    <p><a href="index.php">Volver al menú principal</a></p>

    <?php if ($mensaje !== null): ?>
        <p role="<?= $esError ? 'alert' : 'status' ?>">
            <?= e($mensaje) ?>
        </p>
    <?php endif; ?>

    <?php if (!$conductores || !$propietarios): ?>
        <p>
            Primero registra al menos un conductor y un propietario
            antes de registrar vehículos.
        </p>
    <?php else: ?>
        <form method="POST" action="guardar_vehiculo.php">
            <label for="placa">Placa *</label>
            <input id="placa" name="placa" maxlength="10" required>

            <label for="color">Color *</label>
            <input id="color" name="color" maxlength="40" required>

            <label for="marca">Marca *</label>
            <input id="marca" name="marca" maxlength="60" required>

            <label for="tipo">Tipo de vehículo *</label>
            <select id="tipo" name="tipo" required>
                <option value="particular">Particular</option>
                <option value="publico">Público</option>
            </select>

            <label for="conductor_cedula">Conductor *</label>
            <select id="conductor_cedula" name="conductor_cedula" required>
                <option value="">Selecciona un conductor</option>
                <?php foreach ($conductores as $c): ?>
                    <?php
                    $nombre = trim(
                        $c['primer_nombre'] . ' ' .
                        ($c['segundo_nombre'] ?? '') . ' ' .
                        $c['apellidos']
                    );
                    ?>
                    <option value="<?= e($c['cedula']) ?>">
                        <?= e($nombre . ' — ' . $c['cedula']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="propietario_cedula">Propietario *</label>
            <select id="propietario_cedula" name="propietario_cedula" required>
                <option value="">Selecciona un propietario</option>
                <?php foreach ($propietarios as $p): ?>
                    <?php
                    $nombre = trim(
                        $p['primer_nombre'] . ' ' .
                        ($p['segundo_nombre'] ?? '') . ' ' .
                        $p['apellidos']
                    );
                    ?>
                    <option value="<?= e($p['cedula']) ?>">
                        <?= e($nombre . ' — ' . $p['cedula']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Guardar vehículo</button>
        </form>
    <?php endif; ?>

    <h2>Vehículos registrados</h2>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>Conductor</th>
                    <th>Propietario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehiculos as $v): ?>
                    <tr>
                        <td><?= e($v['placa']) ?></td>
                        <td><?= e($v['marca']) ?></td>
                        <td><?= e($v['color']) ?></td>
                        <td><?= e($v['tipo']) ?></td>
                        <td><?= e(trim(
                            $v['conductor_nombre'] . ' ' .
                            ($v['conductor_segundo_nombre'] ?? '') . ' ' .
                            $v['conductor_apellidos']
                        )) ?></td>
                        <td><?= e(trim(
                            $v['propietario_nombre'] . ' ' .
                            ($v['propietario_segundo_nombre'] ?? '') . ' ' .
                            $v['propietario_apellidos']
                        )) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$vehiculos): ?>
                    <tr>
                        <td colspan="6">Todavía no hay vehículos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
