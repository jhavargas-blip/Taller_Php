<?php
session_start();

if (!isset($_SESSION['historial_calculadora'])) {
    $_SESSION['historial_calculadora'] = [];
}

$numeroA = $_POST['numero_a'] ?? '';
$numeroB = $_POST['numero_b'] ?? '';
$operacion = $_POST['operacion'] ?? 'suma';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['accion'] ?? '') === 'borrar') {
        $_SESSION['historial_calculadora'] = [];
    } elseif (!is_numeric($numeroA) || !is_numeric($numeroB)) {
        $mensaje = 'Ingresa dos números enteros o decimales válidos.';
    } else {
        $a = (float) $numeroA;
        $b = (float) $numeroB;
        $resultado = 0;
        $expresion = '';

        switch ($operacion) {
            case 'suma':
                $expresion = "$a + $b";
                $resultado = $a + $b;
                break;
            case 'resta':
                $expresion = "$a - $b";
                $resultado = $a - $b;
                break;
            case 'multiplicacion':
                $expresion = "$a × $b";
                $resultado = $a * $b;
                break;
            case 'division':
                if ($b == 0) {
                    $mensaje = 'No se puede dividir por cero.';
                } else {
                    $expresion = "$a ÷ $b";
                    $resultado = $a / $b;
                }
                break;
            case 'porcentaje':
                $expresion = "$b% de $a";
                $resultado = $a * ($b / 100);
                break;
            default:
                $mensaje = 'Selecciona una operación válida.';
        }

        if ($mensaje === '' && !is_finite($resultado)) {
            $mensaje = 'El resultado excede el rango numérico permitido.';
        }

        if ($mensaje === '') {
            $entradaHistorial = [
                'expresion' => $expresion,
                'resultado' => $resultado,
                'fecha' => date('Y-m-d H:i:s')
            ];
            array_unshift($_SESSION['historial_calculadora'], $entradaHistorial);
        }
    }
}

$historial = $_SESSION['historial_calculadora'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora | Taller PHP</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/actividades.css">
</head>
<body class="actividad-pagina actividad-calculadora">
    <main class="actividad-contenido">
        <p class="actividad-etiqueta">Actividad 7</p>
        <h1>Calculadora</h1>
        <p class="actividad-descripcion">Realiza operaciones con números enteros o decimales. Para porcentaje calcula el segundo valor como porcentaje del primero.</p>

        <form method="POST" class="acronimo-formulario">
            <label for="numero-a">Primer número</label>
            <input id="numero-a" type="number" name="numero_a" step="any" value="<?php echo htmlspecialchars((string) $numeroA, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="numero-b">Segundo número</label>
            <input id="numero-b" type="number" name="numero_b" step="any" value="<?php echo htmlspecialchars((string) $numeroB, ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="operacion">Operación</label>
            <select id="operacion" name="operacion">
                <option value="suma" <?php if ($operacion === 'suma') echo 'selected'; ?>>Suma</option>
                <option value="resta" <?php if ($operacion === 'resta') echo 'selected'; ?>>Resta</option>
                <option value="multiplicacion" <?php if ($operacion === 'multiplicacion') echo 'selected'; ?>>Multiplicación</option>
                <option value="division" <?php if ($operacion === 'division') echo 'selected'; ?>>División</option>
                <option value="porcentaje" <?php if ($operacion === 'porcentaje') echo 'selected'; ?>>Porcentaje</option>
            </select>
            <button class="acronimo-convertir" type="submit" name="accion" value="calcular">Calcular</button>
        </form>

        <?php
        if ($mensaje !== '') {
            echo '<p class="actividad-error" role="alert">' .
                htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        ?>

        <section class="acronimo-resultado historial" aria-live="polite">
            <h2>Historial</h2>
            <?php
            if ($historial === []) {
                echo '<p>No hay operaciones guardadas.</p>';
            } else {
                echo '<ol>';
                foreach ($historial as $elemento) {
                    echo '<li><span>' . htmlspecialchars($elemento['expresion'], ENT_QUOTES, 'UTF-8') .
                        '</span> <strong>' .
                        htmlspecialchars((string) $elemento['resultado'], ENT_QUOTES, 'UTF-8') .
                        '</strong></li>';
                }
                echo '</ol>';
                ?>
                <form method="POST" class="formulario-borrar-historial">
                    <button class="acronimo-convertir" type="submit" name="accion" value="borrar">Borrar historial</button>
                </form>
                <?php
            }
            ?>
        </section>

        <a class="actividad-volver" href="index.html#calculadora">Volver al menú</a>
    </main>
</body>
</html>
