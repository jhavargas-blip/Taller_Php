<?php
$numero = $_POST['numero'] ?? 0;
$operacion = $_POST['operacion'] ?? 'fibonacci';
$resultado = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = (int) $numero;

    if ($numero < 0) {
        $mensaje = 'El número debe ser mayor o igual a 0.';
    } elseif ($operacion === 'fibonacci') {
        $serie = [];
        $a = 0;
        $b = 1;

        for ($i = 0; $i < $numero; $i++) {
            $serie[] = $a;
            $temporal = $a + $b;
            $a = $b;
            $b = $temporal;
        }

        if ($numero === 0) {
            $serie = [0];
        }

        $resultado = implode(', ', $serie);
    } else {
        $factorial = 1;

        for ($i = 2; $i <= $numero; $i++) {
            $factorial *= $i;
        }

        $resultado = (string) $factorial;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fibonacci y factorial | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>

<body class="actividad-pagina actividad-fibonacci">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 2</p>
        <h1>Fibonacci y factorial</h1>
        <p class="actividad-descripcion">Calcula la sucesión de Fibonacci o el factorial del número ingresado.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="numero">Número</label>
            <input id="numero" type="number" name="numero" min="0"
                value="<?php echo htmlspecialchars((string) $numero, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="operacion">Operación</label>
            <select id="operacion" name="operacion">
                <option value="fibonacci" <?php if ($operacion === 'fibonacci') echo 'selected'; ?>>Fibonacci</option>
                <option value="factorial" <?php if ($operacion === 'factorial') echo 'selected'; ?>>Factorial</option>
            </select>

            <button class="acronimo-convertir" type="submit">Calcular</button>
        </form>

        <section class="acronimo-resultado" aria-live="polite" aria-atomic="true">
            <h2>Resultado</h2>
            <?php
            if ($mensaje !== '') {
                $texto = $mensaje;
            } elseif ($resultado !== '') {
                $texto = $resultado;
            } else {
                $texto = 'Aquí aparecerá el resultado.';
            }
            echo '<output for="numero">' . htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') . '</output>';
            ?>
        </section>

        <a class="actividad-volver" href="index.html#fibonacci">Volver al menú</a>
    </main>
</body>

</html>