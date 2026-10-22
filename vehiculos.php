
<?php
require_once __DIR__ . '/config/conexion.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$conductoresLista = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos
     FROM conductores
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$propietariosLista = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos
     FROM propietarios
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$vehiculos = $pdo->query(
    "SELECT v.placa, v.color, v.marca, v.tipo,
            c.primer_nombre AS conductor_nombre,
            c.segundo_nombre AS conductor_segundo_nombre,
            c.apellidos AS conductor_apellidos,
            p.primer_nombre AS propietario_nombre,
            p.segundo_nombre AS propietario_segundo_nombre,
            p.apellidos AS propietario_apellidos
     FROM vehiculos v
     INNER JOIN conductores c ON v.conductor_cedula = c.cedula
     INNER JOIN propietarios p ON v.propietario_cedula = p.cedula
     ORDER BY v.placa"
)->fetchAll();

$mensajes = [
    'guardado' => 'Vehículo registrado correctamente.',
    'datos' => 'Verifica los datos ingresados y selecciona un conductor y propietario válidos.',
    'duplicado' => 'La placa ya está registrada o existe una referencia inválida.',
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

    <form method="POST" action="guardar_vehiculo.php">
        <label for="placa">Placa *</label>
        <input
            type="text"
            id="placa"
            name="placa"
            maxlength="6"
            minlength="6"
            pattern="([A-Za-z]{3}[0-9]{3}|[A-Za-z]{3}[0-9]{2}[A-Za-z])"
            title="Formatos permitidos: ABC123 o ABC12D."
            placeholder="Ej. ABC123"
            style="text-transform: uppercase;"
            oninput="this.value = this.value.toUpperCase()"
            required
        >


        <label for="color">Color *</label>
        <select id="color" name="color" required>
            <option value="">Seleccione un color</option>
            <option value="Blanco">Blanco</option>
            <option value="Negro">Negro</option>
            <option value="Gris">Gris</option>
            <option value="Plateado">Plateado</option>
            <option value="Rojo">Rojo</option>
            <option value="Azul">Azul</option>
            <option value="Verde">Verde</option>
            <option value="Amarillo">Amarillo</option>
            <option value="Naranja">Naranja</option>
            <option value="Café">Café</option>
            <option value="Beige">Beige</option>
            <option value="Morado">Morado</option>
            <option value="Rosado">Rosado</option>
        </select>

        <label for="marca">Marca *</label>
        <input
            type="text"
            id="marca"
            name="marca"
            maxlength="60"
            pattern="[A-Za-z0-9ÁÉÍÓÚáéíóúÜüÑñ .-]+"
            title="Ingrese una marca válida."
            required
        >

        <label for="tipo">Tipo de vehículo *</label>
        <select id="tipo" name="tipo" required>
            <option value="">Seleccione un tipo</option>
            <option value="particular">Particular</option>
            <option value="publico">Público</option>
        </select>

        <label for="conductor_cedula">Conductor *</label>
        <select id="conductor_cedula" name="conductor_cedula" required>
            <option value="">Seleccione un conductor</option>
            <?php foreach ($conductoresLista as $c): ?>
                <option value="<?= e($c['cedula']) ?>">
                    <?= e(
                        $c['cedula'] . ' - ' .
                        trim(
                            $c['primer_nombre'] . ' ' .
                            ($c['segundo_nombre'] ?? '') . ' ' .
                            $c['apellidos']
                        )
                    ) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="propietario_cedula">Propietario *</label>
        <select id="propietario_cedula" name="propietario_cedula" required>
            <option value="">Seleccione un propietario</option>
            <?php foreach ($propietariosLista as $p): ?>
                <option value="<?= e($p['cedula']) ?>">
                    <?= e(
                        $p['cedula'] . ' - ' .
                        trim(
                            $p['primer_nombre'] . ' ' .
                            ($p['segundo_nombre'] ?? '') . ' ' .
                            $p['apellidos']
                        )
                    ) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Guardar vehículo</button>
    </form>

    <h2>Vehículos registrados</h2>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Color</th>
                    <th>Marca</th>
                    <th>Tipo</th>
                    <th>Conductor</th>
                    <th>Propietario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehiculos as $v): ?>
                    <tr>
                        <td><?= e($v['placa']) ?></td>
                        <td><?= e($v['color']) ?></td>
                        <td><?= e($v['marca']) ?></td>
                        <td><?= e($v['tipo'] === 'publico' ? 'Público' : 'Particular') ?></td>
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
