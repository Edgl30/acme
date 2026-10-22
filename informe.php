<?php
require_once __DIR__ . '/config/conexion.php';


$placa = trim($_GET['placa'] ?? '');
$marca = trim($_GET['marca'] ?? '');
$tipo = trim($_GET['tipo'] ?? '');
$conductor = trim($_GET['conductor'] ?? '');
$propietario = trim($_GET['propietario'] ?? '');

$sql = "
    SELECT
        v.placa,
        v.color,
        v.marca,
        v.tipo,
        CONCAT_WS(' ', c.primer_nombre, c.segundo_nombre, c.apellidos)
            AS conductor,
        CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.apellidos)
            AS propietario
    FROM vehiculos v
    INNER JOIN conductores c
        ON v.conductor_cedula = c.cedula
    INNER JOIN propietarios p
        ON v.propietario_cedula = p.cedula
    WHERE 1 = 1
";

$params = [];

if ($placa !== '') {
    $sql .= " AND v.placa LIKE :placa";
    $params[':placa'] = "%$placa%";
}

if ($marca !== '') {
    $sql .= " AND v.marca LIKE :marca";
    $params[':marca'] = "%$marca%";
}

if (in_array($tipo, ['particular', 'publico'], true)) {
    $sql .= " AND v.tipo = :tipo";
    $params[':tipo'] = $tipo;
}

if ($conductor !== '') {
    $sql .= " AND CONCAT_WS(' ', c.primer_nombre, c.segundo_nombre, c.apellidos) LIKE :conductor";
    $params[':conductor'] = "%$conductor%";
}

if ($propietario !== '') {
    $sql .= " AND CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.apellidos) LIKE :propietario";
    $params[':propietario'] = "%$propietario%";
}

$sql .= " ORDER BY v.placa ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================================
   3. EXPORTAR CSV CON LOS FILTROS
===================================== */

if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="informe_vehiculos.csv"');

    // Facilita la lectura de caracteres especiales en Excel.
    echo "\xEF\xBB\xBF";

    $salida = fopen('php://output', 'w');

    // Separador ; para facilitar la apertura en Excel en español.
    fputcsv(
        $salida,
        ['Placa', 'Color', 'Marca', 'Tipo', 'Conductor', 'Propietario'],
        ';'
    );

    foreach ($vehiculos as $vehiculo) {
        fputcsv($salida, [
            $vehiculo['placa'],
            $vehiculo['color'],
            $vehiculo['marca'],
            $vehiculo['tipo'] === 'publico' ? 'Público' : 'Particular',
            $vehiculo['conductor'],
            $vehiculo['propietario']
        ], ';');
    }

    fclose($salida);
    exit;
}

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$tituloPagina = 'Informe de vehículos | Transportes ACME S.A.';
require_once __DIR__ . '/includes/header.php';
?>
<head>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<main>
    <h1>Informe de vehículos</h1>
    <p>Filtra los registros y descarga el resultado de tu consulta.</p>

    <section class="filtros-informe">
        <h2>Filtros de búsqueda</h2>

        <form method="GET" action="informe.php">
            <div class="campos-filtros">

                <div>
                    <label for="placa">Placa</label>
                    <input
                        type="text"
                        id="placa"
                        name="placa"
                        value="<?= escapar($placa) ?>"
                        placeholder="Ej. ABC123"
                    >
                </div>

                <div>
                    <label for="marca">Marca</label>
                    <input
                        type="text"
                        id="marca"
                        name="marca"
                        value="<?= escapar($marca) ?>"
                        placeholder="Ej. Chevrolet"
                    >
                </div>

                <div>
                    <label for="tipo">Tipo de vehículo</label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos los tipos</option>
                        <option value="particular"
                            <?= $tipo === 'particular' ? 'selected' : '' ?>>
                            Particular
                        </option>
                        <option value="publico"
                            <?= $tipo === 'publico' ? 'selected' : '' ?>>
                            Público
                        </option>
                    </select>
                </div>

                <div>
                    <label for="conductor">Conductor</label>
                    <input
                        type="text"
                        id="conductor"
                        name="conductor"
                        value="<?= escapar($conductor) ?>"
                        placeholder="Nombre del conductor"
                    >
                </div>

                <div>
                    <label for="propietario">Propietario</label>
                    <input
                        type="text"
                        id="propietario"
                        name="propietario"
                        value="<?= escapar($propietario) ?>"
                        placeholder="Nombre del propietario"
                    >
                </div>

            </div>

            <div class="acciones-filtros">
                <button type="submit">Buscar</button>

                <button type="submit" name="exportar" value="csv">
                    Descargar informe CSV
                </button>

                <a href="informe.php" class="boton-limpiar">
                    Limpiar filtros
                </a>
            </div>
        </form>
    </section>

    <section class="tabla-informe">
        <div class="resumen-informe">
            <h2>Resultados</h2>
            <span>
                Registros encontrados: <strong><?= count($vehiculos) ?></strong>
            </span>
        </div>

        <div class="tabla-desplazable">
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
                    <?php if (count($vehiculos) > 0): ?>
                        <?php foreach ($vehiculos as $v): ?>
                            <tr>
                                <td><?= escapar($v['placa']) ?></td>
                                <td><?= escapar($v['color']) ?></td>
                                <td><?= escapar($v['marca']) ?></td>
                                <td>
                                    <?= $v['tipo'] === 'publico'
                                        ? 'Público'
                                        : 'Particular' ?>
                                </td>
                                <td><?= escapar($v['conductor']) ?></td>
                                <td><?= escapar($v['propietario']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                No se encontraron vehículos con esos filtros.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
