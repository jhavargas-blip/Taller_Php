<?php
// ============================================================
// ACTIVIDAD 5: Conversor Decimal a Binario
// Convierte un número entero decimal (base 10) a binario (base 2).
// ============================================================

// Obtener número del formulario
$numero = $_POST['numero'] ?? '';
$resultado = '';  // Resultado binario
$mensaje = '';    // Errores de validación

// Procesar solo si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanear: FILTER_VALIDATE_INT devuelve el entero o false si no es válido
    $numeroEntero = filter_var($numero, FILTER_VALIDATE_INT);

    if ($numeroEntero === false) {
        // No es un número entero válido
        $mensaje = 'Ingresa un número entero válido.';
    } elseif ($numeroEntero < 0) {
        // No permitir negativos
        $mensaje = 'Ingresa un número entero mayor o igual a cero.';
    } else {
        // Conversión nativa de PHP: decimal -> binario (string)
        // decbin() devuelve la representación binaria sin prefijo "0b"
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
    <!-- CSS base: variables, tipografía, tema oscuro -->
    <link rel="stylesheet" href="css/base.css">
    <!-- CSS actividades: formularios, resultados, botones -->
    <link rel="stylesheet" href="css/actividades.css">
</head>

<!-- Clase de color específica: naranja -->
<body class="actividad-pagina actividad-binario">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 5</p>
        <h1>Conversor de decimal a binario</h1>
        <p class="actividad-descripcion">Ingresa un número entero no negativo para convertirlo a binario.</p>

        <!-- Formulario simple: un input numérico -->
        <form method="POST" class="acronimo-formulario">
            <label for="numero-binario">Número entero</label>
            <!-- step=1 fuerza enteros, min=0 evita negativos en UI -->
            <input id="numero-binario" type="number" name="numero" step="1" min="0" value="<?php echo htmlspecialchars((string) $numero, ENT_QUOTES, 'UTF-8'); ?>" required>
            <button class="acronimo-convertir" type="submit">Convertir</button>
        </form>

        <!-- Área de resultado -->
        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultado en binario</h2>
            <?php
            if ($mensaje !== '') {
                // Mostrar error con clase de estilo rojo
                echo '<p class="actividad-error">' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
            } elseif ($resultado !== '') {
                // Mostrar binario (output asociado al input para accesibilidad)
                echo '<output for="numero-binario">' . htmlspecialchars($resultado, ENT_QUOTES, 'UTF-8') . '</output>';
            } else {
                // Placeholder inicial
                echo '<output>Aquí aparecerá el resultado.</output>';
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#binario">Volver al menú</a>
    </main>
</body>
</html>