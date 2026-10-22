
<?php
require_once __DIR__ . '/config/conexion.php';

$sql = "SELECT
            v.placa,
            v.marca,
            CONCAT_WS(' ',
                c.primer_nombre,
                NULLIF(c.segundo_nombre, ''),
                c.apellidos
            ) AS conductor,
            CONCAT_WS(' ',
                p.primer_nombre,
                NULLIF(p.segundo_nombre, ''),
                p.apellidos
            ) AS propietario
        FROM vehiculos v
        INNER JOIN conductores c
            ON v.conductor_cedula = c.cedula
        INNER JOIN propietarios p
            ON v.propietario_cedula = p.cedula
        ORDER BY v.placa";

$stmt = $pdo->query($sql);
$informe = $stmt->fetchAll();

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de vehículos | Transportes ACME</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main>
    <h1>Informe de vehículos</h1>
    <p>Transportes ACME S.A.</p>

    <p><a href="index.php">Volver al menú principal</a></p>

    <p>
        Total de vehículos registrados:
        <strong><?= count($informe) ?></strong>
    </p>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Nombre del conductor</th>
                    <th>Nombre del propietario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($informe as $fila): ?>
                    <tr>
                        <td><?= e($fila['placa']) ?></td>
                        <td><?= e($fila['marca']) ?></td>
                        <td><?= e($fila['conductor']) ?></td>
                        <td><?= e($fila['propietario']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$informe): ?>
                    <tr>
                        <td colspan="4">
                            No hay vehículos registrados para mostrar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p>
        <button type="button" onclick="window.print()">
            Imprimir informe
        </button>
    </p>
</main>
</body>
</html>
