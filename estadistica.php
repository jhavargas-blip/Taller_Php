<?php
$numerosTexto = $_POST['numeros'] ?? '';
$mensaje = '';
$promedio = null;
$media = null;
$moda = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $partes = preg_split('/[\s,;]+/', trim($numerosTexto), -1, PREG_SPLIT_NO_EMPTY);
    $numeros = [];

    if (empty($partes)) {
        $mensaje = 'Ingresa al menos un número.';
    } else {
        foreach ($partes as $parte) {
            $numeros[] = floatval($parte);
        }

        $cantidad = count($numeros);
        $promedio = array_sum($numeros) / $cantidad;

        sort($numeros);
        $posicionMedia = intdiv($cantidad, 2);

        if ($cantidad % 2 == 0) {
            $media = ($numeros[$posicionMedia - 1] + $numeros[$posicionMedia]) / 2;
        } else {
            $media = $numeros[$posicionMedia];
        }

        $frecuencias = [];
        foreach ($numeros as $numero) {
            $clave = (string) $numero;

            if (isset($frecuencias[$clave])) {
                $frecuencias[$clave]++;
            } else {
                $frecuencias[$clave] = 1;
            }
        }

        $mayorFrecuencia = max($frecuencias);

        foreach ($frecuencias as $numero => $frecuencia) {
            if ($frecuencia == $mayorFrecuencia) {
                $moda[] = (float) $numero;
            }
        }

        sort($moda);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadística | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>

<body class="actividad-pagina actividad-estadistica">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 3</p>
        <h1>Medidas estadísticas</h1>
        <p class="actividad-descripcion">Calcula el promedio, la media y la moda de una serie de números reales.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="numeros">Números</label>
            <textarea id="numeros" name="numeros" rows="4" placeholder="Ejemplo: 2, 4, 4, 7, 9"
                required><?php echo htmlspecialchars($numerosTexto, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <button class="acronimo-convertir" type="submit">Calcular</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultados</h2>
            <?php
            if ($mensaje !== '') {
                echo '<output>' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</output>';
            } elseif ($promedio !== null) {
                $modaTexto = [];
                foreach ($moda as $valor) {
                    $modaTexto[] = number_format($valor, 2, ',', '.');
                }

                echo '<p><strong>Promedio:</strong> ' . number_format($promedio, 2, ',', '.') . '</p>';
                echo '<p><strong>Media:</strong> ' . number_format($media, 2, ',', '.') . '</p>';
                echo '<p><strong>Moda:</strong> ' . htmlspecialchars(implode(', ', $modaTexto), ENT_QUOTES, 'UTF-8') . '</p>';
            } else {
                echo '<output>Aquí aparecerán los resultados.</output>';
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#estadistica">Volver al menú</a>
    </main>
</body>

</html>