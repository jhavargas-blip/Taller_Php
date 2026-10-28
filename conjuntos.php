<?php
$conjuntoA = $_POST['conjunto_a'] ?? '';
$conjuntoB = $_POST['conjunto_b'] ?? '';
$union = [];
$interseccion = [];
$diferenciaAB = [];
$diferenciaBA = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numerosA = preg_split('/[\s,;]+/', trim($conjuntoA), -1, PREG_SPLIT_NO_EMPTY);
    $numerosB = preg_split('/[\s,;]+/', trim($conjuntoB), -1, PREG_SPLIT_NO_EMPTY);
    $valoresA = [];
    $valoresB = [];

    foreach ($numerosA as $numero) {
        $numero = (int) $numero;
        if (!in_array($numero, $valoresA, true)) {
            $valoresA[] = $numero;
        }
    }

    foreach ($numerosB as $numero) {
        $numero = (int) $numero;
        if (!in_array($numero, $valoresB, true)) {
            $valoresB[] = $numero;
        }
    }

    foreach ($valoresA as $numero) {
        if (!in_array($numero, $union, true)) {
            $union[] = $numero;
        }
        if (in_array($numero, $valoresB, true)) {
            $interseccion[] = $numero;
        } else {
            $diferenciaAB[] = $numero;
        }
    }

    foreach ($valoresB as $numero) {
        if (!in_array($numero, $union, true)) {
            $union[] = $numero;
        }
        if (!in_array($numero, $valoresA, true)) {
            $diferenciaBA[] = $numero;
        }
    }

    sort($union);
    sort($interseccion);
    sort($diferenciaAB);
    sort($diferenciaBA);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones con conjuntos | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>

<body class="actividad-pagina actividad-conjuntos">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 4</p>
        <h1>Operaciones con conjuntos</h1>
        <p class="actividad-descripcion">Calcula la unión, intersección y diferencias entre dos conjuntos de números
            enteros.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="conjunto-a">Conjunto A</label>
            <input id="conjunto-a" type="text" name="conjunto_a"
                value="<?php echo htmlspecialchars($conjuntoA, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ejemplo: 1, 2, 3, 4">

            <label for="conjunto-b">Conjunto B</label>
            <input id="conjunto-b" type="text" name="conjunto_b"
                value="<?php echo htmlspecialchars($conjuntoB, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ejemplo: 3, 4, 5, 6">

            <button class="acronimo-convertir" type="submit">Calcular</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultados</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $unionTexto = empty($union) ? '∅' : implode(', ', $union);
                $interseccionTexto = empty($interseccion) ? '∅' : implode(', ', $interseccion);
                $diferenciaABTexto = empty($diferenciaAB) ? '∅' : implode(', ', $diferenciaAB);
                $diferenciaBATexto = empty($diferenciaBA) ? '∅' : implode(', ', $diferenciaBA);

                echo '<p><strong>Unión:</strong> ' . $unionTexto . '</p>';
                echo '<p><strong>Intersección:</strong> ' . $interseccionTexto . '</p>';
                echo '<p><strong>A - B:</strong> ' . $diferenciaABTexto . '</p>';
                echo '<p><strong>B - A:</strong> ' . $diferenciaBATexto . '</p>';
            } else {
                echo '<output>Aquí aparecerán los resultados.</output>';
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#conjuntos">Volver al menú</a>
    </main>
</body>

</html>