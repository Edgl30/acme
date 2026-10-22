<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php
require_once __DIR__ . '/config/conexion.php';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$propietarios = $pdo->query(
    "SELECT cedula, primer_nombre, segundo_nombre, apellidos,
            direccion, telefono, ciudad
     FROM propietarios
     ORDER BY primer_nombre, apellidos"
)->fetchAll();

$mensajes = [
    'guardado' => 'Propietario registrado correctamente.',
    'datos' => 'Verifica los datos: documento numérico, nombres y ciudad con letras, y teléfono de 10 dígitos.',
    'duplicado' => 'La cédula ya está registrada.',
    'bd' => 'No fue posible guardar el propietario. Revisa los registros de PHP.'
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
    <title>Propietarios | Transportes ACME</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main>
    <h1>Registro de propietarios</h1>
    <p>Transportes ACME S.A.</p>
    <p><a href="index.php">Volver al menú principal</a></p>

    <?php if ($mensaje !== null): ?>
        <p role="<?= $esError ? 'alert' : 'status' ?>">
            <?= e($mensaje) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="guardar_propietario.php">
        <label for="cedula">Número de cédula *</label>
        <input
            type="text"
            id="cedula"
            name="cedula"
            maxlength="20"
            pattern="[0-9]+"
            inputmode="numeric"
            title="Ingrese únicamente números."
            required
        >

        <label for="primer_nombre">Primer nombre *</label>
        <input
            type="text"
            id="primer_nombre"
            name="primer_nombre"
            maxlength="60"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
            title="Ingrese únicamente letras y espacios."
            required
        >

        <label for="segundo_nombre">Segundo nombre</label>
        <input
            type="text"
            id="segundo_nombre"
            name="segundo_nombre"
            maxlength="60"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]*"
            title="Ingrese únicamente letras y espacios."
        >

        <label for="apellidos">Apellidos *</label>
        <input
            type="text"
            id="apellidos"
            name="apellidos"
            maxlength="120"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
            title="Ingrese únicamente letras y espacios."
            required
        >

        <label for="direccion">Dirección *</label>
        <input
            type="text"
            id="direccion"
            name="direccion"
            maxlength="180"
            required
        >

        <label for="telefono">Teléfono *</label>
        <input
            type="tel"
            id="telefono"
            name="telefono"
            maxlength="10"
            minlength="10"
            pattern="[0-9]{10}"
            inputmode="numeric"
            title="Ingrese exactamente 10 dígitos numéricos."
            placeholder="Ej. 3001234567"
            required
        >

        <label for="ciudad">Ciudad *</label>
        <input
            type="text"
            id="ciudad"
            name="ciudad"
            maxlength="80"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
            title="Ingrese únicamente letras y espacios."
            required
        >

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
                        <td colspan="5">Todavía no hay propietarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
