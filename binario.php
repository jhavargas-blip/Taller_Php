<?php
$numero = $_POST['numero'] ?? '';
$resultado = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroEntero = filter_var($numero, FILTER_VALIDATE_INT);

    if ($numeroEntero === false) {
        $mensaje = 'Ingresa un número entero válido.';
    } elseif ($numeroEntero < 0) {
        $mensaje = 'Ingresa un número entero mayor o igual a cero.';
    } else {
        $resultado = decbin($numeroEntero);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor binario | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>
<body class="actividad-pagina actividad-binario">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 5</p>
        <h1>Conversor de decimal a binario</h1>
        <p class="actividad-descripcion">Ingresa un número entero no negativo para convertirlo a binario.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="numero-binario">Número entero</label>
            <input id="numero-binario" type="number" name="numero" step="1" min="0" value="<?php echo htmlspecialchars((string) $numero, ENT_QUOTES, 'UTF-8'); ?>" required>
            <button class="acronimo-convertir" type="submit">Convertir</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultado en binario</h2>
            <?php
            if ($mensaje !== '') {
                echo '<p class="actividad-error">' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
            } elseif ($resultado !== '') {
                echo '<output for="numero-binario">' . htmlspecialchars($resultado, ENT_QUOTES, 'UTF-8') . '</output>';
            } else {
                echo '<output>Aquí aparecerá el resultado.</output>';
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#binario">Volver al menú</a>
    </main>
</body>
</html>
