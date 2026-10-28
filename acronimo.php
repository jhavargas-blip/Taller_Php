<?php
$frase = $_POST['frase'] ?? '';
$resultado = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frase = trim($frase);
    $frase = preg_replace('/[^\pL\pN\s\-]+/u', '', $frase);
    $frase = preg_replace('/\s+/', ' ', $frase);
    $palabras = preg_split('/[\s\-]+/u', $frase, -1, PREG_SPLIT_NO_EMPTY);

    if (empty($palabras)) {
        $mensaje = 'Escribe una frase con al menos una palabra.';
    } else {
        foreach ($palabras as $palabra) {
            $resultado .= mb_strtoupper(mb_substr($palabra, 0, 1, 'UTF-8'), 'UTF-8');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acrónimo | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>

<body class="actividad-pagina actividad-acronimo">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 1</p>
        <h1>Conversor de acrónimos</h1>
        <p class="actividad-descripcion">Escribe una frase para formar un acrónimo con la primera letra de cada palabra.
        </p>

        <form class="acronimo-formulario" method="POST">
            <label for="frase-acronimo">Frase</label>
            <textarea id="frase-acronimo" name="frase" rows="3" maxlength="300"
                placeholder="Ejemplo: Portable Network Graphics"
                required><?php echo htmlspecialchars($frase, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <button class="acronimo-convertir" type="submit">Crear acrónimo</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultado</h2>
            <?php
            if ($resultado === '') {
                $resultado = 'Aquí aparecerá el acrónimo.';
            }
            echo '<output id="resultado-acronimo" for="frase-acronimo">' .
                htmlspecialchars($resultado, ENT_QUOTES, 'UTF-8') . '</output>';
            echo '<p id="mensaje-acronimo" class="acronimo-mensaje" role="status">' .
                htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
            ?>
        </section>

        <a class="actividad-volver" href="index.html#acronimo">Volver al menú</a>
    </main>
</body>

</html>